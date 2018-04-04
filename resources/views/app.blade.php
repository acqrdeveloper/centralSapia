<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sapia</title>
    <!-- Styles -->
    <link rel="stylesheet" href="{{asset("dist/css/main.css")}}">
</head>
{{--<body class="fixed-nav sticky-footer bg-dark sidenav-toggled" id="page-top">--}}
<body class="face-app fixed-nav sticky-footer bg-light sidenav-toggled" id="page-top">
@if(auth()->check())
    <script>
      window.onload = () => {
        window.localStorage.setItem('data_auth', '{!! auth()->user() !!}')
      }
    </script>
@endif
<div id="app">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark" id="mainNav">
        <a href="/" class="navbar-brand m-auto">
            <h5 class="m-auto">Gestión de Reportes</h5>
        </a>
        <button class="navbar-toggler navbar-toggler-right"
                type="button"
                data-toggle="collapse"
                data-target="#navbarResponsive"
                aria-controls="navbarResponsive"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            @include("layouts.navbar-left")
            @include("layouts.navbar-top")
        </div>
    </nav>

    <div class="content-wrapper">
        <div class="container-fluid">
            <router-view></router-view>
        </div>
    </div>

    <footer class="sticky-footer">
        <div class="container">
            <div class="text-center">
                <small>Copyright © Corporación Sapia {{date("Y")}}</small>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top" @click="scrollUp()">
        <i class="fa fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Scripts -->
<script src="{{asset("dist/js/main.js")}}"></script>
<script src="{{ asset('dist/js/app.js') }}"></script>
</body>
</html>
