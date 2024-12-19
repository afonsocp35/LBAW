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
    @yield('cssRef')
    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>
    <script type="text/javascript" src={{ url('js/app.js') }} defer>
    </script>
</head>
<body>
    <!--
    <header>
        ...
    </header>
    !-->
    @yield('content')
</body>
</html>
