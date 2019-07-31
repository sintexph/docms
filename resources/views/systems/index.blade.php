@extends('layouts.app-side-bar')

@section('nav-5','active')
@section('nav-5-1','active')


@section('breadcrumbs')
<li><a href="/">Home</a></li>
<li class="active">Manage System</li>
@stop

@section('title','Manage System')
@section('header_title','Manage System')
@section('header_title_sm','All system list')


@section('top_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/datatables/datatables.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/css/select2.min.css">
@stop


@section('content')

<div id="app-system">
    <manage-system></manage-system>
</div>

@stop
@section('bottom_script')

<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="http://cdn.sportscity.com.ph/datatables/datatables.min.js"></script>
<script src="{{ asset('js/system.js') }}"></script>

@stop
