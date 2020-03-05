@extends('layouts.app-side-bar')

@section('nav-2','active')


@section('breadcrumbs')
<li><a href="/">Home</a></li>
<li class="active">Manage Document</li>
@stop

@section('title','Manage Documents')
@section('header_title','Manage Documents')
@section('header_title_sm','All your created documents')


@section('top_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/datatables/datatables.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/css/select2.min.css">
@stop

@section('header_tools')
<div class="header-tools">
    <a href="{{ route('manage.documents.create') }}" class="btn btn-success">Create Document</a>
</div>
@stop

@section('content')

<div id="app-document">
    <manage-documents 
        :systems="{{ json_encode($systems) }}"
        :categories="{{ json_encode($categories) }}"
        :sections="{{ json_encode($sections) }}"

        filter_find="{{ $find }}"
        filter_state="{{ $state }}"
        filter_section="{{ $section }}"
        filter_system="{{ $system }}"
        filter_category="{{ $category }}"
    >
    </manage-documents>
</div>



@stop
@section('bottom_script')

<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="http://cdn.sportscity.com.ph/datatables/datatables.min.js"></script>
<script src="{{ asset('js/manage/document.js') }}"></script>

@stop
