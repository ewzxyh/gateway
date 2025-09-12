<?php

namespace App\Http\Controllers\Admin\Ajustes;

use App\Http\Controllers\Controller;
use App\Models\AdMercadopago;
use App\Models\Adquirente;
use Illuminate\Http\Request;
use App\Models\Cashtime;
use App\Models\App;
use App\Models\Efi;
use App\Models\Pagarme;
use App\Models\Witetec;
use App\Models\Xgate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use App\Traits\EfiTrait;

class AdquirentesController extends Controller
{
    public function index()
    {
        $cashtime = Cashtime::first();
        $mercadopago = AdMercadopago::first();
        $pagarme = Pagarme::first();
        $efi = Efi::first();
        $xgate = Xgate::first();
        $witetec = Witetec::first();
        $settings = App::first();
        $adquirente_default = Adquirente::where('status', 1)->first();
        $default = $adquirente_default ? $adquirente_default->referencia : null;

        return view("admin.ajustes.adquirentes", compact(
            'efi',
            'xgate',
            'witetec',
            'cashtime',
            'mercadopago',
            'pagarme',
            'settings',
            'default'
        ));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);
        $payload = [];
        foreach ($data as $key => $value) {
            if ($key == 'secret') {
                $payload[$key] = $value;
            } else {
                $payload[$key] = (float) $value;
            }
        }
        //dd($request->all());
        $setting = Cashtime::first()->update($payload);

        return back()->with('success', 'Dados alterados com sucesso!');

        // Retornar uma resposta de sucesso
        return response('success');
    }

    public function updateEfi(Request $request)
    {
        $data = $request->except(['_token', '_method']);
        //dd($data);
        //$payload = [];
        $data['billet_tx_fixed'] = (float) str_replace(',', '.', $data['billet_tx_fixed']) ?? 0;
        $data['billet_tx_percent'] = (float) str_replace(',', '.', $data['billet_tx_percent']) ?? 0;
        $data['billet_days_availability'] = (int) $data['billet_days_availability'] ?? 0;

        $data['card_tx_fixed'] = (float) str_replace(',', '.', $data['card_tx_fixed']) ?? 0;
        $data['card_tx_percent'] = (float) str_replace(',', '.', $data['card_tx_percent']) ?? 0;
        $data['card_days_availability'] = (int) $data['card_days_availability'] ?? 0;
       
        if ($request->hasFile('cert') && $request->file('cert')->isValid()) {
            $certificado = $request->file('cert');
            $data['cert'] = "Certificado adcionado";
            // Armazena como 'producao.pem'
            Storage::disk('certificados')->put('producao.p12', file_get_contents($certificado));
            $certPath = storage_path('app/private/certificados/producao.p12');
            $pemPath = storage_path('app/private/certificados/producao.pem');
            $process = new Process([
                'openssl',
                'pkcs12',
                '-in',
                $certPath,
                '-out',
                $pemPath,
                '-nodes',
                '-password',
                'pass:'
            ]);
            $process->run();

            if ($process->isSuccessful()) {
                \Log::debug("Certificado convertido com sucesso.");
            } else {
                \Log::error('Erro OpenSSL: ' . $process->getErrorOutput());
            }
        }


        Efi::first()->update($data);

        return back()->with('success', 'Dados alterados com sucesso!');
    }

    public function updateMercadopago(Request $request)
    {
        AdMercadopago::first()->update([
            'access_token' => $request->input('access_token')
        ]);

        return back()->with('success', 'Dados alterados com sucesso!');

        // Retornar uma resposta de sucesso
        return response('success');
    }

    public function updatePagarme(Request $request)
    {
        $data = [];

        $data['secret'] = $request->input('secret');
        $data['taxa_pix_cash_in'] = (float) str_replace(',','.',$request->input('taxa_pix_cash_in'));
        $data['taxa_pix_cash_out'] = (float) str_replace(',','.',$request->input('taxa_pix_cash_out'));

        Pagarme::first()->update($data);

        return back()->with('success', 'Dados alterados com sucesso!');

        // Retornar uma resposta de sucesso
        return response('success');
    }

    public function updateXgate(Request $request)
    {
        $data = [];

        $data['email'] = $request->input('email');
        $data['password'] = $request->input('password');
        
        Xgate::first()->update($data);

        return back()->with('success', 'Dados alterados com sucesso!');

        // Retornar uma resposta de sucesso
        return response('success');
    }

    public function updateWitetec(Request $request)
    {
        $data = [];

        $data['api_token'] = $request->input('api_token');
        
        Witetec::first()->update($data);

        return back()->with('success', 'Dados alterados com sucesso!');

        // Retornar uma resposta de sucesso
        return response('success');
    }


    public function adquirenteDefault(Request $request)
    {
        $default = $request->input('adquirente');
        //dd($default);
        Adquirente::query()->update(['status' => 0]);
        Adquirente::where('referencia', $default)->first()->update(['status' => 1]);

        return back()->with('success', 'Dados alterados com sucesso!');
    }

    public function efiRegistrarWebhook(Request $request)
    {
        EfiTrait::cadastrarWebhook();
        return back()->with('success', 'Webhooks Efí atualizados com sucesso!');
    }
}
