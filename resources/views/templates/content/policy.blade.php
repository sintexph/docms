@extends('templates.content.app')

@section('content')


<table class="table">
    <tbody>

        <tr>
            <th>
                <center>
                    <img style="width:110px;"
                        src="data:image/png;base64, {{ base64_encode(file_get_contents('http://cdn.sportscity.com.ph/images/sci_logo_2.png')) }}">
                    <br>
                    <span class="header">
                        PHILIPPINES
                    </span>
                </center>
            </th>
            <th>
                <center>
                    <span class="header">
                        @if($document->system!=null)
                        {{ $document->system->name }}
                        @else
                        {{ $document->system_code }}
                        @endif
                    </span>
                </center>
            </th>
            <th colspan="4">
                <center>
                    <span class="header">
                        @if($document->category!=null)
                        {{ $document->category->name }}
                        @else
                        {{ $document->category }}
                        @endif
                    </span>
                </center>
            </th>
        </tr>
        <tr>
            <th class="fit">Document Title</th>
            <td>{{ $document->title }}</td>
            <th class="fit" colspan="3">Document No.</th>
            <td>{{ $document->document_number }}</td>
        </tr>
        <tr>
            <th class="fit">Effectivity Date</th>
            <td>
                @if($document_version->effective_date!=null)
                <span>
                    {{$document_version->effective_date_formatted}}
                </span>
                @else
                <span>---</span>
                @endif

                @if($document_version->expiry_date!=null)
                <span>
                    to {{$document_version->expiry_date_formatted}}
                </span>
                @endif
            </td>
            <th class="fit" colspan="3">Version</th>
            <td>{{ $document_version->version }}</td>
        </tr>

        <tr>
            <th class="fit">Prepared By</th>
            <td>
                {{ $document_version->creator->name }}
            </td>
            <th class="fit" colspan="3">Revision Date</th>
            <td>{{ $document_version->creator_modified_at->format('F d, Y') }}</td>
        </tr>
        <tr>
            <th class="fit">
                <span>Reviewed By</span>
            </th>
            <td colspan="5">
                @foreach($document_version->reviewers as $reviewer)
                <p>
                    {{ $reviewer->user->name }}
                    @if($reviewer->reviewed)
                    <span>&nbsp;-&nbsp;{{ $reviewer->reviewed_at->format('F d, Y') }}</span>
                    @endif
                    <br>
                    <strong>{{ $reviewer->user->position }}</strong>
                </p>
                @endforeach
            </td>
        </tr>

        <tr>
            <th class="fit">
                <span>Approver<small>(s)</small></span>
            </th>
            <td colspan="5">
                @foreach($document_version->approvers as $approver)
                <p>
                    {{ $approver->user->name }}
                    <span>&nbsp;</span>
                    @if($approver->approved)
                    <a title="Reviewed" class="text-green">-
                        <small>{{ $approver->approved_at->format('F d, Y') }}</small></a>
                    @endif
                    <br>
                    <strong>{{ $approver->user->position }}</strong>
                </p>
                @endforeach
            </td>
        </tr>

    </tbody>
</table>



<br>
{!! $document_version->castedContent()->toString() !!}

@if(!isset($download) || $download==false)
<br>
<h3>Revision Logs</h3>
<iframe scrolling="no" onload="resizeIframe(this)" frameborder="0" src="/content/view/revision-logs/{{ $document->id }}"
    width="100%"></iframe>
@endif
@endsection
