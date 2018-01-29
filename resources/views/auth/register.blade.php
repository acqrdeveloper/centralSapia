@extends('layouts.app')

@section('content')
    <div class="card card-login mx-auto mt-5">
        <div class="card-header">Sign Up</div>
        <div class="card-body">
            <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label>Name</label>
                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
                    @if ($errors->has('name'))
                        <span class="help-block"><strong>{{ $errors->first('name') }}Invalid email</strong></span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email">E-Mail Address</label>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                    @if ($errors->has('email'))
                        <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password">Password</label>
                    <input id="password" type="password" class="form-control" name="password" required>
                    @if ($errors->has('password'))
                        <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="password-confirm">Confirm Password</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                </div>
                {{--<div class="form-group">--}}
                    <button type="submit" class="btn btn-primary btn-block">Create</button>
                {{--</div>--}}
            </form>
            {{--<div>--}}
                {{--<br>--}}
                {{--<div class="row">--}}
                    {{--<div class="col-6">--}}
                        {{--<div class="text-left text-nowrap">--}}
                            {{--<a class="small" href="register.html">Register an Account</a>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-6">--}}
                        {{--<div class="text-right text-nowrap">--}}
                            {{--<a class="small" href="forgot-password.html">Forgot Password?</a>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>
@endsection
