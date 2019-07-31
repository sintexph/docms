@extends('layouts.app-side-bar')

@section('nav-8','active')


@section('breadcrumbs')
<li><a href="/">Home</a></li>
<li class="active">Manage Document Draft</li>
@stop

@section('title','Manage Document Drafts')
@section('header_title','Manage Document Drafts')
@section('header_title_sm','All your created document drafts')


@section('top_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/datatables/datatables.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/css/select2.min.css">
@stop


@section('content')

<div id="app-draft">
    <draft-list></draft-list>
</div>

@stop
@section('bottom_script')

<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="http://cdn.sportscity.com.ph/datatables/datatables.min.js"></script>
<script src="{{ asset('js/draft.js') }}"></script>

@stop
