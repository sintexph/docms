@extends('layouts.app-top-nav')

@section('title','System Login')


@section('breadcrumbs')
<li><a href="/"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
<li class="active"><a href="{{ route('login') }}"><i class="fa fa-lock" aria-hidden="true"></i> Login</a></li>
@stop

@section('header_title','Document Management System')
@section('header_title_sm','System Login')

@section('content')

@include('layouts.sessions')

<div class="box box-solid ">

    <div class="box-header">
        <h3 class="box-title">Register an account</h3>
    </div>

    <div class="login-box-body">
        <div class="row">
            <div class="col-xs-6">
                <form action="{{ route('register') }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label class="label-control">Account Name <span title="required"
                                class="text-red">*</span></label>
                        <input class="form-control text-uppercase" value="{{ old('name') }}" name="name" required>
                    </div>
                    <div class="form-group">
                        <label class="label-control">Email <span title="required" class="text-red">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="label-control">Position <span title="required" class="text-red">*</span></label>
                        <input type="text" name="position" value="{{ old('position') }}"
                            class="form-control text-uppercase" required>
                    </div>
                    <div class="form-group">
                        <label class="label-control">Username <span title="required" class="text-red">*</span></label>
                        <input type="text" name="username" value="{{ old('username') }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="label-control">Password <span title="required" class="text-red">*</span></label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="label-control">Password Confirmation <span title="required"
                                class="text-red">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-sm btn-primary pull-right">Submit Registration</button>
                </form>
                <a href="{{ route('register') }}">Click here to login</a><br>
            </div>
            <div class="col-xs-6">
            
            </div>
        </div>

    </div>

</div>



@endsection
