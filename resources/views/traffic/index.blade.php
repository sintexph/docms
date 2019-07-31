@extends('layouts.app-side-bar')

@section('nav-10','active')

@section('breadcrumbs')
<li><a href="/">Home</a></li>
<li class="active">Traffic Monitoring</li>
@stop

@section('title','Traffic Monitoring')
@section('header_title','Traffic Monitoring')
@section('header_title_sm','Graphical Display')

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
