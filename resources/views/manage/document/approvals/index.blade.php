@extends('layouts.app-side-bar')

@section('nav-4','active')


@section('breadcrumbs')
<li class="active"><i class="fa fa-thumbs-up" aria-hidden="true"></i> Approvals</li>
@stop

@section('title','Approve Document Versions')

@section('header_title','Document Versions')
@section('header_title_sm','Approve all created document versions')


@section('top_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/datatables/datatables.min.css">
@stop


@section('content')
<div id="app-document">
    <doc-for-approval></doc-for-approval>
</div>
@stop

@section('bottom_script')
<script src="http://cdn.sportscity.com.ph/datatables/datatables.min.js"></script>
<script src="{{ asset('js/manage/for_approval.js') }}"></script>
@stop
