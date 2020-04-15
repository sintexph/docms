@extends('layouts.app-side-bar')

@section('nav-3','active')


@section('breadcrumbs')
<li class="active"><i class="fa fa-eye" aria-hidden="true"></i> Reviews</li>
@stop

@section('title','Review Document Versions')

@section('header_title','Document Versions')
@section('header_title_sm','Review all created document versions')


@section('top_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/datatables/datatables.min.css">
@stop


@section('content')
<div id="app-document">
    <doc-for-review></doc-for-review>
</div>
@stop

@section('bottom_script')
<script src="http://cdn.sportscity.com.ph/datatables/datatables.min.js"></script>
<script src="{{ asset('js/manage/for_review.js') }}"></script>
@stop
