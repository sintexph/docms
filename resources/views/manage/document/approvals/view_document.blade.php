@extends('layouts.app-side-bar')

@section('nav-4','active')


@section('breadcrumbs')
<li><a href="{{ route('for_review') }}">Approvals</a></li>

<li class="active">{{ $document->title }}</li>
@stop

@section('title','Approve Document Versions')

@section('header_title',$document->title.' - Version: '.$document->version)
@section('header_title_sm',$document->document_number)


@section('content')

<div id="app-document">
    @if($document->obsolete==true)
    <div class="alert-custom alert-custom-warning">
        <span>The document that you are viewing is <strong>obsolete</strong>.</span>
    </div>
    @endif

    <div class="box box-solid">
        <div class="box-header">
            <h3 class="box-title">Document Information</h3>
        </div>
        <div class="box-body no-padding">


            <table class="table table-hover table-bordered">
                <tbody>

                    <tr>
                        <th>Document No.</th>
                        <td>{{ $document->document_number }}</td>
                        <th>Title</th>
                        <td colspan="3">{{ $document->title }}</td>

                    </tr>


                    <tr>

                        <th>System</th>
                        @if($document->system!=null)
                        <td>{{ $document->system->name }}</td>
                        @else
                        <td><code>{{ $document->system_code }}</code></td>
                        @endif

                        <th>Section</th>
                        @if($document->section!=null)
                        <td>{{ $document->section->name }}</td>
                        @else
                        <td><code>{{ $document->section_code }}</code></td>
                        @endif

                        <th>Category</th>
                        @if($document->category!=null)
                        <td>{{ $document->category->name }}</td>
                        @else
                        <td><code>{{ $document->category_code }}</code></td>
                        @endif


                    </tr>

                    <tr>
                        <th>Version</th>
                        <td>{{ $current_version->version }}</td>
                        <th>Description</th>
                        <td colspan="3">
                            {!! $current_version->description_html !!}
                        </td>
                    </tr>

                    <tr>
                        <th>Effective Date</th>
                        <td>
                            @if($current_version->effective_date!=null)
                            <span>
                                {{$current_version->effective_date_formatted}}
                            </span>
                            @else
                            <span>---</span>
                            @endif
                        </td>

                        <th>Expiry Date</th>
                        <td colspan="3">
                            @if($current_version->expiry_date!=null)
                            <span>
                                {{$current_version->expiry_date_formatted}}
                            </span>
                            @else
                            <span>---</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Prepared By</th>
                        <td colspan="5">
                            <u>{{ $current_version->creator->name }}</u>&nbsp;<a title="Reviewed" class="text-green">-
                                <small>{{ $current_version->creator_modified_at }}</small></a>
                            <br>
                            {{ $current_version->creator->position }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <span>Reviewer<small>(s)</small></span>
                        </th>
                        <td colspan="5">
                            @foreach($current_version->reviewers as $reviewer)
                            <p>
                                <u>{{ $reviewer->user->name }}</u>
                                <span>&nbsp;</span>
                                @if($reviewer->reviewed)
                                <a title="Reviewed" class="text-green">- <small>{{ $reviewer->reviewed_at }}</small></a>
                                @endif
                                <br>
                                {{ $reviewer->user->position }}
                            </p>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <span>Approver<small>(s)</small></span>
                        </th>
                        <td colspan="5">
                            @foreach($current_version->approvers as $approver)
                            <p>
                                <u>{{ $approver->user->name }}</u>
                                <span>&nbsp;</span>
                                @if($approver->approved)
                                <a title="Reviewed" class="text-green">- <small>{{ $approver->approved_at }}</small></a>
                                @endif
                                <br>
                                {{ $approver->user->position }}
                            </p>
                            @endforeach
                        </td>
                    </tr>


                </tbody>
            </table>
        </div>
        @if($document_approver->approved==false)
        <div class="box-footer clearfix">
            <button-approve class="pull-right" version_approver_id="{{ $document_approver->id }}"></button-approve>
        </div>
        @endif
    </div>
    <div class="box box-solid">
        <div class=" box-header with-border">
            <h3 class="box-title">Document Preview</h3>
        </div>
        <div class="box-body">
            <iframe src="{{ route('content.view',$current_version->id) }}" width="100%" height="600"></iframe>
        </div>
    </div>
    <div class="box box-solid">
        <div class="box-body">
            <table class="table table-striped table-bordered">
                <tbody>
                    <tr>
                        <th><i class="fa fa-paperclip" aria-hidden="true"></i> Attachments</th>
                        <td>
                            @foreach($current_version->attachments as $att)
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




    <div class="box box-solid">
        <div class=" box-header with-border">
            <h3 class="box-title">Comments</h3>
        </div>
        <div class="box-body">

            <comments version_id="{{ $current_version->id }}"></comments>
        </div>
    </div>
 


</div>

@stop



@section('bottom_script')
<script src="http://cdn.sportscity.com.ph/datatables/datatables.min.js"></script>
<script src="{{ asset('js/manage/for_approval.js') }}"></script>
@stop
