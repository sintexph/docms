@extends('layouts.app-side-bar')

@section('nav-9','active')

@section('breadcrumbs')
<li><a href="/">Home</a></li>
<li class="active">Trashed Document</li>
@stop

@section('title','Trashed Documents')
@section('header_title','Trashed Documents')
@section('header_title_sm','All your trashed documents')

@section('top_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/datatables/datatables.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/css/select2.min.css">
@stop

@section('content')

<div id="app-trashed">
    <trashed-documents :systems="{{ json_encode($systems) }}"></trashed-documents>
</div>

@stop

@section('bottom_script')

<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="http://cdn.sportscity.com.ph/datatables/datatables.min.js"></script>
<script src="{{ asset('js/trashed-document.js') }}"></script>

@stop
