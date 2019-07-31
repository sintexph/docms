@extends('layouts.app-top-nav')

@section('title','Document Management System')




@section('header_title','Welcome')
@section('header_title_sm','to document management system')




@section('breadcrumbs')
<li><a href="/"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
@stop


@section('top_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/datatables/datatables.min.css">
@stop


@section('content')

<div class="row">
    <div class="col-xs-4">
        <div class="box box-solid">
            <div class="box-header with-border">

                <form method="get" action="{{ route('home') }}">

                    <div class="form-group">
                        <label class="control-label">Find Document</label>
                        <div class="input-group">


                            <input type="text" id="search-input" name="search" value="{{ app('request')->input('search') }}" class="form-control"
                                placeholder="Find Document..." {{ $url_search!==false?'autofocus':'' }}>
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-folder" aria-hidden="true"></i> Systems</h3>
            </div>


            <div class="box-body home-box-maxh-600">

                <ul class="document-list tree" data-widget="tree">
                    @foreach($systems as $system)
                    <li class="treeview {{ app('request')->input('system')==$system->id?'active menu-open':'' }}">
                        <a href="#">
                            <i class="fa fa-folder-o" aria-hidden="true"></i> <span>{{ $system->name }}</span>
                        </a>
                        <ul class="treeview-menu" style="{{ app('request')->input('system')==$system->id?'display: block;':'' }}">
                            @foreach($system->sections()->orderBy('name','asc')->get() as $section)
                            <li class="{{ app('request')->input('sec')==$section->id?'active':'' }}">
                                <a href="/?system={{ $system->id }}&sec={{ $section->id }}">
                                    <i class="fa fa-folder-o" aria-hidden="true"></i> {{ $section->name }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xs-8">
        @if($url_system==false && $url_section==false && $url_search==false)
        @include('home.view_src.main')
        @elseif(!empty( $url_search))
        @include('home.view_src.search')
        @else
        @include('home.view_src.documents')
        @endif
    </div>
</div>
@stop


@section('bottom_script')
<script src="http://cdn.sportscity.com.ph/datatables/datatables.min.js"></script>

<script>
    $(document).ready(function () {

        var input = $("#search-input");
        var len = input.val().length;
        input[0].focus();
        input[0].setSelectionRange(len, len);

    });

</script>
@stop
