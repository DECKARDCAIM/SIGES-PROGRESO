<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/tom-select/dist/css/tom-select.bootstrap5.css') }}">
    <link rel="preload" href="{{ asset('css/theme.min.css') }}" data-hs-appearance="default" as="style">
    <link rel="preload" href="{{ asset('css/theme-dark.min.css') }}" data-hs-appearance="dark" as="style">
    <script src="{{ asset('js/form.js') }}"></script>
</head>

<body class="d-flex align-items-center min-h-100">
    <script src="{{ asset('js/hs.theme-appearance.js') }}"></script>
    <main id="content" role="main" class="main pt-0">
        <div class="container-fluid px-3">
            <div class="row">
                @include('includes.form.content')
                <div class="col-lg-6 d-flex justify-content-center align-items-center min-vh-lg-100">
                    <div class="w-100 content-space-t-4 content-space-t-lg-2 content-space-b-1"
                        style="max-width: 25rem;">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="{{ asset('vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-migrate/dist/jquery-migrate.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/hs-toggle-password/dist/js/hs-toggle-password.js') }}"></script>
    <script src="{{ asset('vendor/tom-select/dist/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('js/theme.min.js') }}"></script>
</body>
</html>