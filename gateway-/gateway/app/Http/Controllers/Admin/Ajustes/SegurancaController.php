<?php

namespace App\Http\Controllers\Admin\Ajustes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\App;

class SegurancaController extends Controller
{
    public function index()
    {
        $setting = App::first();
        return view("admin.ajustes.gerais", compact('setting'));
    }

    public function update(Request $request)
    {
        if ($request->input('gerente_percentage')) {
            $gerente_active = $request->has('gerente_active');
            App::first()->update([
                'gerente_percentage' => (float) str_replace(',', '.', $request->gerente_percentage),
                'gerente_active' => $gerente_active
            ]);
            return back()->with('success', 'Porcentagem alterada com sucesso.');
        }

        $data = $request->except(['_token', '_method', 'gateway_logo', 'gateway_favicon', 'gateway_banner_home']);
        $payload = [];

        foreach ($data as $key => $value) {
            $payload[$key] = (
                $key === 'gateway_name' ||
                $key === 'cnpj' ||
                $key === 'gateway_color'
            ) ? $value : (float) $value;
        }

        $payload["taxa_cash_in_padrao"]  = (float) str_replace(',', '.', $payload["taxa_cash_in_padrao"]);
        $payload["taxa_cash_out_padrao"]  = (float) str_replace(',', '.', $payload["taxa_cash_out_padrao"]);
        $payload["taxa_fixa_padrao"]  = (float) str_replace(',', '.', $payload["taxa_fixa_padrao"]);
        $payload["taxa_fixa_padrao_cash_out"]  = (float) str_replace(',', '.', $payload["taxa_fixa_padrao_cash_out"]);
        $payload["baseline"]  = (float) str_replace(',', '.', $payload["baseline"]);
        $payload["deposito_minimo"]  = (float) str_replace(',', '.', $payload["deposito_minimo"]);
        $payload["saque_minimo"]  = (float) str_replace(',', '.', $payload["saque_minimo"]);
        $payload["limite_saque_mensal"]  = (float) str_replace(',', '.', $payload["limite_saque_mensal"]);

        $imageFields = ['gateway_logo', 'gateway_favicon', 'gateway_banner_home'];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();

                // Caminho: public/uploads
                $destination = public_path('uploads');
                if (!file_exists($destination)) {
                    mkdir($destination, 0775, true);
                }

                $file->move($destination, $filename);

                // Caminho acessível via navegador
                $payload[$field] = '/uploads/' . $filename;
            } else {
                unset($payload[$field]); // Corrigido: $payload, não $data
            }
        }

        // Atualiza as configurações
        $setting = App::first();
        if ($setting) {
            $setting->update($payload);
        }

        return back()->with('success', 'Dados alterados com sucesso!');
    }
}
