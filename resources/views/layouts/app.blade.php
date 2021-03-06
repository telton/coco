<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}: {{ env('APP_DESCRIPTION') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" media="all">
</head>
<body>
    <div id="app">
        @include('includes.auth')
        @include('flash::message')

        <div class="main-content">
            @yield('nav')
            
            <div class="content-body">
                @yield('content')
            </div>
            
            @yield('aside')
        </div>
    </div>

    <!-- Scripts -->
    <script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>
    <script type="text/javascript">
        window.ParsleyConfig = {
            errorClass: 'is-invalid',
            successClass: 'is-valid',
            errorsWrapper: '<ul class="parsley-errors-list invalid-feedback"></ul>'
        }

        // Enable tooltops everywhere.
        window.$(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
</body>
</html>
