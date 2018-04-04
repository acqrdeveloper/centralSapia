@extends('layouts.app')
@section('content')
    <div class="m-5 text-center">
        <img src="{{asset('logo.svg')}}" alt="" width="100px">
    </div>
    <div class="card card-login mx-auto mt-5">
        <div class="card-header">Sign In</div>
        <div class="card-body">
            <form method="post" action="{{ url('/login') }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label>Proyecto</label>
                    <select name="proyecto" class="form-control mb-1">
                        <option value="0">Administrativo</option>
                        <option value="1">Interbank</option>
                        <option value="2" selected>Corporativo</option>
                        <option value="3">Entel</option>
                        <option value="4">Empresas</option>
                        <option value="5">Calidda</option>
                    </select>
                    {{--@if ($errors->has('proyecto'))--}}
                        {{--<span class="help-block"><small><strong>{{ $errors->first('proyecto') }}</strong></small></span>--}}
                    {{--@endif--}}
                </div>
                <div class="form-group">
                    <label>Email address</label>
                    <div class="input-group mb-1">
                        <input name="email" class="form-control" type="text" placeholder="Enter email" title="Registrar"
                               value="{{old("email")}}">
                        <div class="input-group-append">
                            <span class="input-group-text">@sapia.com.pe</span>
                        </div>
                    </div>
                    @if ($errors->has('email'))
                        <span class="help-block"><small><strong>{{ $errors->first('email') }}</strong></small></span>
                    @endif
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input name="password" class="form-control mb-1" type="password" placeholder="Password">
                    @if ($errors->has('password'))
                        <span class="help-block"><small><strong>{{ $errors->first('password') }}</strong></small></span>
                    @endif
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <label class="form-check-label"><input name="rememberme" class="form-check-input"
                                                               type="checkbox" {{ old("rememberme") ? "checked" : "" }}>
                            Remember Password</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Log In</button>
            </form>
            <div hidden>
                <br>
                <div class="row">
                    <div class="col-6">
                        <div class="text-left text-nowrap">
                            <a class="small text-dark" href="{{url("/register")}}">Register an Account</a>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-right text-nowrap">
                            <a class="small text-dark" href="{{url("/register")}}">Forgot Password?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
