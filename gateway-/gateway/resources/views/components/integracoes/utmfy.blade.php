<div class="card" style="border-radius: 20px;">
    <div class="card-body">
        <div style="display: flex;align-items:center;justify-content:space-between;">
            <img class="text-center" src="/build/assets/images/utmfy.png" width="150px" height="auto">
            @php
            $utmfy = auth()->user()->integracao_utmfy;
            $title = is_null($utmfy) || $utmfy == '' ? "Adcionar" : "Alterar";
            @endphp
            <button
                class="btn btn-outline-primary btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#utmfy">
                {{ $title }}
            </button>
        </div>
        @if($utmfy)
        <small><i class="fa-solid fa-circle-check text-success"></i>{{ ' ' }}Token: {{ $utmfy }}</small>
        @endif
    </div>

    <!-- Modal -->
    <div class="modal fade" id="utmfy" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="utmfyLabel">UTMFY TOKEN - {{ $title }}</h6>
                    <button id="btnDepositar" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('integracoes.utmfy.edit') }}">
                    @csrf
                    <div class="px-4 modal-body">
                        <div class="row gy-2">

                            <!-- Campo de valor -->
                            <div class="col-xl-12">
                                <label for="integracao_utmfy" class="form-label">API TOKEN</label>
                                <input type="text" class="form-control" id="integracao_utmfy" name="integracao_utmfy" placeholder="Digite seu API TOKEN" value="{{ $utmfy }}" required>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">{{ $title }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>