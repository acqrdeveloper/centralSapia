<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{asset("dist/main.css")}}">
</head>
<body class="face-login">

<div class="container">
    @yield('content')
</div>

<footer class="sticky-footer"
        style="width: 100% !important;background-color: transparent !important;color:#fff !important;">
    <div class="container">
        <div class="text-center">
            <small>Copyright © Sapia Corporation {{date("Y")}}</small>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="{{asset("dist/main.js")}}"></script>
</body>
</html>
