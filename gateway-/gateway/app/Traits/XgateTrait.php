<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Solicitacoes;
use App\Models\SolicitacoesCashOut;
use App\Models\App;
use App\Models\User;
use App\Models\Xgate;
use App\Helpers\Helper;
use App\Services\XGate as XGateService;

trait XgateTrait
{

    public static function requestDepositXgate($data)
    {

        $xgate = new XGateService();
        $response = $xgate->genPayment($data);

        if (isset($response['id'])) {
            $setting = App::first();
            $user = $data->user;
            $taxafixa = $user->taxa_cash_in_fixa;


            $taxatotal = ((float)$data->amount * (float)$user->taxa_cash_in / 100);
            $deposito_liquido = (float)$data->amount - $taxatotal;
            $taxa_cash_in = $taxatotal;
            $descricao = "PORCENTAGEM";

            if ((float)$taxatotal < (float)$setting->baseline) {
                $deposito_liquido = (float)$data->amount - (float)$setting->baseline;
                $taxa_cash_in = (float)$setting->baseline;
                $descricao = "FIXA";
            }


            $deposito_liquido = $deposito_liquido - $taxafixa;
            $taxa_cash_in = $taxa_cash_in + $taxafixa;

            $date = Carbon::now();

            $cashin = [
                "user_id"                       => $data->user->username,
                "externalreference"             => $response['id'],
                "amount"                        => $data->amount,
                "client_name"                   => $data->debtor_name,
                "client_document"               => $data->debtor_document_number,
                "client_email"                  => $data->email,
                "date"                             => $date,
                "status"                        => 'WAITING_FOR_APPROVAL',
                "idTransaction"                 => $response['id'],
                "deposito_liquido"              => $deposito_liquido,
                "qrcode_pix"                    => $response['code'],
                "paymentcode"                   => $response['code'],
                "paymentCodeBase64"             => $response['code'],
                "adquirente_ref"                => 'xgate',
                "taxa_cash_in"                  => $taxa_cash_in,
                "taxa_pix_cash_in_adquirente"   => 0,
                "taxa_pix_cash_in_valor_fixo"   => $taxafixa,
                "client_telefone"               => $data->phone,
                "executor_ordem"                => 'xgate',
                "descricao_transacao"           => $descricao,
                "callback"                      => $data->postback,
                "split_email"                   => null,
                "split_percentage"              => null,
            ];

            Solicitacoes::create($cashin);

            if (!is_null($user->integracao_utmfy)) {

                $ip = $data->header('X-Forwarded-For') ?
                    $data->header('X-Forwarded-For') : ($data->header('CF-Connecting-IP') ?
                        $data->header('CF-Connecting-IP') :
                        $data->ip());

                $msg = "PIX Gerado " . env('APP_NAME');
                UtmfyTrait::gerarUTM('pix', 'waiting_payment', $cashin, $user->integracao_utmfy, $ip, $msg);
            }

            return [
                "data" => [
                    "idTransaction" => $response['id'],
                    "qrcode" => $response['code'],
                    "qr_code_image_url" => 'https://quickchart.io/qr?text=' . urlencode($response['code'])
                ],
                "status" => 200
            ];
        }
    }

    public static function requestPaymentXgate($request)
    {
        $data = $request->all();

        $user = User::where('id', $request->user->id)->first();

        $setting = App::first();

        $user = $request->user;
        $taxafixa = $user->taxa_cash_out_fixa;

        $taxatotal = ((float)$request->amount * (float)$user->taxa_cash_out / 100);
        $cashout_liquido = (float)$request->amount - $taxatotal;
        $taxa_cash_out = $taxatotal;
        $descricao = "PORCENTAGEM";

        if ((float)$taxatotal < (float)$setting->baseline) {
            $cashout_liquido = (float)$request->amount - (float)$setting->baseline;
            $taxa_cash_out = (float)$setting->baseline;
            $descricao = "FIXA";
        }

        $cashout_liquido = $cashout_liquido - $taxafixa;
        $taxa_cash_out = $taxa_cash_out + $taxafixa;

        if ($user->saldo < $cashout_liquido) {
            return [
                'status' => 401,
                'data' => ['message' => "Saldo insuficiente."]
            ];
        }

        $date = Carbon::now();

        if ($request->baasPostbackUrl === 'web') {
            return self::generateTransactionPaymentManual($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user);
        }

        $xgate = new XGateService();
        $response = $xgate->genWithdraw($data);

        if (isset($response['message'])) {
            return [
                'status' => 401,
                'data' => ['message' => "Houve um erro. Tente novamente mais tarde."]
            ];
        }

        if (isset($response['status'])) {
            $name = "Cliente de " . explode(' ', $request->user->name)[0] . ' ' . explode(' ', $request->user->name)[1];

            $pixKey = $request->pixKey;

            switch ($request->pixKeyType) {
                case 'cpf':
                case 'cnpj':
                case 'phone':
                    $pixKey = preg_replace('/[^0-9]/', '', $pixKey);
                    break;
            }

            $pixcashout = [
                "user_id"               => $request->user->username,
                "externalreference"     => $response['id'],
                "amount"                => $request->amount,
                "beneficiaryname"       => $name,
                "beneficiarydocument"   => $pixKey,
                "pix"                   => $pixKey,
                "pixkey"                => strtolower($request->pixKeyType),
                "date"                  => $date,
                "status"                => "PENDING",
                "type"                  => "PIX",
                "idTransaction"         => $response['id'],
                "taxa_cash_out"         => $taxa_cash_out,
                "cash_out_liquido"      => $cashout_liquido,
                "end_to_end"            => $response['id'],
                "callback"              => $request->baasPostbackUrl,
                "descricao_transacao"   => $descricao
            ];

            SolicitacoesCashOut::create($pixcashout);

            return [
                "status" => 200,
                "data" => [
                    "id"                => $response['id'],
                    "amount"            => $request->amount,
                    "pixKey"            => $request->pixKey,
                    "pixKeyType"        => $request->pixKeyType,
                    "withdrawStatusId"  => "PendingProcessing",
                    "createdAt"         => $date,
                    "updatedAt"         => $date
                ]
            ];
        }
    }

