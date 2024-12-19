<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="user-id" content="{{ Auth::id() }}">
        <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">


        <!-- <title>{{ config('app.name', 'Laravel') }}</title> -->
        <title>@yield('title')</title>

        <!-- Styles -->
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src={{ url('js/app.js') }} defer>
        </script>
    </head>
    <body>
        <main>
            @include('partials.timeline')

            <!-- Barra de navegação com ícone de Purchase History -->
            <div class="header-buttons" style="display: flex; justify-content: flex-end; padding: 10px;">
                <!-- Histórico de Compras -->
                <a href="{{ route('purchase.history') }}">
                    <!-- Aumentando o tamanho do ícone para 50px -->
                    <img src="{{ asset('images/history-icon.png') }}" alt="Purchase History" style="width: 50px; height: 50px;">
                </a>
            </div>

            <section id="content">
                @yield('content')
            </section>
        </main>
    </body>
</html>
