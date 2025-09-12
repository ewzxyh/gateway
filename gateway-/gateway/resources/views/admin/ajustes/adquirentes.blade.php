<x-app-layout :route="'[ADMIN] Ajustes de adquirentes'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Start::page-header -->
            <div class="mb-3 row justify-content-between align-items-">
                <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-0 md-mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                    <h1 class="mb-0 display-5">Ajuste de adquirentes</h1>
                </div>
            </div>

            <!-- Start::row-0 -->
            <div class="row mb-3">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.adquirentes.default') }}" enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row gy-2">
                                    <div class="col-12">
                                        <label for="access_token" class="form-label">Adquirente padrão</label>
                                        <select class="form-control @error('access_token') is-invalid @enderror" name="adquirente" value="{{ $default }}" required>
                                            <option value="cashtime" {{ $default == 'cashtime' ? 'selected' : '' }}>Cashtime</option>
                                            <option value="efi" {{ $default == 'efi' ? 'selected' : '' }}>Efí</option>
                                            <option value="mercadopago" {{ $default == 'mercadopago' ? 'selected' : '' }}>Mercado Pago</option>
                                            <option value="pagarme" {{ $default == 'pagarme' ? 'selected' : '' }}>PagarMe</option>
                                            <option value="witetec" {{ $default == 'witetec' ? 'selected' : '' }}>Witetec</option>
                                            <option value="xgate" {{ $default == 'xgate' ? 'selected' : '' }}>XGate</option>
                                        </select>
                                        @error('access_token')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-12 text-end">
                                        <button type="submit" class="btn btn-primary">Alterar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Start::row-2 -->
            <div class="row mb-3">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="bg-transparent card-header justify-content-between">
                            <div class="card-title">
                                Cashtime
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.adquirentes.cashtime') }}" enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row gy-2">
                                    <div class="col-xl-4">
                                        <label for="secret" class="form-label">Chave Secreta</label>
                                        <input type="text" class="form-control @error('secret') is-invalid @enderror" name="secret" value="{{ $cashtime->secret }}" required>
                                        @error('secret')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="taxa_pix_cash_in" class="form-label">Taxa (PIX-IN)</label>
                                        <input type="number" step="0.01" class="form-control @error('taxa_pix_cash_in') is-invalid @enderror" name="taxa_pix_cash_in" value="{{ $cashtime->taxa_pix_cash_in }}" required>
                                        @error('taxa_pix_cash_in')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="taxa_pix_cash_out" class="form-label">Taxa (PIX-OUT)</label>
                                        <input type="number" step="0.01" class="form-control @error('taxa_pix_cash_out') is-invalid @enderror" name="taxa_pix_cash_out" value="{{ $cashtime->taxa_pix_cash_out }}" required>
                                        @error('taxa_pix_cash_in')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-12 text-end">
                                        <button type="submit" class="btn btn-primary">Alterar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Start::row-4 -->
            <div class="row mb-3">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="bg-transparent card-header justify-content-between">
                            <div class="card-title">
                                Mercado Pago
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.adquirentes.mercadopago') }}" enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row gy-2">
                                    <div class="col-12">
                                        <label for="access_token" class="form-label">Access Token</label>
                                        <input type="text" class="form-control @error('access_token') is-invalid @enderror" name="access_token" value="{{ $mercadopago->access_token }}" required>
                                        @error('access_token')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-12 text-end">
                                        <button type="submit" class="btn btn-primary">Alterar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="bg-transparent card-header justify-content-between">
                            <div class="card-title">
                                PagarMe
                                <br />
                                <small>Registrar o webhook no painel da pagar.me: <span class="text-warning">{{env('APP_URL')}}/pagarme/webhook</span></small>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.adquirentes.pagarme') }}" enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row gy-2">
                                    <div class="col-xl-4">
                                        <label for="secret" class="form-label">Chave Secreta</label>
                                        <input type="text" class="form-control @error('secret') is-invalid @enderror" name="secret" value="{{ $pagarme->secret ?? null }}" required>
                                        @error('secret')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="taxa_pix_cash_in" class="form-label">Taxa (PIX-IN)</label>
                                        <input type="number" step="0.01" class="form-control @error('taxa_pix_cash_in') is-invalid @enderror" name="taxa_pix_cash_in" value="{{ $pagarme->taxa_pix_cash_in ?? 0 }}" required>
                                        @error('taxa_pix_cash_in')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="taxa_pix_cash_out" class="form-label">Taxa (PIX-OUT)</label>
                                        <input type="number" step="0.01" class="form-control @error('taxa_pix_cash_out') is-invalid @enderror" name="taxa_pix_cash_out" value="{{ $pagarme->taxa_pix_cash_out ?? 0 }}" required>
                                        @error('taxa_pix_cash_in')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-12 text-end">
                                        <button type="submit" class="btn btn-primary">Alterar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="bg-transparent card-header justify-content-between">
                            <div class="card-title d-flex align-items-center justify-content-between">
                                <span>Efí</span>
                                <div>
                                    <form method="POST" action="{{ route('admin.adquirentes.efi.regitrar') }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-outline-primary">Registrar Webhooks</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.adquirentes.efi') }}" enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row gy-2">
                                    <div class="col-12 mb-3 mt-3 pb-1" style="border-bottom: 1px solid rgba(27, 27, 27, 0.47);">
                                        <h3 class="fs-5">Gerais&nbsp;<small class="text-warning">(Chaves)</small></h3>
                                    </div>
                                    <div class="col-xl-6">
                                        <label for="client_id" class="form-label">Client ID</label>
                                        <input type="text" class="form-control @error('client_id') is-invalid @enderror" name="client_id" value="{{ $efi->client_id }}">
                                        @error('client_id')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-6">
                                        <label for="client_secret" class="form-label">Client Secret</label>
                                        <input type="text" class="form-control @error('client_secret') is-invalid @enderror" name="client_secret" value="{{ $efi->client_secret }}">
                                        @error('client_secret')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-xl-6">
                                        <label for="chave_pix" class="form-label">Chave PIX</label>
                                        <input type="text" class="form-control @error('chave_pix') is-invalid @enderror" name="chave_pix" value="{{ $efi->chave_pix }}">
                                        @error('chave_pix')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-6">
                                        <label for="identificador_conta" class="form-label">Identificador de conta</label>
                                        <input type="text" class="form-control @error('identificador_conta') is-invalid @enderror" name="identificador_conta" value="{{ $efi->identificador_conta }}">
                                        @error('identificador_conta')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-12">
                                        <label for="cert" class="form-label">Certificado</label>
                                        <input
                                            id="input-cert"
                                            type="file"
                                            class="filepond form-control @error('cert') is-invalid @enderror"
                                            name="cert"
                                            hidden
                                            value="{{ $efi->cert }}">
                                        <br />
                                        <button id="bt-add-cert" type="button" class="w-100 btn btn-outline-primary" onclick="adcionarCertificado()">Selecionar certificado</button>
                                        <small style="display: none;" class="text-success">Certificado selecionado</small>
                                        @error('cert')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12 mb-3 mt-5 pb-1" style="border-bottom: 1px solid rgba(27, 27, 27, 0.47);">
                                        <h3 class="fs-5">Boleto&nbsp;<small class="text-warning">(Taxas e prazos)</small></h3>
                                    </div>

                                    <div class="col-xl-4">
                                        <label for="billet_tx_percent" class="form-label">Taxa (%)</label>
                                        <input type="text" class="form-control @error('billet_tx_percent') is-invalid @enderror" name="billet_tx_percent" value="{{ $efi->billet_tx_percent }}">
                                        @error('billet_tx_percent')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="billet_tx_fixed" class="form-label">Taxa fixa (R$)</label>
                                        <input type="text" class="form-control @error('billet_tx_fixed') is-invalid @enderror" name="billet_tx_fixed" value="{{ $efi->billet_tx_fixed }}">
                                        @error('billet_tx_fixed')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="billet_days_availability" class="form-label">Tempo de liberação</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">D+</span>
                                            <input type="number" min="1" class="form-control" placeholder="ex.: 21" name="billet_days_availability" id="billet_days_availability" value="{{ $efi->billet_days_availability }}" aria-label="Dias para liberar" aria-describedby="billet_days_availability">
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3 mt-5 pb-1" style="border-bottom: 1px solid rgba(27, 27, 27, 0.47);">
                                        <h3 class="fs-5">Cartão&nbsp;<small class="text-warning">(Taxas e prazos)</small></h3>
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="card_tx_percent" class="form-label">Taxa (%)</label>
                                        <input type="text" class="form-control @error('card_tx_percent') is-invalid @enderror" name="card_tx_percent" value="{{ $efi->card_tx_percent }}">
                                        @error('card_tx_percent')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="card_tx_fixed" class="form-label">Taxa fixa (R$)</label>
                                        <input type="text" class="form-control @error('card_tx_fixed') is-invalid @enderror" name="card_tx_fixed" value="{{ $efi->card_tx_fixed }}">
                                        @error('card_tx_fixed')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="card_days_availability" class="form-label">Tempo de liberação</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">D+</span>
                                            <input type="number" min="1" class="form-control" placeholder="ex.: 21" name="card_days_availability" id="card_days_availability" value="{{ $efi->card_days_availability }}" aria-label="Dias para liberar" aria-describedby="card_days_availability">
                                        </div>
                                    </div>


                                    <div class="col-xl-12 text-end">
                                        <button type="submit" class="btn btn-primary">Alterar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row mb-3">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="bg-transparent card-header justify-content-between">
                            <div class="card-title">
                                XGate
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.adquirentes.xgate') }}" enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row gy-2">
                                    <div class="col-xl-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $xgate->email ?? null }}" required>
                                        @error('email')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-6">
                                        <label for="password" class="form-label">Senha</label>
                                        <input type="text" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ $xgate->password ?? null }}" required>
                                        @error('password')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-12 text-end">
                                        <button type="submit" class="btn btn-primary">Alterar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="bg-transparent card-header justify-content-between">
                            <div class="card-title">
                                Witetec
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.adquirentes.witetec') }}" enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row gy-2">
                                    <div class="col-12">
                                        <label for="api_token" class="form-label">API Key</label>
                                        <input type="text" class="form-control @error('api_token') is-invalid @enderror" name="api_token" value="{{ $witetec->api_token ?? null }}" required>
                                        @error('api_token')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-12 text-end">
                                        <button type="submit" class="btn btn-primary">Alterar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function adcionarCertificado() {
            document.getElementById('input-cert').click();
        }
        document.getElementById('input-cert').addEventListener('change', function(ev) {
            ev.preventDefault();
            console.log(ev.target.value)
            document.getElementById('bt-add-cert').innerText = "Alterar Certificado";
            document.querySelector('#container-btn-cert small').style.display = 'block';
        })
        /* document.addEventListener('DOMContentLoaded', function() {
            const inputElement = document.querySelector('input[type="file"]');
            const pond = FilePond.create(inputElement, {
                labelIdle: `Arraste e solte aqui ou <span class="filepond--label-action py-5">clique aqui</span>`,
                allowImagePreview: false, // Desativa a pré-visualização
                allowMultiple: false,
                stylePanelAspectRatio: null, // Evita forçar proporção
            });
        }); */
    </script>
</x-app-layout>