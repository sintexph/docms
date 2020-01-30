

@extends('layouts.app-side-bar')

 

@section('breadcrumbs')
<li><a href="{{ route('manage.documents') }}">Manage Documents</a></li>
<li class="active">Notification Settings</li>
@stop

@section('title','Notification Settings')
@section('header_title','Notification Settings')
@section('header_title_sm','Customize your notifications')


@section('top_script')

<link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/plugins/iCheck/all.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/datatables/datatables.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/css/select2.min.css">
@stop


@section('content')

<div id="app-profile">
    <notification-settings ref="notificationSettings"></notification-settings>
</div>

@stop
@section('bottom_script')
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/plugins/iCheck/icheck.min.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="http://cdn.sportscity.com.ph/datatables/datatables.min.js"></script>
<script src="{{ asset('js/profile.js') }}"></script>

@stop
