@extends('layouts.app')

@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">
            <div class="row justify-content-sm-center text-center py-10">
                <div class="col-sm-7 col-md-5">
                    <img class="img-fluid mb-5" src="{{ asset('img/logotipo.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                    <img class="img-fluid mb-5" src="{{ asset('img/logotipo-white.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                    <h1>Bienvenido al sistema de gestión de inventarios y solicitudes de mantenimiento</h1>
                    <p>Este sistema te permitirá gestionar los inventarios y solicitudes de mantenimiento del hospital</p>
                </div>
            </div>
        </div>
    </main>
@endsection