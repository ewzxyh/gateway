<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\Solicitacoes;
use App\Models\User;
use App\Models\SolicitacoesCashOut;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\App;
use App\Models\CheckoutOrders;
use App\Models\Transactions;
use App\Traits\UtmfyTrait;

class CallbackController extends Controller
{
    public function callbackDeposit(Request $request)
    {
        $data = $request->all();
        if ($data['status'] != "paid") {
            return response()->json(['status' => false, 'message' => 'Not paid']);
        }

        $cashin = Solicitacoes::where('idTransaction', $data['orderId'])->first();

        if (!$cashin || $cashin->status != "WAITING_FOR_APPROVAL") {
            return response()->json(['status' => false, 'message' => 'Invalid solicitation']);
        }

        $cashin->update(['status' => 'PAID_OUT', 'updated_at' => Carbon::now()]);

        $user = User::where('user_id', $cashin->user_id)->first();
        if ($user) {
            Helper::incrementAmount($user, $cashin->deposito_liquido, 'saldo');
            Helper::calculaSaldoLiquido($user->user_id);

            if (isset($user->gerente_id) && !is_null($user->gerente_id)) {
                $gerente = User::where('id', $user->gerente_id)->first();
                if ($gerente) {
                    $gerente_porcentagem = $gerente->gerente_percentage;
                    $valor = (float)$cashin->taxa_cash_in * (float)$gerente_porcentagem / 100;

                    Transactions::create([
                        'user_id' => $user->user_id,
                        'gerente_id' => $user->gerente_id,
                        'solicitacao_id' => $cashin->id,
                        'comission_value' => $valor,
                        'transaction_percent' => $cashin->taxa_cash_in,
                        'comission_percent' => $gerente_porcentagem,
                    ]);

                    Helper::calculaSaldoLiquido($gerente->user_id);
                }
            }

            $order = CheckoutOrders::where('idTransaction', $data['orderId'])->first();
            if ($order) {
                $order->update(['status' => 'pago']);
                if (!is_null($user->webhook_url) && in_array('pago', (array) $user->webhook_endpoint)) {
                    Http::post($user->webhook_url, [
                        'nome' => $order->name,
                        'cpf' => preg_replace('/\D/', '', $order->cpf),
                        'telefone' => preg_replace('/\D/', '', $order->telefone),
                        'email' => $order->email,
                        'status' => 'pago'
                    ]);
                }
            }

            if (!is_null($user->integracao_utmfy)) {
                $ip = $request->ip();
                $msg = "PIX Pago " . env('APP_NAME');
                UtmfyTrait::gerarUTM('PIX', 'paid', $cashin, $user->integracao_utmfy, $ip, $msg);
            }
        }

        if ($cashin->callback) {
            $payload = [
                "status" => "paid",
                "idTransaction" => $cashin->idTransaction,
                "typeTransaction" => "PIX"
            ];

            Http::post($cashin->callback, $payload);
            \Log::debug("[PIX-IN] Send Callback: Para $cashin->callback -> Enviando...");

            if ($cashin->callback != 'web') {
                return response()->json(['status' => 'paid']);
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function callbackWithdraw(Request $request)

    {
        $data = $request->all();

        \Log::debug("[PIX-OUT] Received Callback: " . json_encode($data));

        if ($data['withdrawStatusId'] == "Successfull") {
            $cashout = SolicitacoesCashOut::where('idTransaction', $data['id'])->first();

            if (!$cashout || $cashout->status != "PENDING") {
                return response()->json(['status' => false]);
            }

            $cashout->update(['status' => 'COMPLETED', 'updated_at' => $data['updatedAt']]);
            $user = User::where('user_id', $cashout->user_id)->first();
            if ($user) {
                Helper::decrementAmount($user, $request->amount, 'valor_saque_pendente');
            }


            if ($cashout->callback) {
                $payload = [
                    "status"            => "paid",
                    "idTransaction"     => $cashout->idTransaction,
                    "typeTransaction"   => "PAYMENT"
                ];

                $sendcallback = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'accept' => 'application/json'
                ])->post($cashout->callback, $payload);

                \Log::debug("[PIX-OUT] Send Callback: Para $cashout->callback -> Enviando...");
                if ($cashout->callback && $cashout->callback != 'web') {
                    $payload = [
                        "status"            => "paid",
                        "idTransaction"     => $cashout->idTransaction,
                        "typeTransaction"   => "PAYMENT"
                    ];

                    Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'accept' => 'application/json'
                    ])->post($cashout->callback, $payload);

                    return response()->json(['status' => $success]);
                }
            }
        }
    }

    public function callbackDepositMercadopago(Request $request)
    {
        $data = $request->all();
        if (isset($data['action']) && $data['action'] == 'payment.updated' && isset($data['data_id'])) {
            $idTransaction = $data['data_id'];
            $status = $request->status;
            if (isset($status) && $status === 'paid') {
                $cashin = Solicitacoes::where('idTransaction', $idTransaction)->first();

                if (!$cashin || $cashin->status != "WAITING_FOR_APPROVAL") {
                    return response()->json(['status' => false]);
                }

                $updated_at = Carbon::now();
                $cashin->update(['status' => 'PAID_OUT', 'updated_at' => $updated_at]);

                $user = User::where('user_id', $cashin->user_id)->first();
                if ($user) {
                    Helper::incrementAmount($user, $cashin->deposito_liquido, 'saldo');
                    Helper::calculaSaldoLiquido($user->user_id);
                }

                $order = CheckoutOrders::where('idTransaction', $data['orderId'])->first();
                if ($order) {
                    $order->update(['status' => 'pago']);
                    if (!is_null($user->webhook_url) && in_array('pago', (array) $user->webhook_endpoint)) {
                        Http::withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
                            ->post($user->webhook_url, [
                                'nome' => $order->name,
                                'cpf' => preg_replace('/\D/', '', $order->cpf),
                                'telefone' => preg_replace('/\D/', '', $order->telefone),
                                'email' => $order->email,
                                'status' => 'pago'
                            ]);
                    }
                }

                if ($user && isset($user->gerente_id) && !is_null($user->gerente_id)) {
                    $gerente = User::where('id', $user->gerente_id)->first();
                    if ($gerente) {
                        $gerente_porcentagem = $gerente->gerente_percentage;

                        $valor = (float)$cashin->taxa_cash_in * (float)$gerente_porcentagem / 100;

                        Transactions::create([
                            'user_id' => $user->user_id,
                            'gerente_id' => $user->gerente_id,
                            'solicitacao_id' => $cashin->id,
                            'comission_value' => $valor,
                            'transaction_percent' => $cashin->taxa_cash_in,
                            'comission_percent' => $gerente_porcentagem,
                        ]);

                        Helper::calculaSaldoLiquido($gerente->user_id);
                    }
                }

                if (!is_null($user->integracao_utmfy)) {
                    $payload = [
                        'name' => $cashin['client_name'],
                        'email' => $cashin['client_email'],
                        'phone' => $cashin['client_telefone'],
                        'cpf' => $cashin['client_document'],
                        'value' => $cashin->amount
                    ];
                    $ip = $request->header('X-Forwarded-For') ?
                        $request->header('X-Forwarded-For') : ($request->header('CF-Connecting-IP') ?
                            $request->header('CF-Connecting-IP') :
                            $request->ip());
                    $msg = "PIX Pago " . env('APP_NAME');
                    UtmfyTrait::gerarUTM('PIX', 'paid', $cashin, $user->integracao_utmfy, $ip, $msg);
                }

                if ($cashin->callback) {
                    $payload = [
                        "status"            => "paid",
                        "idTransaction"     => $cashin->idTransaction,
                        "typeTransaction"   => "PIX"
                    ];

                    Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'accept' => 'application/json'
                    ])->post($cashin->callback, $payload);

                    \Log::debug("[PIX-IN] Send Callback: Para $cashin->callback -> Enviando...");
                    if ($cashin->callback && $cashin->callback != 'web') {
                        $payload = [
                            "status"            => "paid",
                            "idTransaction"     => $cashin->idTransaction,
                            "typeTransaction"   => "PIX"
                        ];

                        Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'accept' => 'application/json'
                        ])->post($cashin->callback, $payload);

                        $success = 'paid';
                        return response()->json(['status' => $success]);
                    } else {
                        $order = CheckoutOrders::where('idTransaction', $data['data_id'])->first();
                        if ($order) {
                            $order->update(['status' => 'pago']);
                            if (!is_null($user->webhook_url) && in_array('pago', (array) $user->webhook_endpoint)) {
                                Http::withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
                                    ->post($user->webhook_url, [
                                        'nome' => $order->name,
                                        'cpf' => preg_replace('/\D/', '', $order->cpf),
                                        'telefone' => preg_replace('/\D/', '', $order->telefone),
                                        'email' => $order->email,
                                        'status' => 'pago'
                                    ]);
                            }
                        }
                    }
                }
            }
        }

        return response()->json([], 200);
    }

    public function callbackEfi(Request $request)
    {
        $data = $request->all();
        \Log::debug('[+] Callback Efí: ' . json_encode($data));

        $dados = $data['pix'][0];
        $tipo = isset($dados['tipo']) && $dados['tipo'] == 'SOLICITACAO' ? 'saque' : 'deposito';
        \Log::debug('[+] Tipo de callback Efí: ' . $tipo);

        switch ($tipo) {
            case 'deposito':
                $idTransaction = $dados['txid'];
                $existEndToEndId = isset($dados['endToEndId']) && isset($dados['gnExtras']['pagador']);
                if ($existEndToEndId) {
                    $cashin = Solicitacoes::where('idTransaction', $idTransaction)->first();
                    if (!$cashin || $cashin->status != "WAITING_FOR_APPROVAL") {
                        return response()->json(['status' => false]);
                    }

                    $updated_at = Carbon::now();
                    $cashin->update(['status' => 'PAID_OUT', 'updated_at' => $updated_at]);

                    $user = User::where('user_id', $cashin->user_id)->first();
                    if ($user) {
                        Helper::incrementAmount($user, $cashin->deposito_liquido, 'saldo');
                        Helper::calculaSaldoLiquido($user->user_id);
                    }

                    if ($user && isset($user->gerente_id) && !is_null($user->gerente_id)) {
                        $gerente = User::where('id', $user->gerente_id)->first();
                        if ($gerente) {
                            $gerente_porcentagem = $gerente->gerente_percentage;

                            $valor = (float)$cashin->taxa_cash_in * (float)$gerente_porcentagem / 100;

                            Transactions::create([
                                'user_id' => $user->user_id,
                                'gerente_id' => $user->gerente_id,
                                'solicitacao_id' => $cashin->id,
                                'comission_value' => $valor,
                                'transaction_percent' => $cashin->taxa_cash_in,
                                'comission_percent' => $gerente_porcentagem,
                            ]);

                            Helper::calculaSaldoLiquido($gerente->user_id);
                        }
                    }


                    $order = CheckoutOrders::where('idTransaction', $idTransaction)->first();
                    if ($order) {
                        $order->update(['status' => 'pago']);
                        if (!is_null($user->webhook_url) && in_array('pago', (array) $user->webhook_endpoint)) {
                            Http::withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
                                ->post($user->webhook_url, [
                                    'nome' => $order->name,
                                    'cpf' => preg_replace('/\D/', '', $order->cpf),
                                    'telefone' => preg_replace('/\D/', '', $order->telefone),
                                    'email' => $order->email,
                                    'status' => 'pago'
                                ]);
                        }
                    }

                    if (!is_null($user->integracao_utmfy)) {
                        $payload = [
                            'name' => $cashin['client_name'],
                            'email' => $cashin['client_email'],
                            'phone' => $cashin['client_telefone'],
                            'cpf' => $cashin['client_document'],
                            'value' => $cashin->amount
                        ];
                        $ip = $request->header('X-Forwarded-For') ?
                            $request->header('X-Forwarded-For') : ($request->header('CF-Connecting-IP') ?
                                $request->header('CF-Connecting-IP') :
                                $request->ip());
                        $msg = "PIX Pago " . env('APP_NAME');
                        UtmfyTrait::gerarUTM('PIX', 'paid', $cashin, $user->integracao_utmfy, $ip, $msg);
                    }

                    if ($cashin->callback && $cashin->callback != 'web') {
                        $payload = [
                            "status"            => "paid",
                            "idTransaction"     => $cashin->idTransaction,
                            "typeTransaction"   => "PIX"
                        ];

                        Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'accept' => 'application/json'
                        ])->post($cashin->callback, $payload);

                        \Log::debug("[PIX-IN] Send Callback: Para $cashin->callback -> Enviando...");
                        if ($cashin->callback && $cashin->callback != 'web') {
                            $payload = [
                                "status"            => "paid",
                                "idTransaction"     => $cashin->idTransaction,
                                "typeTransaction"   => "PIX"
                            ];

                            Http::withHeaders([
                                'Content-Type' => 'application/json',
                                'accept' => 'application/json'
                            ])->post($cashin->callback, $payload);

                            $success = 'paid';
                            return response()->json(['status' => $success]);
                        } else {
                            $order = CheckoutOrders::where('idTransaction', $dados['txid'])->first();
                            if ($order) {
                                $order->update(['status' => 'pago']);
                                if (!is_null($user->webhook_url) && in_array('pago', (array) $user->webhook_endpoint)) {
                                    Http::withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
                                        ->post($user->webhook_url, [
                                            'nome' => $order->name,
                                            'cpf' => preg_replace('/\D/', '', $order->cpf),
                                            'telefone' => preg_replace('/\D/', '', $order->telefone),
                                            'email' => $order->email,
                                            'status' => 'pago'
                                        ]);
                                }
                            }
                        }
                    }
                }
                break;
            case 'saque':
                $idTransaction = $dados['gnExtras']['idEnvio'];
                if ($dados['status'] == "REALIZADO") {
                    $cashout = SolicitacoesCashOut::where('idTransaction', $idTransaction)->first();
                    if (!$cashout || $cashout->status != "PENDING") {
                        return response()->json(['status' => false]);
                    }

                    $cashout->update(['status' => 'COMPLETED']);
                    $user = User::where('user_id', $cashout->user_id)->first();
                    if ($user) {
                        Helper::decrementAmount($user, $request->amount, 'valor_saque_pendente');
                    }

                    if ($cashout->callback && $cashout->callback != 'web') {
                        $payload = [
                            "status"            => "paid",
                            "idTransaction"     => $cashout->idTransaction,
                            "typeTransaction"   => "PAYMENT"
                        ];

                        $sendcallback = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'accept' => 'application/json'
                        ])->post($cashout->callback, $payload);

                        \Log::debug("[PIX-OUT] Send Callback: Para $cashout->callback -> Enviando...");
                        if ($cashout->callback && $cashout->callback != 'web') {
                            $payload = [
                                "status"            => "paid",
                                "idTransaction"     => $cashout->idTransaction,
                                "typeTransaction"   => "PAYMENT"
                            ];

                            Http::withHeaders([
                                'Content-Type' => 'application/json',
                                'accept' => 'application/json'
                            ])->post($cashout->callback, $payload);
                        }
                    }
                } elseif ($dados['status'] == "NAO_REALIZADO") {
                    $cashout = SolicitacoesCashOut::where('idTransaction', $idTransaction)->first();
                    if ($cashout) {
                        $message = $dados['gnExtras']['erro']['motivo'] ?? 'Erro na Adquirencia.';
                        $cashout->update(['status' => 'CANCELLED', 'descricao_externa' => $message]);
                    }

                    if ($cashout->callback && $cashout->callback != 'web') {
                        $payload = [
                            "status"            => "canceled",
                            "idTransaction"     => $cashout->idTransaction,
                            "typeTransaction"   => "PAYMENT"
                        ];

                        $sendcallback = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'accept' => 'application/json'
                        ])->post($cashout->callback, $payload);

                        \Log::debug("[PIX-OUT] Send Callback: Para $cashout->callback -> Enviando...");
                        if ($cashout->callback && $cashout->callback != 'web') {
                            $payload = [
                                "status"            => "canceled",
                                "idTransaction"     => $cashout->idTransaction,
                                "typeTransaction"   => "PAYMENT"
                            ];

                            Http::withHeaders([
                                'Content-Type' => 'application/json',
                                'accept' => 'application/json'
                            ])->post($cashout->callback, $payload);
                        }
                    }
                }
                break;
        }

        return response()->json([], 200);
    }

    public function webhookPagarme(Request $request)
    {
        $data = $request->all();
        Log::debug("[PAGAR.ME] WEBHOOK: " . json_encode($data));

        $type = $data['type'];
        $status = $data['data']['charges'][0]['last_transaction']['status'];
        $transaction_id = $data['data']['id'];

        Log::debug("[PAGAR.ME] DADOS PRINCIPAIS: " . json_encode(compact('type', 'status', 'transaction_id')));
        Log::debug("[PAGAR.ME] WEBHOOK PIX PAGO?: " . '' . ($type == "order.paid" && $status == "paid"));
        if ($type == "order.paid" && $status == "paid") {
            $cashin = Solicitacoes::where('idTransaction', $transaction_id)->first();
            Log::debug("[PAGAR.ME] SOLICITAÇÃO: " . json_encode($cashin));

            if (!$cashin || $cashin->status != "WAITING_FOR_APPROVAL") {
                return response()->json(['status' => false]);
            }

            $updated_at = Carbon::now();
            $cashin->update(['status' => 'PAID_OUT', 'updated_at' => $updated_at]);

            $user = User::where('user_id', $cashin->user_id)->first();
            if ($user) {
                Helper::incrementAmount($user, $cashin->deposito_liquido, 'saldo');
                Helper::calculaSaldoLiquido($user->user_id);
            }


            if ($user && isset($user->gerente_id) && !is_null($user->gerente_id)) {
                $gerente = User::where('id', $user->gerente_id)->first();
                if ($gerente) {
                    $gerente_porcentagem = $gerente->gerente_percentage;

                    $valor = (float)$cashin->taxa_cash_in * (float)$gerente_porcentagem / 100;

                    Transactions::create([
                        'user_id' => $user->user_id,
                        'gerente_id' => $user->gerente_id,
                        'solicitacao_id' => $cashin->id,
                        'comission_value' => $valor,
                        'transaction_percent' => $cashin->taxa_cash_in,
                        'comission_percent' => $gerente_porcentagem,
                    ]);

                    Helper::calculaSaldoLiquido($gerente->user_id);
                }
            }

            if (!is_null($user->integracao_utmfy)) {
                $payload = [
                    'name' => $cashin['client_name'],
                    'email' => $cashin['client_email'],
                    'phone' => $cashin['client_telefone'],
                    'cpf' => $cashin['client_document'],
                    'value' => $cashin->amount
                ];

                $ip = $request->header('X-Forwarded-For') ?
                    $request->header('X-Forwarded-For') : ($request->header('CF-Connecting-IP') ?
                        $request->header('CF-Connecting-IP') :
                        $request->ip());
                $msg = "PIX Pago " . env('APP_NAME');
                UtmfyTrait::gerarUTM('PIX', 'paid', $cashin, $user->integracao_utmfy, $ip, $msg);
            }

            $order = CheckoutOrders::where('idTransaction', $transaction_id)->first();
            if ($order) {
                $order->update(['status' => 'pago']);
                if (!is_null($user->webhook_url) && in_array('pago', (array) $user->webhook_endpoint)) {
                    Http::withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
                        ->post($user->webhook_url, [
                            'nome' => $order->name,
                            'cpf' => preg_replace('/\D/', '', $order->cpf),
                            'telefone' => preg_replace('/\D/', '', $order->telefone),
                            'email' => $order->email,
                            'status' => 'pago'
                        ]);
                }
            }

            if ($cashin->callback) {
                $payload = [
                    "status"            => "paid",
                    "idTransaction"     => $cashin->idTransaction,
                    "typeTransaction"   => "PIX"
                ];

                Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'accept' => 'application/json'
                ])->post($cashin->callback, $payload);

                \Log::debug("[PIX-IN] Send Callback: Para $cashin->callback -> Enviando...");
                if ($cashin->callback && $cashin->callback != 'web') {
                    $payload = [
                        "status"            => "paid",
                        "idTransaction"     => $cashin->idTransaction,
                        "typeTransaction"   => "PIX"
                    ];

                    Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'accept' => 'application/json'
                    ])->post($cashin->callback, $payload);

                    return response()->json(['status' => true]);
                }
            }
        }
    }

    public function callbackCard(Request $request)
    {
        $data = $request->all();
        \Log::debug('[+][EFI][CALLBACK][CARD]: ' . json_encode($data));
    }
}