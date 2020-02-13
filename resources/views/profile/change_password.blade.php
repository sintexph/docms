@extends('layouts.app-side-bar')



@section('breadcrumbs')
<li><a href="{{ route('manage.documents') }}">Manage Documents</a></li>
<li class="active">Change Password</li>
@stop

@section('title','Change Password')
@section('header_title','Change Password')
@section('header_title_sm','set your desired password')

@section('content')


<div class="box box-solid ">

    <div class="box-header">
        <h3 class="box-title">Change Account Password</h3>
    </div>

    <div class="login-box-body">
        <div class="row">
            <div class="col-xs-6">
            @include('layouts.sessions')
                <form action="{{ route('profile.change_password') }}" method="post">
                    @csrf


                    <div class="form-group">
                        <label class="label-control">Old Password <span title="required"
                                class="text-red">*</span></label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="label-control">New Password <span title="required"
                                class="text-red">*</span></label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>


                    <div class="form-group">
                        <label class="label-control">New Password Confirmation <span title="required"
                                class="text-red">*</span></label>
                        <input type="password" name="new_password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-sm btn-primary pull-right">Change Password</button>
                </form> 
            </div>
          
        </div>

    </div>

</div>

@stop