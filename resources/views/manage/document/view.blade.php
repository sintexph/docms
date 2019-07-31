@extends('layouts.app-side-bar')

@section('nav-2','active')

@section('title',$document->title.' | '.$document->document_number)

@section('header_title',$document->title.' - Version: '.$document->version)
@section('header_title_sm',$document->document_number)



@section('breadcrumbs')
<li><a href="/">Home</a></li>
<li><a href="{{ route('manage.documents') }}">Manage Document</a></li>
<li class="active">{{ $document->title }}</li>
@stop




@section('top_script')

<link rel="stylesheet" href="/css/timeline.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.css">
<script src='http://cdn.sportscity.com.ph/tinymce/tinymce.min.js'></script>
@stop

@section('content')


@include('layouts.sessions')


@if($document->locked==true)
<div class="alert-custom alert-custom-danger">
    <span><i class="fa fa-lock" aria-hidden="true"></i> Document is locked.</span>
</div>
@endif

@if($document->archived==true)
<div class="alert-custom alert-custom-warning">
    <span><i class="fa fa-archive" aria-hidden="true"></i> Document is added to archived.</span>
</div>
@endif


@if($document->obsolete==true)
<div class="alert-custom alert-custom-warning">
    <span><i class="fa fa-ban" aria-hidden="true"></i> Document is obsolete.</span>
</div>
@endif

<div id="app-document">
    @include('manage.document.view_src.tab-actions')


    @if($current_view==false)
    @include('manage.document.view_src.views.default')
    @elseif($current_view=='new' && $selected_version->reviewed==true && $selected_version->released==true && $selected_version->approved==true)
    @include('manage.document.view_src.views.new_version')
    @elseif($current_view=='edit' && $document->current_version->approved==false)
    @include('manage.document.view_src.views.edit_document')
    @elseif($current_view=='edit_access')
    @include('manage.document.view_src.views.edit_access')
    @elseif($current_view=='lock')
    @include('manage.document.view_src.views.lock')
    @elseif($current_view=='change_status')
    @include('manage.document.view_src.views.change_status')
    @elseif($current_view=='histories')
    @include('manage.document.view_src.views.histories')
    @elseif($current_view=='archive')
    @include('manage.document.view_src.views.archive')
    @endif
</div>

@stop

@section('bottom_script')


<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.3/plugins/input-mask/jquery.inputmask.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.3/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="{{ asset('js/manage/document.js') }}"></script>


@stop
