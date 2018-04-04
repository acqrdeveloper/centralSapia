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
    <link rel="stylesheet" href="{{asset("dist/css/main.css")}}">
    <style>
        .form-control:focus{
            color: #495057;
            background-color: #fff;
            border-color: #343a40;
            outline: 0;
            -webkit-box-shadow: 0 0 0 0.2rem rgba(52, 58, 64, 0.25);
            box-shadow: 0 0 0 0.2rem rgba(52, 58, 64, 0.25);
        }
    </style>
</head>
<body class="face-login">

<div class="container">
    @yield('content')
</div>

<footer class="sticky-footer" style="width: 100% !important;background-color: transparent !important;color:#fff !important;">
    <div class="container">
        <div class="text-center">
            <small>Copyright © Corporación Sapia {{date("Y")}}</small>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="{{asset("dist/js/main.js")}}"></script>
</body>
</html>
