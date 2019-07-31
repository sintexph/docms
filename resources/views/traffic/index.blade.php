@extends('layouts.app-side-bar')

@section('nav-5','active')
@section('nav-5-4','active')

@section('breadcrumbs')
<li><a href="/">Home</a></li>
<li class="active">Manage Traffic</li>
@stop

@section('title','Site Traffic')
@section('header_title','Site Traffic')
@section('header_title_sm','Statistics site traffic')

@section('top_script') 

@stop

@section('content')

<div id="app-traffic">
    <site-traffic  ></site-traffic>
</div>

@stop

@section('bottom_script')
 
<script src="{{ asset('js/site-traffic.js') }}"></script>

@stop