    protected static function generateTransactionPaymentManual($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user)
    {
        $idTransaction = Str::uuid()->toString();

        $name = "Cliente de " . $request->user->name;
        $pixKey = $request->pixKey;

        switch ($request->pixKeyType) {
            case 'cpf':
            case 'cnpj':
            case 'phone':
                $pixKey = preg_replace('/[^0-9]/', '', $pixKey);
                break;
        }

        $pixcashout = [
            "user_id"               => $request->user->username,
            "externalreference"     => $idTransaction,
            "amount"                => $request->amount,
            "beneficiaryname"       => $name,
            "beneficiarydocument"   => $pixKey,
            "pix"                   => $pixKey,
            "pixkey"                => strtolower($request->pixKeyType),
            "date"                  => $date,
            "status"                => "PENDING",
            "type"                  => $request->pixKeyType == "crypto" ? "CRYPTO" : "PIX",
            "idTransaction"         => $idTransaction,
            "taxa_cash_out"         => $taxa_cash_out,
            "cash_out_liquido"      => $cashout_liquido,
            "end_to_end"            => $idTransaction,
            "callback"              => $request->baasPostbackUrl,
            "blockchainNetwork"     => $request->blockchainNetwork ?? null,
            "cryptocurrency"        => $request->cryptocurrency ?? null,
            "descricao_transacao"   => "WEB"
        ];

        $cashout = SolicitacoesCashOut::create($pixcashout);

        return [
            "status" => 200,
            "data" => [
                "id"                => $idTransaction,
                "amount"            => $request->amount,
                "pixKey"            => $request->pixKey,
                "pixKeyType"        => $request->pixKeyType,
                "withdrawStatusId"  => "PendingProcessing",
                "createdAt"         => $date,
                "updatedAt"         => $date
            ]
        ];
    }

    public static function liberarSaqueManual($id)
    {

        $cashout = SolicitacoesCashOut::where('id', $id)->with('user')->first();
        $callback = url("cashtime/callback/withdraw");

        $xgate = new XGateService();
        if ($cashout->type == "CRYPTO") {
            $payload = [];
            $payload['amount'] = (float) $cashout->cash_out_liquido;
            $payload["blockchainNetwork"] = $cashout->blockchainNetwork;
            $payload["cryptocurrency"] = $cashout->cryptocurrency;
            $payload["wallet"] = $cashout->pix;

            $dt = [];
            $dt["user"] = $cashout->user;

            $response = $xgate->genWithdrawCrypto($payload, $dt);
            if (isset($response['message'])) {
                return back()->with('error', $response['message']);
            }


            $pixcashout = [
                "externalreference"     => $response['id'],
                "idTransaction"         => $response['id'],
                "end_to_end"            => $response['id'],
                "descricao_transacao"   => "LIBERADOADMIN"
            ];

            $cashout = SolicitacoesCashOut::where('id', $id)->update($pixcashout);
            return back()->with('success', 'Pedido de saque enviado com sucesso!');
        }

        $data = [
            'amount' => $cashout->cash_out_liquido,
            'pixKeyType' => $cashout->pixkey,
            'pixKey' => $cashout->pix,
            'user' => $cashout->user
        ];
        $response = $xgate->genWithdraw($data);

        if (isset($response['message'])) {
            return back()->with('error', $response['message']);
        }


        $pixcashout = [
            "externalreference"     => $response['id'],
            "idTransaction"         => $response['id'],
            "end_to_end"            => $response['id'],
            "descricao_transacao"   => "LIBERADOADMIN"
        ];

        $cashout = SolicitacoesCashOut::where('id', $id)->update($pixcashout);
        return back()->with('success', 'Pedido de saque enviado com sucesso!');
    }
}
