<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\PixKeyType;
use App\Traits\CashtimeTrait;
use App\Traits\EfiTrait;
use App\Traits\XgateTrait;
use App\Traits\WitetecTrait;
use App\Traits\MercadoPagoTrait;
use App\Models\User;
use App\Models\App;
use App\Helpers\Helper;
use App\Models\Adquirente;

class SaqueController extends Controller
{
    public function makePayment(Request $request)
    {
        Helper::calculaSaldoLiquido($request->user->user_id);
        $setting = App::first();
        if (!$setting) {
            return response()->json(['status' => 'error', 'message' => 'Configurações do aplicativo não encontradas.'], 500);
        }

        $adquirente = Adquirente::where('status', 1)->first();
        if (!$adquirente) {
            return response()->json(['status' => 'error', 'message' => 'Nenhum adquirente padrão configurado.'], 500);
        }
        $default = $adquirente->referencia;

        $user = User::where('id', $request->user->id)->first();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Usuário não encontrado.'], 404);
        }

        if ((float) $user->saldo < (float)$request->amount) {
            return response()->json(['status' => 'error', 'message' => 'Saldo Insuficiente.'], 401);
        }

        try {
            $validated = $request->validate([
                'token' =>    ['required', 'string'],
                'secret' =>    ['required', 'string'],
                'amount' =>    ['required'],
                'pixKey' => ['required', 'string'],
                'pixKeyType' =>    ['required', 'string', 'in:cpf,cnpj,email,telefone,aleatoria,crypto'],
                'baasPostbackUrl' =>    ['required', 'string']
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422); // Status code 422 para erros de validação
        }

        if ($request->amount < $setting->saque_minimo) {
            $saqueminimo = "R$ " . number_format($setting->saque_minimo, '2', ',', '.');
            return response()->json([
                'status' => 'error',
                'message' => "O saque mínimo é de $saqueminimo.",
            ], 401);
        }

       // dd($default);
        switch ($default) {
            case 'cashtime':
                $response = CashtimeTrait::requestPaymentCashtime($request);
                break;
            case 'mercadopago':
            case 'pagarme':
                $response = MercadoPagoTrait::requestPaymentCashtime($request);
                break;
            case 'efi':
                $response = EfiTrait::requestPaymentEfi($request);
                break;
            case 'xgate':
                $response = XgateTrait::requestPaymentXgate($request);
            break;
            case 'witetec':
                $response = WitetecTrait::requestPaymentWitetec($request);
            break;
        }

        // Se passar pela validação, processar o depósito
        return response()->json($response['data'], $response['status']);
    }
}
