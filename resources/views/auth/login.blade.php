@extends('layouts.app-top-nav')

@section('title','System Login')


@section('breadcrumbs')
<li><a href="/"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
<li class="active"><a href="{{ route('login') }}"><i class="fa fa-lock" aria-hidden="true"></i> Login</a></li>
@stop

@section('header_title','Document Management System')
@section('header_title_sm','System Login')

@section('content')

<div class="box box-solid login-box">

    <div class="box-header">
        <h3 class="box-title">Login to Document Management System</h3>
    </div>

    <div class="login-box-body">
        <center><img src="{{ asset('img/brand-icon.png') }}" class="img-responsive login-logo-img" alt="Document Management System"></center>
        <p class="login-box-msg">Sign in to start your session</p>
        <form action="{{ route('login') }}" method="post">
            @csrf
            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Username">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>

            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password" placeholder="Password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-8">
                    <label>
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                            {{ old( 'remember') ? 'checked' : '' }}>
                        Remember Me
                    </label>
                </div>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-sm btn-block">Sign In</button>
                </div>
            </div>
        </form>
        <a href="{{ route('password.request') }}">I forgot my password</a><br>

    </div>
    @include('layouts.sessions')
</div>

@stop
