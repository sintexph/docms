@extends('layouts.app-side-bar')

@section('nav-5','active')
@section('nav-5-4','active')


@section('breadcrumbs')
<li><a href="/">Home</a></li>
<li class="active">Manage Content Titles</li>
@stop

@section('title','Manage Content Titles')
@section('header_title','Manage Content Titles')
@section('header_title_sm','All content title list')


@section('top_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/datatables/datatables.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/css/select2.min.css">
@stop


@section('content')

<div id="app-content-title">
    <manage-content-title></manage-content-title>
</div>

@stop
@section('bottom_script')

<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="http://cdn.sportscity.com.ph/datatables/datatables.min.js"></script>
<script src="{{ asset('js/content-title.js') }}"></script>

@stop
