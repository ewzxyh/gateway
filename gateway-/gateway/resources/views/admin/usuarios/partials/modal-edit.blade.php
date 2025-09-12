@props([
'user',
'gerentes'
])

<div class="modal fade" id="editModal-{{ $user->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel-{{ $user->id }}">Editar Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('admin.usuarios.edit', ['id' => $user->id]) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editUserId" name="id">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="editNome-{{ $user->id }}" class="form-label">Nome</label>
                            <input type="text" value="{{ $user->name }}" class="form-control" id="editNome-{{ $user->id }}" name="name">
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="editCpf-{{ $user->cpf_cnpj }}" class="form-label">CPF/CNPJ</label>
                            <input type="text" value="{{ $user->cpf_cnpj }}" class="form-control" id="editCpf-{{ $user->cpf_cnpj }}" name="cpf_cnpj">
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="editTelefone-{{ $user->telefone }}" class="form-label">Telefone</label>
                            <input type="text" value="{{ $user->cpf_cnpj }}" class="form-control" id="editTelefone-{{ $user->telefone }}" name="telefone">
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="editDn-{{ $user->data_nascimento }}" class="form-label">Data nascimento</label>
                            <input type="date" value="{{ $user->data_nascimento }}" class="form-control" id="editDn-{{ $user->data_nascimento }}" name="data_nascimento">
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="editEmail-{{ $user->id }}" class="form-label">Email</label>
                            <input type="email" value="{{ $user->email }}" class=" form-control" id="editEmail-{{ $user->id }}" name="email">
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="editPermission-{{ $user->id }}" class="form-label">Permissão</label>
                            <select
                                onchange="onChangePermission(this, '{{$user->id}}')"
                                class="form-select"
                                value="{{ $user->permission }}"
                                id="editPermission-{{ $user->id }}"
                                data-permission-select
                                data-permission-id="{{ $user->id }}"
                                name="permission">
                                <option value="3" {{ (int) $user->permission == 3 ? "selected" : "" }}>Administrador</option>
                                <option value="5" {{ (int) $user->permission == 5 ? "selected" : "" }}>Gerente</option>
                                <option value="1" {{ (int) $user->permission == 1 ? "selected" : "" }}>usuario</option>
                                <!-- Adicione outras permissões conforme necessário -->
                            </select>
                        </div>
                        <div class="hidden mb-3 col-md-3" id="container-percentage-{{$user->id}}">
                            <label for="editPorcentagem-{{ $user->id }}" class="form-label">Porcentagem</label>
                            <input type="text" value="{{ $user->gerente_percentage }}" class=" form-control" id="editPorcentagem-{{ $user->id }}" id="gerente_percentage" name="gerente_percentage">
                        </div>
                        <div class="hidden mb-3 col-md-3" id="container-gerenteaprovar-{{$user->id}}">
                            <div class="pt-4 form-check form-switch d-flex align-center">
                                <input
                                    class="form-check-input custom-switch-lg"
                                    name="gerente_aprovar"
                                    type="checkbox"
                                    role="switch"
                                    id="switchCheckDefault"
                                    value="{{ $user->gerente_aprovar }}"
                                    {{ ($user->gerente_aprovar ?? false) ? 'checked' : '' }}>
                                <label class="text-xl form-check-label ms-2" for="switchCheckDefault">
                                    Pode aprovar
                                </label>
                            </div>
                        </div>

                        <div class="mb-3 col-md-3" id="container-gerente-{{$user->id}}">
                            <label for="editGerente-{{ $user->id }}" class="form-label">Gerente</label>
                            <select class="form-select" id="editGerente-{{ $user->id }}" name="gerente_id">
                                <option value="" {{ $user->gerente_id == NULL ? "selected" : "" }}>Nenhum</option>
                                @foreach ($gerentes as $gerente)
                                <option value="{{ $gerente->id }}" {{ $user->gerente_id == $gerente->id ? "selected" : "" }}>{{ $gerente->name }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="mb-3 col-lg-3" id="container-cash-in-{{$user->id}}">
                            <p for="e-cash-in-{{ $user->id }}" class="form-label">Taxa Cash In (%)</p>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $user->taxa_cash_in ?? '' }}" name="taxa_cash_in" id="e-cash-in-{{ $user->id }}">
                            </div>
                        </div>
                        <div class="mb-3 col-lg-3" id="container-cash-in-fixa-{{$user->id}}">
                            <p for="e-cash-in-fixa-{{ $user->id }}" class="form-label">Taxa Cash In Fixa (R$)</p>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $user->taxa_cash_in_fixa ?? '' }}" name="taxa_cash_in_fixa" id="e-cash-in-fixa-{{ $user->id }}">
                            </div>
                        </div>
                         <div class="mb-3 col-lg-3" id="container-cash-out-{{$user->id}}">
                            <p for="e-cash-out-{{ $user->id }}" class="form-label">Taxa Cash Out (%)</p>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $user->taxa_cash_out ?? '' }}" name="taxa_cash_out" id="e-cash-out-{{ $user->id }}">
                            </div>
                        </div>

                        <div class="mb-3 col-lg-3" id="container-cash-out-fixa-{{$user->id}}">
                            <p for="e-cash-out-fixa-{{ $user->id }}" class="form-label">Taxa Cash Out Fixa (R$)</p>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $user->taxa_cash_out_fixa ?? '' }}" name="taxa_cash_out_fixa" id="e-cash-out-fixa-{{ $user->id }}">
                            </div>
                        </div>

                        <div class="mb-3 col-md-6" id="container-token-{{$user->id}}">
                            <p for="e-token-{{ $user->id }}" class="form-label">Token</p>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $user->chaves->token ?? '' }}" name="token" id="e-token-{{ $user->id }}" readonly>
                                <button class="btn btn-primary" onclick="gerarChaveToken('{{ $user->id }}')" type="button"><i class="fa-solid fa-sync"></i></button>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6" id="container-secret-{{$user->id}}">
                            <p for="e-secret-{{ $user->id }}" class="form-label">Secret</p>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $user->chaves->secret ?? '' }}" name="secret" id="e-secret-{{ $user->id }}" readonly>
                                <button class="btn btn-primary" onclick="gerarChaveSecret('{{ $user->id }}')" type="button"><i class="fa-solid fa-sync"></i></button>
                            </div>
                        </div>
                        <div class="mb-3 col-md-4">
                            <x-image-upload id="foto_rg_frente" name="foto_rg_frente" label="Foto RG (Frente)" :value="'/'.$user->foto_rg_frente" />
                        </div>
                        <div class="mb-3 col-md-4">
                            <x-image-upload id="foto_rg_verso" name="foto_rg_verso" label="Foto RG (Verso)" :value="'/'.$user->foto_rg_verso" />
                        </div>
                        <div class="mb-3 col-md-4">
                            <x-image-upload id="selfie_rg" name="selfie_rg" label="Selfie RG" :value="'/'.$user->selfie_rg" />
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Salvar alterações</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modals = document.querySelectorAll('.modal');

        modals.forEach(modal => {
            modal.addEventListener('shown.bs.modal', function() {
                Inputmask({
                    "mask": "(99) 99999-9999"
                }).mask(modal.querySelectorAll('input[name="telefone"]'));
                Inputmask({
                    mask: ["999.999.999-99", "99.999.999/9999-99"],
                    keepStatic: true
                }).mask(modal.querySelectorAll('input[name="cpf_cnpj"]'));
                Inputmask({
                    "mask": "99999-999"
                }).mask(modal.querySelectorAll('input[name="cep"]'));
            });
        });



    });

    document.addEventListener('DOMContentLoaded', function() {
        // Executa onChangePermission para todos os campos já preenchidos
        document.querySelectorAll('[data-permission-select]').forEach(function(select) {
            const id = select.dataset.permissionId;
            onChangePermission(select, id);
        });
    });

    function onChangePermission(input, id) {
        console.log()
        const containerPercentage = document.getElementById(`container-percentage-${id}`);
        const containerToken = document.getElementById(`container-token-${id}`);
        const containerSecret = REDACTED_SECRET(`container-secret-${id}`);
        const containerGerente = document.getElementById(`container-gerente-${id}`);
        const containerGerenteAprovar = document.getElementById(`container-gerenteaprovar-${id}`);

        if (input.value === "5") {
            containerPercentage.classList.remove('hidden');
            containerGerenteAprovar.classList.remove('hidden');
            containerToken.classList.remove('col-md-6');
            containerToken.classList.add('col-md-3');
            containerSecret.classList.remove('col-md-6');
            containerSecret.classList.add('col-md-3');
            containerGerente.classList.remove('col-md-3');
            containerGerente.classList.add('col-md-3');
        } else {
            containerPercentage.classList.add('hidden');
            containerGerenteAprovar.classList.add('hidden');
            containerToken.classList.remove('col-md-3');
            containerToken.classList.add('col-md-6');
            containerSecret.classList.remove('col-md-3');
            containerSecret.classList.add('col-md-6');
            containerGerente.classList.remove('col-md-3');
            containerGerente.classList.add('col-md-3');
        }
    };
</script>