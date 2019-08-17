@extends('layouts.app-top-nav')


@section('breadcrumbs')
<li><a href="/">Home</a></li>
<li class="active">{{ $document->title }}</li>
@stop

@section('title',$document->title)


@section('header_title',$document->title.' - Version: '.$document_version->version)
@section('header_title_sm',$document->document_number)


@section('content')


@if($document->obsolete==true)
<div class="alert-custom alert-custom-warning">
    <span>This document is obsolete.</span>
</div>
@endif



<div id="doc-app">
    <div class="box box-solid">
        <div class=" box-header with-border">
            <h3 class="box-title">Document Preview</h3>
            <div class="pull-right">
                <a href="{{ route('content.download',$document->id) }}" title="Download this document"
                    class="btn btn-xs btn-default"><i class="fa fa-download" aria-hidden="true"></i></a>
            </div>
        </div>
        <div class="box-body">
            <iframe src="{{ route('content.view',$document_version->id) }}" width="100%" height="600"></iframe>
            <br>
            <br>
            <table class="table table-striped table-bordered">
                <tbody>
                    <tr>
                        <th><i class="fa fa-tags" aria-hidden="true"></i> Keywords</th>
                        <td>
                            @foreach($document->keywords as $keyword)
                            <strong><a href="#"
                                    title="{{ $keyword }} keyword"><small>#{{ $keyword }}</small>&nbsp;&nbsp;</a></strong>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th><i class="fa fa-paperclip" aria-hidden="true"></i> Attachments</th>
                        <td>
                            @foreach($document_version->attachments as $att)
                            <a href="{{ $att->upload->download_link }}"
                                title="Download {{ $att->upload->file_name }} attachment">
                                <i class="fa fa-download" aria-hidden="true"></i>
                                {{ $att->upload->file_name }}&nbsp;&nbsp;&nbsp;</a>
                            @endforeach
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="box box-solid">
    <div class=" box-header with-border">
        <h3 class="box-title">Revision Logs</h3>
    </div>
    <div class="box-body">
        <iframe src="/content/view/revision-logs/{{ $document->id }}" width="100%"></iframe>
    </div>
</div>
@stop






@section('bottom_script')
<script src="http://cdn.sportscity.com.ph/pdfobject/pdfobject.min.js"></script>
<script src="{{ asset('js/global.js') }}"></script>

@stop
