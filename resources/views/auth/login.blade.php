@extends('layouts.form')

@section('content')
    <form method="POST" action="{{ route('login') }}" role="form" class="text-start" novalidate>
        @csrf

        <div class="text-center">
            <div class="mb-5">
            <div class="text-center mb-4 d-lg-none">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('img/logotipo.svg') }}"
                        alt="Logotipo"
                        style="min-width: 20rem; max-width: 20rem;"
                        data-hs-theme-appearance="default">
                    <img src="{{ asset('img/logotipo-white.svg') }}"
                        alt="Logotipo"
                        style="min-width: 20rem; max-width: 20rem;"
                        data-hs-theme-appearance="dark">
                </a>
            </div>
                <h1 class="display-5">Iniciar Sesión</h1>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label" for="signinSrEmail">Correo electrónico</label>
            <input type="email" class="form-control form-control-lg" name="email" id="signinSrEmail" tabindex="1"
                placeholder="Ingrese su correo electrónico" required>
            <span class="invalid-feedback">Por favor, ingrese un correo electrónico válido.</span>
        </div>
        
        <div class="mb-4">
            <label class="form-label w-100" for="signupSrPassword" tabindex="0">
                <span class="d-flex justify-content-between align-items-center">
                    <span>Contraseña</span>
                </span>
            </label>

            <div class="input-group input-group-merge" data-hs-validation-validate-class>
                <input type="password" class="js-toggle-password form-control form-control-lg" name="password"
                    id="signupSrPassword" placeholder="Ingrese su contraseña" required
                    minlength="8"
                    data-hs-toggle-password-options='{
                           "target": "#changePassTarget",
                           "defaultClass": "bi-eye-slash",
                           "showClass": "bi-eye",
                           "classChangeTarget": "#changePassIcon"
                         }'>
            </div>
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" value="" id="termsCheckbox">
            <label class="form-check-label" for="termsCheckbox">
                Recuérdame
            </label>
        </div>
        
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">Iniciar Sesión</button>
        </div>
    </form>
@endsection