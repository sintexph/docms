<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title>{{ $document->title }}</title>
    @if(isset($download) && $download==true)
    <!--Change fonts if the content is for download-->
    <style>
        body {
            padding: 10px;
            font-family: 'Arial';
            font-size: 17px;
        }

        .header {
            font-size: 19px;
        }

    </style>
    @else
    <style>
        body {
            font-family: 'Arial';
            font-size: 12px;
        }

        .header {
            font-size: 15px;
        }

    </style>
    @endif
    <style>
        .table,
        .table td,
        .table th {
            border: 1px solid #2b2b2b;
            text-align: left;
        }


        .table td.fit,
        .table th.fit {
            white-space: nowrap;
            width: 1%;
        }

        .table {
            border-collapse: collapse;
            width: 100%;
        }

        .table th,
        .table td {
            padding: 10px;
        }



        .policy-content .items {
            padding-left: 10px;
        }


        ol {
            padding: 0px 0px 0px 12px;
            counter-reset: item;
            text-align: justify;

        }

        li {
            display: block
        }

        li:before {
            content: counters(item, ".") " ";
            counter-increment: item
        }


        /* width */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #bdbdbd;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: rgb(95, 95, 95);
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #464d52;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .watermark {
            position: fixed;
            bottom: 0px;
            color: #ff7d7d;
            font-size: 15px;
            margin-left: 35%;
            /** Your watermark should be behind every content**/
            z-index: -1000;
        }

        /*Imported from the source of document_content.css*/
        table.ordered-list tr td.ol-label,
        table.ordered-list tr td.ol-content {
            vertical-align: top;
            text-align: left;
        }

        table.ordered-list {
            border-collapse: collapse;
        }

        table.ordered-list tr td.ol-content {
            padding: 0px;
        }

    </style>

</head>

<body>
    @if(isset($download) && $download==true)
    <div class="watermark">
        <strong>
            <center>This document is uncontrolled if printed</center>
        </strong>
    </div>
    @endif
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
                <td>{{ $document_version->updated_at->format('F d, Y') }}</td>
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
</body>

</html>
