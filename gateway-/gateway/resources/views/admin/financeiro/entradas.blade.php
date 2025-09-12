<x-app-layout :route="'[ADMIN] Saídas'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Start::page-header -->
            <div class="mb-3 row justify-content-between align-items-">
                <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-0 md-mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                    <h1 class="mb-0 display-5">Entradas</h1>
                </div>
            </div>
            <!-- Start:: row-1 -->
            <div class="row">
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ $totalaprovadas }}</div>
                                    <div class="card-text">Aprovadas (Total)</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ $totalaprovadasHoje }}</div>
                                    <div class="card-text">Aprovadas (Hoje)</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ $totalaprovadasMes }}</div>
                                    <div class="card-text">Aprovadas (Mês)</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ $totalsolicitacoes }}</div>
                                    <div class="card-text">Transações geral</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-sync"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End:: row-1 -->





            <!-- Start:: row-2 -->
            <div class="row">
                <div class="mb-4 col-xxl-4 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ "R$ ".number_format($valorAprovadoTotal, 2, ',', '.') }}</div>
                                    <div class="card-text">Aprovadas (Bruto)</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-4 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ "R$ ".number_format($valorAprovadoHoje, 2, ',', '.') }}</div>
                                    <div class="card-text">Aprovadas (Hoje)</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-4 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ "R$ ".number_format($valorAprovadoMes, 2, ',', '.') }}</div>
                                    <div class="card-text">Aprovadas (Mês)</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Start::row-2 -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table-financeiro-entradas" class="table text-nowrap">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">Meio</th>
                                            <th scope="col">User ID</th>
                                            <th scope="col">Transação ID</th>
                                            <th scope="col">Valor</th>
                                            <th scope="col">Valor Líquido</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Data</th>
                                            <th scope="col">Taxa</th>
                                            <th scope="col">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($cashOuts as $cashOut)
                                        <tr>
                                            <td>
                                                 @switch($cashOut->method)
                                                        @case('pix')
                                                            <i class="fa-brands fa-pix" style="color:rgb(0, 167, 130)"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="PIX"></i>
                                                        @break
                                                        @case('billet')
                                                            <i class="fa-solid fa-barcode" style="color:black"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Boleto"></i>
                                                        @break
                                                        @case('card')
                                                            <i class="fa-solid fa-credit-card" style="color:rgb(255, 154, 2)"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Cartão"></i>
                                                        @break
                                                    @endswitch
                                            </td>
                                            <td>{{ $cashOut->user_id }}</td>
                                            <td>{{ $cashOut->idTransaction }}</td>
                                            <td>{{ number_format($cashOut->amount, 2, ',', '.') }}</td>
                                            <td>{{ number_format($cashOut->deposito_liquido, 2, ',', '.') }}</td>
                                            <td>
                                                @switch($cashOut->status)
                                                @case('PAID_OUT')
                                                <span class="badge bg-success">Aprovado</span>
                                                @break
                                                @case('WAITING_FOR_APPROVAL')
                                                <span class="badge bg-warning">Pendente</span>
                                                @break
                                                 @case('RELEASE')
                                                <span class="badge badge-sm bg-info gateway-badge-info" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Será liberado em {{ \Carbon\Carbon::parse($cashOut->date)->addDays($cashOut->days_availability ?? 21)->format('d/m/Y \à\s H:i:s') }}" data-bs-placement="top" >A Liberar</span>
                                                @break
                                                @case('CANCELLED')
                                                <span class="badge bg-danger-transparent">Cancelado</span>
                                                @break
                                                @default
                                                <span class="badge">Desconhecido</span>
                                                @endswitch
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($cashOut->date)->format('d/m/Y \à\s H:i:s') }}</td>
                                            <td> R$ {{ number_format((float)$cashOut->amount - (float)$cashOut->deposito_liquido, '2', ',', '.') }}</td>
                                            <td>
                                                <select class="form-select form-select-sm status-select" data-id="{{ $cashOut->id }}" data-type="entrada">
                                                    <option value="PAID_OUT" {{ $cashOut->status == 'PAID_OUT' ? 'selected' : '' }}>Aprovado</option>
                                                    <option value="WAITING_FOR_APPROVAL" {{ $cashOut->status == 'WAITING_FOR_APPROVAL' ? 'selected' : '' }}>Pendente</option>
                                                    <option value="RELEASE" {{ $cashOut->status == 'RELEASE' ? 'selected' : '' }}>A Liberar</option>
                                                    <option value="CANCELLED" {{ $cashOut->status == 'CANCELLED' ? 'selected' : '' }}>Cancelado</option>
                                                </select>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9">Nenhum registro encontrado</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="dateFilterModal" tabindex="-1" role="dialog" aria-labelledby="dateFilterModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="shadow-lg modal-content">
                        <form method="GET" action="{{ route('admin.financeiro.entradas') }}">
                            <div class="modal-header">
                                <h5 class="modal-title" id="dateFilterModalLabel">Filtrar por Data</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="data_inicio">Data Início</label>
                                    <input type="date" class="form-control" name="data_inicio" id="data_inicio" value="{{ $dataInicio }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="data_fim">Data Fim</label>
                                    <input type="date" class="form-control" name="data_fim" id="data_fim" value="{{ $dataFim }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
        $("#table-financeiro-entradas").DataTable({
            responsive: true,
            info:false,
            ordering: false,
            lengthChange: false,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
            },
            dom: '<"top"f>rt<"bottom"p><"clear">',
                initComplete: function() {
                    // Muda o placeholder do input de busca
                    $('#table-financeiro-entradas_filter input[type="search"]').attr('placeholder', 'Pesquisar');
                }
        });

        // Handler para mudança de status
        $('.status-select').on('change', function() {
            const id = $(this).data('id');
            const type = $(this).data('type');
            const newStatus = $(this).val();
            const selectElement = $(this);
            
            // Confirmar mudança
            if (confirm('Tem certeza que deseja alterar o status desta transação?')) {
                $.ajax({
                    url: `/admin/financeiro/${type}/${id}/status`,
                    method: 'PUT',
                    data: {
                        status: newStatus,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Atualizar o badge de status na linha
                            const statusCell = selectElement.closest('tr').find('td:nth-child(6)');
                            let badgeClass = '';
                            let statusText = '';
                            
                            switch(newStatus) {
                                case 'PAID_OUT':
                                    badgeClass = 'bg-success';
                                    statusText = 'Aprovado';
                                    break;
                                case 'WAITING_FOR_APPROVAL':
                                    badgeClass = 'bg-warning';
                                    statusText = 'Pendente';
                                    break;
                                case 'RELEASE':
                                    badgeClass = 'badge-sm bg-info gateway-badge-info';
                                    statusText = 'A Liberar';
                                    break;
                                case 'CANCELLED':
                                    badgeClass = 'bg-danger-transparent';
                                    statusText = 'Cancelado';
                                    break;
                            }
                            
                            statusCell.html(`<span class="badge ${badgeClass}">${statusText}</span>`);
                            
                            // Mostrar mensagem de sucesso
                            alert('Status atualizado com sucesso!');
                        }
                    },
                    error: function(xhr) {
                        alert('Erro ao atualizar status: ' + xhr.responseJSON.message);
                        // Reverter o select para o valor anterior
                        location.reload();
                    }
                });
            } else {
                // Reverter o select para o valor anterior
                location.reload();
            }
        });
    });
    </script>

</x-app-layout>
