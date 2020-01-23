@extends('layouts.app-top-nav')

@section('title','Document Management System')


@section('header_title','Welcome')
@section('header_title_sm','to document management system')


@section('breadcrumbs')
<li><a href="/"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
@stop


@section('top_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/intro.js-2.9.3/minified/introjs.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/datatables/datatables.min.css">


@stop


@section('content')




<div class="row">
    <div id="doc-home" class="col-xs-4">
    <home-filter
    url_find="{{ $url_find }}"
    url_system="{{ $url_system }}"
    url_section="{{ $url_section }}"
    url_category="{{ $url_category }}"
    url_status="{{ $url_status }}"

    ></home-filter>
    </div>
    <div class="col-xs-8">
        @if($url_system==false && $url_section==false && $url_category==false && $url_status==false && $url_find==false)
        @include('home.view_src.main')
        @else
        @include('home.view_src.search')
        @endif
    </div>
</div>
@stop


@section('bottom_script')
<script src="http://cdn.sportscity.com.ph/intro.js-2.9.3/minified/intro.min.js"></script>
<script src="http://cdn.sportscity.com.ph/datatables/datatables.min.js"></script>

<script src="{{ asset('js/home.js') }}"></script>


@if(!empty(app('request')->input('guide')))
<script src="{{ asset('js/guide.js') }}"></script>
@endif

@stop
