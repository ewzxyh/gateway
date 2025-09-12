@php
use App\Helpers\Helper;
Helper::calculaSaldoLiquido(auth()->user()->user_id);
$setting = Helper::getSetting();

auth()->user()->fresh();

@endphp
<x-app-layout :route="'Financeiro'">
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="mb-3 row justify-content-between align-items-">
                <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-3 md-mb-0 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                    <h1 class="mb-0 display-5">Financeiro</h1>
                </div>
            </div>

            <!-- Start::page-header -->
            <div class="flex-wrap gap-2 d-flex align-items-center justify-content-between page-header-breadcrumb">
            </div>
            <!-- End::page-header -->

            <!-- Start:: row-1 -->
            <div class="mb-3 row">
                <div class="mb-3 md-mb-0 col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-warning">R$ {{ number_format(auth()->user()->saldo + auth()->user()->valor_saque_pendente ?? 0, 2, ',', '.') }}</div>
                                    <div class="card-text">Disponível + Pendente</div>
                                </div>
                                <div class="text-white icon-circle bg-success card-color"><i class="material-icons">account_balance_wallet</i></div>
                            </div>
                            <div class="card-text">
                                <div class="d-inline-flex align-items-center ">
                                    <i class="text-md fa-brands fa-pix icon-xs text-warning"></i>&nbsp;
                                    <div class="text-md caption text-warning fw-500 me-2">Total</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">R$ {{ number_format(auth()->user()->saldo ?? 0, 2, ',', '.') }}</div>
                                    <div class="card-text">Disponível para saque</div>
                                </div>
                                <div class="text-white icon-circle bg-success card-color"><i class="material-icons">account_balance_wallet</i></div>
                            </div>
                            <div class="card-text">
                                <div class="d-inline-flex align-items-center ">
                                    <i class="text-md fa-brands fa-pix icon-xs text-success"></i>&nbsp;
                                    <div class="text-md caption text-success fw-500 me-2">Liberado</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Start::row-1 -->
            <div class="row">
                <div class="mb-3 col-xl-6 md-mb-0 ">
                    <div class="card card-raised">
                        <div class="p-0 card-body">
                            <div class="p-3 d-grid border-bottom border-block-end-dashed">
                                <button class="btn btn-primary d-flex align-items-center justify-content-center"
                                    data-bs-toggle="modal"
                                    data-bs-target="#addsaldo">
                                    <i class="align-middle ri-add-circle-line fs-16 me-1"></i> Adcionar Saldo
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="addsaldo" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="mail-ComposeLabel">Adcionar Saldo</h6>
                                                <button id="btnDepositar" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form id="depositForm" method="POST">
                                                @csrf
                                                <div class="px-4 modal-body">
                                                    <div class="row gy-2">

                                                        <!-- Campo de valor -->
                                                        <div class="col-xl-12">
                                                            <label for="valor" class="form-label">Valor</label>
                                                            <input type="number" step="0.01" class="form-control" id="valor_deposito" name="valor" placeholder="Valor" required>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                                                    <button id="btn-depositar" type="submit" class="btn btn-primary">Depositar</button>
                                                </div>
                                            </form>

                                            <div id="data-qrcode" class="mt-5 text-center" style="width:100%;display: none;">
                                                <img id="pix-qr-code" width="200" height="200" class="mb-3" />
                                                <input id="pix-copia-e-cola" style="background: transparent; width: 80%;" class="mb-3" readonly />
                                                <button class="mb-3 btn btn-primary" onclick="copiarTexto()">Copiar chave</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Explicação sobre taxas padrão -->
                            <div class="m-3 alert alert-info">
                                <ul>
                                    <li><strong>Taxa de depósito:</strong> {{ $setting->taxa_cash_in_padrao."%" }} {{ $setting->taxa_fixa_padrao > 0 ? '+ R$ '.number_format($setting->taxa_fixa_padrao, '2', ',', '.') : '' }}</li>
                                    <li><strong>Limite Pessoa física:</strong> Sem limite</li>
                                    <li><strong>Limite Pessoa jurídica:</strong> Sem limite</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card card-raised">
                        <div class="p-0 card-body">
                            <div class="p-3 d-grid border-bottom border-block-end-dashed">
                                <button class="btn btn-primary d-flex align-items-center justify-content-center"
                                    data-bs-toggle="modal"
                                    data-bs-target="{{ is_null($networks) ? '#saquepix' : '#modalSelMoeda' }}"
                                    data-saldo="{{ $saldoliquido }}">
                                    <i class="align-middle ri-add-circle-line fs-16 me-1"></i> Solicitar saque
                                </button>


                                <!-- Modal -->
                                <div class="modal fade" id="modalSelMoeda" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="modalSelMoedaLabel">Selecione a modalidade</h6>
                                                <button id="btn-c-mod" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="px-4 modal-body">
                                                <div class="row gy-2 d-flex align-items-center justify-content-center">
                                                    <div class="col-6 d-flex align-items-center justify-content-end">
                                                        <button
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#saquepix"
                                                            class="btn btn-outline-primary"
                                                            onclick="document.getElementById('btn-c-mod').click()"
                                                            style="display:flex;align-items:center;justify-content:center;width: 120px;height:120px">
                                                            PIX
                                                        </button>
                                                    </div>
                                                    <div class="col-6 d-flex align-items-center justify-content-start">
                                                        <button
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#saquecrypto"
                                                            class="btn btn-outline-primary"
                                                            onclick="document.getElementById('btn-c-mod').click()"
                                                            class="btn btn-outline-primary"
                                                            style="display:flex;align-items:center;justify-content:center;width: 120px;height:120px">
                                                            CRYPTO
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <!-- Modal PIX-->
                                <div class="modal fade" id="saquepix" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="mail-ComposeLabel">SAQUE PIX</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form id="saqueForm" method="POST">
                                                @csrf
                                                <div class="px-4 modal-body">
                                                    <div class="row gy-2">

                                                        <!-- Verificação de saldo baixo -->
                                                        @if($saldoBaixo)
                                                        <div class="mt-4 alert alert-danger">
                                                            <strong>Saldo muito baixo para realizar um saque.</strong>
                                                        </div>
                                                        @endif

                                                        @if($retiradasPendentes)
                                                        <div class="mt-4 alert alert-warning">
                                                            <strong>Já existe um saque em processamento. Aguarde a conclusão.</strong>
                                                        </div>
                                                        @endif

                                                        <!-- Exibição do saldo disponível -->
                                                        <div class="mt-4 alert alert-info">
                                                            <ul>
                                                                <li><strong>DISPONÍVEL PARA SAQUE:</strong> R$: {{ number_format(auth()->user()->saldo, 2, ',', '.') }}</li>
                                                            </ul>
                                                        </div>

                                                        <!-- Campo de valor -->
                                                        <div class="col-xl-12">
                                                            <label for="valor" class="form-label">Valor</label>
                                                            <input type="number" step="0.01" class="form-control"
                                                                id="valor"
                                                                max="{{ auth()->user()->saldo }}"
                                                                name="valor"
                                                                placeholder="Valor"
                                                                required>
                                                            <!-- <div id="valorLiquido" class="mt-2 text-success"></div> -->
                                                            <div id="containerValorLiquido" style="display: none;" class="mt-4 alert alert-success">
                                                                <ul>
                                                                    <li><strong id="valorLiquido"></strong></li>
                                                                </ul>
                                                            </div>
                                                            <div id="valorError" class="mt-2 text-danger" style="display: none;">Saldo insuficiente para o valor solicitado.</div>
                                                        </div>

                                                        <div class="col-xl-12">
                                                            <label class="form-label">Tipo de Chave</label>
                                                            <select id="tipo_chave" name="tipo_chave" type="text" class="form-control">
                                                                <option value="cpf">CPF</option>
                                                                <option value="cnpj">CNPJ</option>
                                                                <option value="email">EMAIL</option>
                                                                <option value="telefone">TELEFONE</option>
                                                                <option value="aleatoria">ALEATÓRIA</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-xl-12">
                                                            <label for="chave" class="form-label">Chave PIX:</label>
                                                            <input type="text" class="form-control" id="chave" name="chave" placeholder="Chave" required>
                                                        </div>

                                                        <!-- Campo oculto para o ID do usuário -->
                                                        <input type="hidden" id="user_id" name="user_id" value="{{ $email }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button
                                                        type="button"
                                                        class="btn btn-light"
                                                        data-bs-dismiss="modal"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalSelMoeda">Voltar</button>
                                                    <button id="btnSolicitarSaque" type="submit" class="btn btn-primary" {{ $retiradasPendentes >= 1 ? 'disabled' : '' }}>Solicitar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                                <!-- Modal Crypto-->
                                <div class="modal fade" id="saquecrypto" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-md modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="mail-ComposeLabel">SAQUE CRYPTO</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form id="saqueFormCrypto" method="POST">
                                                @csrf
                                                <div class="px-4 modal-body">
                                                    <div class="row gy-2">

                                                        <!-- Verificação de saldo baixo -->
                                                        @if($saldoBaixo)
                                                        <div class="mt-4 alert alert-danger">
                                                            <strong>Saldo muito baixo para realizar um saque.</strong>
                                                        </div>
                                                        @endif

                                                        @if($retiradasPendentes)
                                                        <div class="mt-4 alert alert-warning">
                                                            <strong>Já existe um saque em processamento. Aguarde a conclusão.</strong>
                                                        </div>
                                                        @endif

                                                        <!-- Exibição do saldo disponível -->
                                                        <div class="mt-4 alert alert-info">
                                                            <ul>
                                                                <li><strong>DISPONÍVEL PARA SAQUE:</strong> R$: {{ number_format(auth()->user()->saldo, 2, ',', '.') }}</li>
                                                            </ul>
                                                        </div>

                                                        <!-- Campo de valor -->
                                                        <div class="col-xl-12">
                                                            <label for="valor" class="form-label">Valor</label>
                                                            <input
                                                                class="form-control"
                                                                type="number"
                                                                step="0.01"
                                                                id="valor_saque"
                                                                max="{{ auth()->user()->saldo }}"
                                                                name="valor_saque"
                                                                placeholder="Valor"
                                                                required>
                                                            <!-- <div id="valorLiquido" class="mt-2 text-success"></div> -->
                                                            <div id="containerValorLiquido" style="display: none;" class="mt-4 alert alert-success">
                                                                <ul>
                                                                    <li><strong id="valorLiquido"></strong></li>
                                                                </ul>
                                                            </div>
                                                            <div id="valorError" class="mt-2 text-danger" style="display: none;">Saldo insuficiente para o valor solicitado.</div>
                                                        </div>
                                                        @if(!is_null($networks))
                                                        <div class="multistep">

                                                            {{-- STEP 1: Escolha da Network --}}
                                                            <div id="step-networks" class="row g-3">
                                                                @foreach($networks as $network)
                                                                <div class="col-md-3">
                                                                    <div class="card card-network text-center h-100"
                                                                        data-id="{{ $network['_id'] }}"
                                                                        data-data="{{ json_encode($network) }}">
                                                                        <div class="card-body">
                                                                            <h6 class="fw-bold">{{ $network['name'] }}</h6>
                                                                            <p class="text-muted m-0">{{ $network['chain'] }} • {{ $network['symbol'] }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            </div>

                                                            {{-- STEP 2: Escolha da Moeda --}}
                                                            <div id="step-cryptos" class="d-none">
                                                                <h6 class="fw-semibold mb-3">Escolha a moeda:</h6>
                                                                <ul class="list-group" id="crypto-list"></ul>
                                                                <div class="mt-3">
                                                                    <button type="button" class="btn btn-outline-secondary" id="btn-back" sele>Voltar</button>
                                                                </div>
                                                            </div>

                                                            {{-- STEP 3: Endereço + Solicitar --}}
                                                            <div id="step-final" class="d-none">
                                                                <div class="mb-3">
                                                                    <label for="wallet" class="form-label">Endereço da carteira</label>
                                                                    <input type="text" class="form-control" id="wallet" placeholder="Cole seu endereço aqui">
                                                                </div>
                                                                <input id="tipo_chave" name="tipo_chave" type="text" class="form-control" value="crypto" hidden />
                                                                <input type="hidden" id="user_id" name="user_id" value="{{ $email }}">

                                                                <div class="d-flex justify-content-between">
                                                                    <button type="button" class="btn btn-outline-secondary" id="btn-back-crypto">Voltar</button>
                                                                    <button type="submit" class="btn btn-success" onclick="this.disabled;">Solicitar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Explicação sobre taxas padrão -->
                            <div class="m-3 alert alert-info">
                                <ul>
                                    <li><strong>Taxa de saque:</strong> {{ $setting->taxa_cash_out_padrao."%" }} {{ $setting->taxa_fixa_padrao_cashout > 0 ? '+ R$ '.number_format($setting->taxa_fixa_padrao_cashout, '2', ',', '.') : '' }}</li>
                                    @if(isset($setting->limite_saque_mensal) && (float)$setting->limite_saque_mensal > 0)
                                    <li><strong>Limite Pessoa física:</strong> R$ {{ number_format($setting->limite_saque_mensal, '2', ',', '.') }} /mês</li>
                                    @else
                                    <li><strong>Limite Pessoa física:</strong> Sem limite</li>
                                    @endif
                                    <li><strong>Limite Pessoa jurídica:</strong> Sem limite</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End::row-1 -->
        </div>
    </div>

    <script>
        window.selectedNetwork = null;
        window.selectedCrypto = null;
        document.addEventListener("DOMContentLoaded", function() {
            let selectedNetwork = null;
            let selectedCrypto = null;
            const stepNetworks = document.getElementById("step-networks");
            const stepCryptos = document.getElementById("step-cryptos");
            const stepFinal = document.getElementById("step-final");
            const cryptoList = document.getElementById("crypto-list");

            // Seleção de network
            document.querySelectorAll(".card-network").forEach(card => {
                card.addEventListener("click", function() {
                    // Resetar seleção
                    document.querySelectorAll(".card-network").forEach(c => c.classList.remove("active"));
                    this.classList.add("active");

                    selectedNetwork = this.dataset.id;
                
                    // Carregar as cryptos dinamicamente
                    cryptoList.innerHTML = "";
                    let networkData = @json($networks);
                    let selNet = networkData.find(n => n._id === selectedNetwork);
                    let cryptos = selNet.cryptocurrencies;
                    window.selectedNetwork = selNet;

                    cryptos.forEach(c => {
                        let li = document.createElement("li");
                        li.className = "list-group-item list-group-item-action crypto-item";
                        li.dataset.crypto = c.cryptocurrency._id;
                        li.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <span><strong>${c.cryptocurrency.name}</strong> <small>(${c.cryptocurrency.symbol})</small></span>
                            <span class="badge bg-light text-dark border">Min: ${c.minWithdraw}</span>
                        </div>`;
                        cryptoList.appendChild(li);

                        li.addEventListener("click", function() {
                            document.querySelectorAll(".crypto-item").forEach(el => el.classList.remove("active"));
                            this.classList.add("active");
                            selectedCrypto = this.dataset.crypto;
                            window.selectedCrypto = c;

                            // Avança para step final
                            stepCryptos.classList.add("d-none");
                            stepFinal.classList.remove("d-none");
                        });
                    });

                    // Mostrar Step 2
                    stepNetworks.classList.add("d-none");
                    stepCryptos.classList.remove("d-none");
                });
            });

            // Botão Voltar
            document.getElementById("btn-back").addEventListener("click", () => {
                stepCryptos.classList.add("d-none");
                stepNetworks.classList.remove("d-none");
                selectedNetwork = null;
            });

            document.getElementById("btn-back-crypto").addEventListener("click", () => {
                stepFinal.classList.add("d-none");
                stepCryptos.classList.remove("d-none");
                selectedCrypto = null;
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let selectedNetwork = null;
            let selectedCrypto = null;

            // Seleção de network
            document.querySelectorAll(".btn-select-network").forEach(btn => {
                btn.addEventListener("click", function() {
                    let id = this.dataset.id;

                    // Resetar todos os cards
                    document.querySelectorAll(".card-select").forEach(card => {
                        card.classList.remove("border-primary", "shadow");
                    });

                    // Marcar card selecionado
                    let selectedCard = this.closest(".card-select");
                    selectedCard.classList.add("border-primary", "shadow");

                    // Esconder todas as listas
                    document.querySelectorAll(".crypto-wrapper").forEach(list => {
                        list.classList.add("d-none");
                    });

                    // Mostrar apenas a lista da network escolhida
                    document.getElementById("crypto-list-" + id).classList.remove("d-none");

                    selectedNetwork = id;
                    selectedCrypto = null;
                    console.log("Network selecionada:", selectedNetwork);
                });
            });

            // Seleção de crypto
            document.querySelectorAll(".crypto-item").forEach(item => {
                item.addEventListener("click", function() {
                    let networkId = this.dataset.network;

                    // Resetar todos os itens da lista dessa network
                    document.querySelectorAll("#crypto-list-" + networkId + " .crypto-item")
                        .forEach(el => el.classList.remove("active"));

                    // Marcar item selecionado
                    this.classList.add("active");

                    selectedCrypto = this.dataset.crypto;
                    console.log("Crypto selecionada:", selectedCrypto);
                });
            });
        });
    </script>

    <script>
        function copiarTexto() {
            var input = document.getElementById("pix-copia-e-cola");
            input.select();
            document.execCommand("copy");
            showToast("success", "Chave Pix copiada!");
        }
    </script>

    <script>
        document.getElementById('depositForm').addEventListener('submit', function(event) {
            event.preventDefault();
            let btnDepositar = document.getElementById('btn-depositar');
            btnDepositar.setAttribute('disabled', true);
            var paymentCode;
            var transactionId;
            generateQRCode();
            async function generateQRCode() {
                var name = "{{ auth()->user()->name }}";
                var cpf = "{{ auth()->user()->cpf_cnpj }}";
                var email = "{{ auth()->user()->email }}";
                var amount = document.getElementById('valor_deposito').value;
                var apiUrl = "{{ env('APP_URL') }}/api/wallet/deposit/payment";
                var token = "{{ auth()->user()->chaves->token }}";
                var secret = "{{ auth()->user()->chaves->secret }}";
                var phone = "{{ auth()->user()->telefone }}";
                var payload = {
                    "token": token,
                    "secret": secret,
                    "amount": parseFloat(amount),
                    "debtor_name": name,
                    "email": email,
                    "debtor_document_number": cpf,
                    "phone": phone,
                    "method_pay": "pix",
                    "postback": "web"
                };
                try {
                    const response = await fetch(apiUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await response.json();
                    console.log('resposta', data);
                    if (data.qrcode) {
                        paymentCode = data.qrcode;
                        paymentCodeBase64 = data.qr_code_image_url;
                        transactionId = data.idTransaction; // Ajustado para pegar idTransaction



                        // Adiciona o paymentCode ao texto da div
                        document.getElementById('pix-qr-code').src = paymentCodeBase64;
                        document.getElementById('pix-copia-e-cola').value = paymentCode;

                        document.getElementById('depositForm').style.display = 'none';
                        let pixcontainer = document.getElementById('data-qrcode');
                        pixcontainer.style.display = 'flex';
                        pixcontainer.style.flexDirection = "column";
                        pixcontainer.style.alignItems = "center";
                        pixcontainer.style.justifyContent = "center";
                        pixcontainer.style.gap = 5;

                        // Inicia a verificação do pagamento a cada 2 segundos
                        setInterval(checkPaymentStatus, 5000);
                    } else {
                        btnDepositar.setAttribute('disabled', false);
                        console.error("Erro na solicitação:", data.message);
                    }
                } catch (error) {
                    btnDepositar.setAttribute('disabled', false);
                    console.error("Erro na solicitação:", error);
                }
            }

            async function checkPaymentStatus() {
                var apiUrl = "{{env('APP_URL')}}/api/status";
                var payload = {
                    "idTransaction": transactionId
                };

                try {
                    const response = await fetch(apiUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await response.json();

                    if (data.status === "PAID_OUT") {
                        clearInterval(checkPaymentStatus); // Para a verificação quando o pagamento for confirmado

                        showToast('success', "Saldo adcionado com sucesso!")
                        setTimeout(() => {
                            window.location.reload();
                        }, 3000)
                    } else if (data.status === "WAITING_FOR_APPROVAL") {
                        console.log("Aguardando aprovação...");
                    }
                } catch (error) {
                    console.error("Erro na verificação do pagamento:", error);
                }
            }
        })
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let btnSolicitarSaque = document.getElementById("btnSolicitarSaque");
            let inputValor = document.getElementById("valor");
            let inputChave = document.getElementById("chave");
            let valorLiquidoInput = document.getElementById("valorLiquido");
            let containerValorLiquido = document.getElementById("containerValorLiquido");

            // Desabilita o botão inicialmente
            btnSolicitarSaque.setAttribute("disabled", true);

            function validarCampos() {
                let valorPreenchido = inputValor.value && parseFloat(inputValor.value) > 0;
                let chavePreenchida = inputChave.value.trim().length > 0;

                if (valorPreenchido && chavePreenchida) {
                    btnSolicitarSaque.removeAttribute("disabled");
                } else {
                    btnSolicitarSaque.setAttribute("disabled", true);
                }
            }

            function calcularValorLiquido() {
                let maxValue = parseFloat(inputValor.max) || 0;
                let currentValue = parseFloat(inputValor.value) || 0;

                if (currentValue > maxValue) {
                    inputValor.value = maxValue;
                    currentValue = maxValue;
                }

                if (currentValue <= 0 || isNaN(currentValue)) {
                    containerValorLiquido.style.display = "none";
                } else {
                    containerValorLiquido.style.display = "block";
                }

                let tx_cash_out = parseFloat("{{ $setting->taxa_cash_out_padrao }}") || 0;
                let taxa_fixa_padrao = parseFloat("{{ $taxa_fixa_padrao }}") || 0;
                let valorLiquido = currentValue - taxa_fixa_padrao - (currentValue * tx_cash_out / 100);

                valorLiquidoInput.innerText = "Valor líquido a receber: " +
                    valorLiquido.toLocaleString("pt-BR", {
                        style: "currency",
                        currency: "BRL"
                    });
            }

            inputValor.addEventListener("input", function() {
                calcularValorLiquido();
                validarCampos();
            });

            inputChave.addEventListener("input", validarCampos);
        });
    </script>

    <script>
        document.getElementById('saqueForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var saldo = "{{ $saldoliquido }}"; // Corrigido para usar PHP para obter o saldo
            var valor = parseFloat(document.getElementById('valor').value);
            var valorError = document.getElementById('valorError');

            // Verifica se o saldo é zero ou se o valor solicitado é maior que o saldo
            if (saldo <= 0) {
                showToast('warning', "Saldo insuficiente!")
                event.preventDefault(); // Evita o envio do formulário
            } else if (valor > saldo) {
                showToast('success', "Saldo insuficiente!")
                event.preventDefault(); // Evita o envio do formulário
            }


            requestPayment();
            async function requestPayment() {
                var token = "{{ auth()->user()->chaves->token }}";
                var secret = "{{ auth()->user()->chaves->secret }}";
                var amount = document.getElementById('valor').value;
                var pixKey = document.getElementById('chave').value;
                var pixKeyType = document.getElementById('tipo_chave').value;
                var apiUrl = "{{env('APP_URL')}}/api/pixout";

                if (parseFloat(valor) > parseFloat(saldo)) {
                    valor = saldo;
                }

                var payload = {
                    token,
                    secret,
                    amount,
                    pixKey,
                    pixKeyType,
                    baasPostbackUrl: 'web'
                }

                const response = await fetch(apiUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (data.id) {
                    showToast('success', "Saque solicitado com sucesso.")
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000)
                } else {
                    showToast('warning', data.message);
                }
            }



        });




        document.getElementById('saqueFormCrypto').addEventListener('submit', function(event) {
            event.preventDefault();
            var saldo = "{{ $saldoliquido }}"; // Corrigido para usar PHP para obter o saldo
            var valor = parseFloat(document.getElementById('valor').value);
            var valorError = document.getElementById('valorError');

            // Verifica se o saldo é zero ou se o valor solicitado é maior que o saldo
            if (saldo <= 0) {
                showToast('warning', "Saldo insuficiente!")
                event.preventDefault(); // Evita o envio do formulário
            } else if (valor > saldo) {
                showToast('success', "Saldo insuficiente!")
                event.preventDefault(); // Evita o envio do formulário
            }


            requestPaymentCrypto();
            async function requestPaymentCrypto() {
                var token = "{{ auth()->user()->chaves->token }}";
                var secret = "{{ auth()->user()->chaves->secret }}";
                var amount = document.getElementById('valor_saque').value;
                var pixKey = document.getElementById('wallet').value;
                var pixKeyType = "crypto";
                var apiUrl = "{{env('APP_URL')}}/api/pixout";

                if (parseFloat(valor) > parseFloat(saldo)) {
                    valor = saldo;
                }

                let blockchainNetwork = window.selectedNetwork;
                blockchainNetwork['cryptocurrencies'] = [];
                let cryptocurrency = window.selectedCrypto.cryptocurrency;

                var payload = {
                    token,
                    secret,
                    amount,
                    pixKey,
                    pixKeyType,
                    baasPostbackUrl: 'web',
                    blockchainNetwork,
                    cryptocurrency,
                }

                console.log(payload)
                const response = await fetch(apiUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (data.id) {
                    showToast('success', "Saque solicitado com sucesso.")
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000)
                } else {
                    showToast('warning', data.message);
                }
            }



        });
    </script>

    <style>
        .card-network {
            cursor: pointer;
            border: 2px solid transparent;
            transition: 0.3s;
        }

        .card-network:hover {
            border-color: #dee2e6;
        }

        .card-network.active {
            border-color: #198754;
            /* Verde Bootstrap */
            box-shadow: 0 0 10px rgba(25, 135, 84, 0.3);
        }

        .crypto-item.active {
            background-color: #198754;
            color: #fff;
            font-weight: bold;
        }
    </style>
</x-app-layout>