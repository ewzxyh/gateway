<x-app-layout :route="'[ADMIN] Saídas'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Start::page-header -->
            <div class="mb-3 row justify-content-between align-items-">
                <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-0 md-mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                    <h1 class="mb-0 display-5">Saídas</h1>
                </div>
            </div>
            <!-- Start:: row-1 -->
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
                                <table id="table-financeiro-saidas" class="table text-nowrap">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">User ID</th>
                                            <th scope="col">Transação ID</th>
                                            <th scope="col">Valor</th>
                                            <th scope="col">Valor Líquido</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Nome</th>
                                            <th scope="col">Chave PIX</th>
                                            <th scope="col">Tipo de Chave</th>
                                            <th scope="col">Data</th>
                                            <th scope="col">Taxa </th>
                                            <th scope="col">Resposta da adquirência</th>
                                            <th scope="col">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($cashOuts as $cashOut)
                                        <tr>
                                            <td>{{ $cashOut->user_id }}</td>
                                            <td>{{ $cashOut->externalreference }}</td>
                                            <td>{{ number_format($cashOut->cash_out_liquido, 2, ',', '.') }}</td>
                                            <td>{{ number_format($cashOut->amount, 2, ',', '.') }}</td>
                                            <td>
                                                @switch($cashOut->status)
                                                @case('COMPLETED')
                                                <span class="badge bg-success">Aprovado</span>
                                                @break
                                                @case('PENDING')
                                                <span class="badge bg-warning">Pendente</span>
                                                @break
                                                @case('CANCELLED')
                                                @case('CANCELED')
                                                <span class="badge bg-danger">Cancelado</span>
                                                @break
                                                @default
                                                <span class="badge">Desconhecido</span>
                                                @endswitch
                                            </td>
                                            <td>{{ $cashOut->beneficiaryname }}</td>
                                            <td>{{ $cashOut->beneficiarydocument }}</td>
                                            <td>{{ $cashOut->pixkey }}</td>
                                            <td>{{ \Carbon\Carbon::parse($cashOut->date)->format('d/m/Y \à\s H:i:s') }}</td>
                                            <td> R$ {{ number_format((float)$cashOut->cash_out_liquido - (float)$cashOut->amount, '2', ',', '.') }}</td>
                                      		<td style="white-space: pre-wrap; word-wrap: break-word;">
                                                {!! nl2br(e($cashOut->descricao_externa)) !!}
                                            </td>
                                            <td>
                                                <select class="form-select form-select-sm status-select" data-id="{{ $cashOut->id }}" data-type="saida">
                                                    <option value="COMPLETED" {{ $cashOut->status == 'COMPLETED' ? 'selected' : '' }}>Aprovado</option>
                                                    <option value="PENDING" {{ $cashOut->status == 'PENDING' ? 'selected' : '' }}>Pendente</option>
                                                    <option value="CANCELLED" {{ $cashOut->status == 'CANCELLED' || $cashOut->status == 'CANCELED' ? 'selected' : '' }}>Cancelado</option>
                                                </select>
                                            </td>
                                      </tr>
                                        @empty
                                        <tr>
                                            <td colspan="12">Nenhum registro encontrado</td>
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
                        <form method="GET" action="{{ route('admin.financeiro.saidas') }}">
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
        $("#table-financeiro-saidas").DataTable({
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
                    $('#table-financeiro-saidas_filter input[type="search"]').attr('placeholder', 'Pesquisar');
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
                            const statusCell = selectElement.closest('tr').find('td:nth-child(5)');
                            let badgeClass = '';
                            let statusText = '';
                            
                            switch(newStatus) {
                                case 'COMPLETED':
                                    badgeClass = 'bg-success';
                                    statusText = 'Aprovado';
                                    break;
                                case 'PENDING':
                                    badgeClass = 'bg-warning';
                                    statusText = 'Pendente';
                                    break;
                                case 'CANCELLED':
                                    badgeClass = 'bg-danger';
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
