@php
use App\Helpers\Helper;
$setting = Helper::getSetting();
@endphp
<x-guest-layout :route="'Cadastrar-me'">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .form-outlined {
          position: relative;
          margin-top: 2rem;  }

        .form-outlined input {
          padding: 1.25rem 2.5rem 0.5rem 0.75rem;
          border: 1px solid #ced4da;
          border-radius: 0.375rem;
          outline: none;
          width: 100%;
          background: transparent;
          min-height: 56px !important;

        }

        .form-outlined label {
          position: absolute;
          top: 1rem;
          left: 0.75rem;
          background: white;
          padding: 0 0.25rem;
          color: #6c757d;
          font-size: 1rem;
          transition: 0.2s ease all;
          pointer-events: none;
        }

        .form-outlined input:focus + label,
        .form-outlined input:not(:placeholder-shown) + label {
          top: -0.6rem;
          left: 0.5rem;
          font-size: 0.75rem;
          color: var(--color-gateway);
        }

        .form-outlined input:focus {
            border-color: var(--color-gateway) !important;
            box-shadow: 0 0 2px 1px var(--color-gateway) !important;
        }
        .toggle-visibility {
          position: absolute;
          top: 50%;
          right: 0.75rem;
          transform: translateY(-50%);
          background: transparent;
          border: none;
          font-size: 1.25rem;
          color: #6c757d;
          cursor: pointer;
        }
        .form-title,
        .mdc-label::after {
            color: gray !important;
        }
        .form-subtitle{
            color: gray !important;
        }
      </style>

    <div class="col-xxl-7 col-xl-10">
        <div class="mt-5 mb-5 card card-raised shadow-10 mt-xl-10">
            <div class="p-5 card-body">
                <div class="text-center">
                    <img class="mb-3" src="{{ asset($setting->gateway_logo)}}" alt="..." style="height: 48px" />
                    <h1 class="mb-0 display-5 form-title">Crie uma nova conta</h1>
                    <div class="mb-5 subheading-1 form-subtitle">para ter acesso a plataforma</div>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <input type="hidden" name="ref" value="{{ request('ref') }}">

                    <div class="row">
                        <div class="mb-2 col-sm-12">
                            <div class="form-outlined">
                                <input type="text"  name="name" value="{{ old('name') }}" class="form-control" placeholder=" " />
                                <label for="name">Nome Completo</label>
                            </div>
                            @if ($errors->has('name'))
                            <span class="text-danger">{{  $errors->first('name') }} </span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-2 col-sm-6">
                            <div class="form-outlined">
                                <input type="text"  name="telefone" value="{{ old('telefone') }}" class="form-control" placeholder=" " />
                                <label for="telefone">Telefone</label>
                            </div>
                            @if ($errors->has('telefone'))
                            <span class="text-danger">{{  $errors->first('telefone') }} </span>
                            @endif
                        </div>
                        <div class="mb-2 col-sm-6">
                            <div class="form-outlined">
                                <input type="text"  name="username" value="{{ old('username') }}" class="form-control" placeholder=" " />
                                <label for="username">Username</label>
                            </div>
                            @if ($errors->has('username'))
                            <span class="text-danger">{{  $errors->first('username') }} </span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="form-outlined">
                            <input type="email"  name="email" value="{{ old('email') }}" class="form-control" placeholder=" " />
                            <label for="email">Email</label>
                        </div>
                        @if ($errors->has('email'))
                        <span class="text-danger">{{  $errors->first('email') }} </span>
                        @endif
                    </div>
                    <div class="row">
                        <div class="mb-2 col-sm-6">
                            <div class="form-outlined">
                                <input type="password" id="passwordInput" name="password" value="{{ old('password') }}" class="form-control" placeholder=" " />
                                <label for="passwordInput">Senha</label>
                                <button type="button" id="togglePassword" class="toggle-visibility">
                                    <i id="i-p" class="bi bi-eye-slash"></i>
                                </button>
                            </div>
                            @if ($errors->has('password'))
                            <span class="text-danger">{{  $errors->first('password') }} </span>
                            @endif
                        </div>
                        <div class="mb-2 col-sm-6">
                            <div class="form-outlined">
                                <input type="password" id="passwordConfirmationInput" name="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control" placeholder=" " />
                                <label for="passwordConfirmationInput">Confirmar senha</label>
                                <button type="button" id="togglePasswordConfirmation" class="toggle-visibility">
                                    <i id="i-c" class="bi bi-eye-slash"></i>
                                </button>
                            </div>
                            @if ($errors->has('password_confirmation'))
                        <span class="text-danger">{{  $errors->first('password_confirmation') }} </span>
                        @endif
                        </div>
                    </div>
                    <div class="mt-4 mb-0 form-group d-flex align-items-center justify-content-between">
                        <a class="small fw-500 text-decoration-none" href="/login">Efetuar login</a>
                        <button type="submit" class="btn btn-primary" >Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
          const toggleBtn = document.getElementById("togglePassword");
          const passwordInput = document.getElementById("passwordInput");
          const icon = toggleBtn.querySelector("#i-p");

          toggleBtn.addEventListener("click", function () {
            const isPassword = REDACTED_SECRET === "password";
            passwordInput.type = isPassword ? "text" : "password";
            icon.classList.toggle("bi-eye");
            icon.classList.toggle("bi-eye-slash");
        });

        const toggleBtnConf = document.getElementById("togglePasswordConfirmation");
        const passwordInputConf = document.getElementById("passwordConfirmationInput");
        const iconConf = toggleBtn.querySelector("#i-c");

        toggleBtnConf.addEventListener("click", function () {
            const isPassword = REDACTED_SECRET === "password";
            passwordInputConf.type = isPassword ? "text" : "password";
            iconConf.classList.toggle("bi-eye");
            iconConf.classList.toggle("bi-eye-slash");
          });

        });
      </script>
</x-guest-layout>
