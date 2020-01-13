<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{ $document->title }}</title>
    <style>
        * {
            font-family: Arial;
            src: url("/fonts/ARIAL.TTF") format("truetype");
        }

        .table {
            border-collapse: collapse;
            width: 100%;
        }

        .table td,
        .table th {
            border: 1px solid #ddd;
            padding: 8px;
        }


        .table tr:hover {
            background-color: #ddd;
        }

        .table th {

            white-space: nowrap;
            text-align: left;

        }

        .table .nowrap {
            white-space: nowrap;
        }

    </style>

</head>

<body>

    <table class="table">
        <tbody>

            <tr>
                <th>
                    <center>
                        <h1>
                            <span style="font-size:30px;  font-style:italic; color:red;">SCI</span><br>
                            PHILIPPINES
                        </h1>
                    </center>
                </th>
                <th>
                    <center>
                        <h1 style="font-size:20px;">
                            @if($document->system!=null)
                            {{ $document->system->name }}
                            @else
                            {{ $document->system_code }}
                            @endif
                        </h1>
                    </center>
                </th>
                <th colspan="4">
                    <center>
                        <h1 style="font-size:20px;">
                            @if($document->category!=null)
                            {{ $document->category->name }}
                            @else
                            {{ $document->category }}
                            @endif
                        </h1>
                    </center>
                </th>
            </tr>
            <tr>
                <th>Document No.</th>
                <td class="nowrap">{{ $document->document_number }}</td>
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

    <h3>Revision Logs</h3>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Revision Level</th>
                <th>Effectivity Date</th>
                <th>Description</th>
                <th>Change Initiator</th>
                <th>Reviewer(s)</th>
                <th>Approver(s)</th>
            </tr>
        </thead>
        <tbody>

            @foreach($document->versions as $version_log)

            <tr>
                <td>{{ $version_log->version }}</td>
                <td>{{ $version_log->effective_date }}</td>
                <td>{!! $version_log->description !!}</td>

                <td>{!! $version_log->creator->name !!}</td>



                <td>
                    @foreach($version_log->reviewers as $version_log_reviewers)
                    {{ $version_log_reviewers->user->name }}<br>
                    @endforeach
                </td>

                <td>
                    @foreach($version_log->approvers as $version_log_approver)
                    {{ $version_log_approver->user->name }}<br>
                    @endforeach
                </td>
            </tr>

            @endforeach
        </tbody>
    </table>

    <h3>Document Content</h3>
    <div style="width:100%">
        {!! $current_version->content !!}
    </div>
</body>

</html>
