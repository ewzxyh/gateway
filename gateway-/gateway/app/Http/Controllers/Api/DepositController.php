<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Adquirente;
use Illuminate\Http\Request;
use App\Traits\CashtimeTrait;
use App\Traits\MercadoPagoTrait;
use App\Traits\EfiTrait;
use App\Traits\PagarMeTrait;
use App\Traits\XgateTrait;
use App\Traits\WitetecTrait;
use App\Models\Solicitacoes;
use App\Models\App;

/**
 * @OA\Info(
 *     title="API Rest PIX",
 *     version="1.0.0",
 *     description="Documentação"
 * )
 */

class DepositController extends Controller
{

    public function makeDeposit(Request $request)
    {
        $setting = App::first();
        if (!$setting) {
            return response()->json(['status' => 'error', 'message' => 'Configurações do aplicativo não encontradas.'], 500);
        }

        $adquirente = Adquirente::where('status', 1)->first();
        if (!$adquirente) {
            return response()->json(['status' => 'error', 'message' => 'Nenhum adquirente padrão configurado.'], 500);
        }
        $default = $adquirente->referencia;

        if ($setting->deposito_minimo > 0 && $request->amount < $setting->deposito_minimo) {
            $valorret = number_format($setting->deposito_minimo, '2', ',', '.');
            return response()->json([
                'status' => 'error',
                'message' => "O valor mínimo de depósito é de R$ $valorret."
            ], 401);
        }
        try {
            $validated = $request->validate([
                'token' => ['required', 'string'],
                'secret' => ['required', 'string'],
                'amount' => ['required'],
                'debtor_name' => ['required', 'string'],
                'email' => ['required', 'string', 'email'],
                'debtor_document_number' => ['required', 'string'],
                'phone' => ['required', 'string'],
                'method_pay' => ['required', 'string'],
                'postback' => ['required', 'string'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422); // Status code 422 para erros de validação
        }

        switch($default){
            case 'cashtime':
                $response = CashtimeTrait::requestDepositCashtime($request);
            break;
            case 'mercadopago':
                $response = MercadoPagoTrait::requestDepositMercadoPago($request);
            break;
            case 'efi':
                $response = EfiTrait::requestDepositEfi($request);
            break;
            case 'pagarme':
                $response = PagarMeTrait::requestDepositPagarme($request);
            break;
            case 'xgate':
                $response = XgateTrait::requestDepositXgate($request);
                break;
            case 'witetec':
                $response = WitetecTrait::requestDepositWitetec($request);
            break;

        }
        // Se passar pela validação, processar o depósito
        return response()->json($response['data'], $response['status']);
    }

    public function statusDeposito(Request $request)
    {
        $deposit = Solicitacoes::where('idTransaction', $request->idTransaction)->first();
        if (!$deposit) {
            return response()->json(['status' => 'NOT_FOUND']);
        }
        return response()->json(['status' => $deposit->status]);
    }
}
