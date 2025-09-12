<x-app-layout :route="'[ADMIN] Ajustes Gerais'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Start::page-header -->
            <div class="mb-3 row justify-content-between align-items-center">
                <div class="mb-0 col-12 col-md-4 d-flex justify-content-start align-items-center">
                    <h1 class="mb-0 display-5">Ajustes gerais</h1>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.ajustes.gerais') }}" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <!-- Card: Taxas -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card card-raised">
                            <div class="card-header">
                                <h5>Taxas</h5>
                            </div>
                            <div class="card-body">
                                <div class="row gy-3">
                                    <div class="col-md-6 col-lg-3">
                                        <label for="taxa_cash_in_padrao" class="form-label">Taxa Depósito (%)</label>
                                        <input type="text" class="form-control @error('taxa_cash_in_padrao') is-invalid @enderror" name="taxa_cash_in_padrao" value="{{ $setting->taxa_cash_in_padrao }}" required>
                                        @error('taxa_cash_in_padrao')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <label for="taxa_fixa_padrao" class="form-label">Taxa Depósito Fixa (R$)</label>
                                        <input type="text" class="form-control @error('taxa_fixa_padrao') is-invalid @enderror" name="taxa_fixa_padrao" value="{{ $setting->taxa_fixa_padrao }}" required>
                                        @error('taxa_fixa_padrao')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <label for="taxa_cash_out_padrao" class="form-label">Taxa Saque (%)</label>
                                        <input type="text" class="form-control @error('taxa_cash_out_padrao') is-invalid @enderror" name="taxa_cash_out_padrao" value="{{ $setting->taxa_cash_out_padrao }}" required>
                                        @error('taxa_cash_out_padrao')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <label for="taxa_fixa_padrao_cash_out" class="form-label">Taxa Saque Fixa (R$)</label>
                                        <input type="text" class="form-control @error('taxa_fixa_padrao_cash_out') is-invalid @enderror" name="taxa_fixa_padrao_cash_out" value="{{ $setting->taxa_fixa_padrao_cash_out }}" required>
                                        @error('taxa_fixa_padrao_cash_out')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <label for="baseline" class="form-label">Taxa Baseline (R$)</label>
                                        <input type="text" class="form-control @error('baseline') is-invalid @enderror" name="baseline" value="{{ $setting->baseline }}" required>
                                        @error('baseline')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <label for="deposito_minimo" class="form-label">Depósito Mínimo</label>
                                        <input type="text" class="form-control @error('deposito_minimo') is-invalid @enderror" name="deposito_minimo" value="{{ $setting->deposito_minimo }}" required>
                                        @error('deposito_minimo')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <label for="saque_minimo" class="form-label">Saque Mínimo</label>
                                        <input type="text" class="form-control @error('saque_minimo') is-invalid @enderror" name="saque_minimo" value="{{ $setting->saque_minimo }}" required>
                                        @error('saque_minimo')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <label for="limite_saque_mensal" class="form-label">Limite Mensal (P.F)</label>
                                        <input type="text" class="form-control @error('limite_saque_mensal') is-invalid @enderror" name="limite_saque_mensal" value="{{ $setting->limite_saque_mensal }}" required>
                                        @error('limite_saque_mensal')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Gateway -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card card-raised">
                            <div class="card-header">
                                <h5>Gerais</h5>
                            </div>
                            <div class="card-body">
                                <div class="row gy-3">
                                    <div class="col-md-6">
                                        <label for="gateway_name" class="form-label">Gateway Name</label>
                                        <input type="text" class="form-control @error('gateway_name') is-invalid @enderror" name="gateway_name" value="{{ $setting->gateway_name }}" required>
                                        @error('gateway_name')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="gateway_color" class="form-label">Cor padrão</label>
                                        <input type="color" class="form-control form-control-color @error('gateway_color') is-invalid @enderror" name="gateway_color" value="{{ $setting->gateway_color }}" required>
                                        @error('gateway_color')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="cnpj" class="form-label">CNPJ</label>
                                        <input type="text" class="form-control @error('cnpj') is-invalid @enderror" name="cnpj" value="{{ $setting->cnpj }}" required>
                                        @error('cnpj')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="contato" class="form-label">Contato (Gerente)</label>
                                        <input type="text" class="form-control @error('contato') is-invalid @enderror" name="contato" value="{{ $setting->contato }}" required>
                                        @error('contato')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <x-image-upload id="gateway_logo" name="gateway_logo" label="Logo" :value="asset($setting->gateway_logo)" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-image-upload id="gateway_favicon" name="gateway_favicon" label="Icone" :value="asset($setting->gateway_favicon)" />
                                    </div>
                                    <div class="col-md-12">
                                        <x-image-upload id="gateway_banner_home" name="gateway_banner_home" label="Banner Dashboard" :value="asset($setting->gateway_banner_home)" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card card-raised">
                                    <div class="card-body">
                                        <div class="row gy-3">
                                            <div class="col-12 text-end mt-4">
                                                <button type="submit" class="btn btn-primary">Alterar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </form>

        </div>
    </div>
</x-app-layout>