@extends('layouts.app-side-bar')

@section('nav-6','active')

@section('title','Create Document')
@section('header_title','Create Documents')
@section('header_title_sm','Please fill in all the necessary fields.')


@section('breadcrumbs')
<li><a href="/">Home</a></li>
<li><a href="{{ route('manage.documents') }}">Manage Document</a></li>
<li class="active">Create</li>
@stop



@section('top_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.css">
<script src='http://cdn.sportscity.com.ph/tinymce/tinymce.min.js'></script>
@stop

@section('content')
<div id="app-document">

    <document-create 
    @if($draft!=null)
    :draft="{{ json_encode($draft) }}"
    @endif
    >
    </document-create>
</div>
@stop

@section('bottom_script')

<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.3/plugins/input-mask/jquery.inputmask.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.3/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="{{ asset('js/manage/document.js') }}"></script>

@stop

