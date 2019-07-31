<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{ $document->title }}</title>
    <style>
        @font-face {
            font-family: 'Arial';
            font-style: normal;
            font-weight: normal;
            @if(app('request')->input('download')=="1") 
            src: url("{{ storage_path('fonts/arial.ttf') }}") 
            @endif
        }

        @font-face {
            font-family: 'Arial';
            font-style: normal;
            font-weight: bold;
            @if(app('request')->input('download')=="1") 
            src: url("{{ storage_path('fonts/arialbd.ttf') }}");
            @endif
        }



        body {
            font-family: 'Arial';
            font-size: 16px;
        }

        .header {
            font-size: 20px;
        }


        .tbl,
        .tbl td,
        .tbl th {
            border: 1px solid #2b2b2b;
            text-align: left;
        }

        .tbl {
            border-collapse: collapse;
            width: 100%;
        }

        .tbl th,
        .tbl td {
            padding: 5px;
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



        .com-img-center,
        .com-img-left,
        .com-img-right {
            padding: 20px;
        }




        .com-img-left {
            left: 0;
        }


        .com-img-right {
            position: absolute;
            right: 0;
            width: 50%;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

    </style>

</head>

<body>

    <table class="tbl">
        <tbody>

            <tr>
                <th>
                    <center>
                        <img style="width:110px;" src="http://cdn.sportscity.com.ph/images/sci_logo_2.png"><br>
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
                <th>Document Title</th>
                <td>{{ $document->title }}</td>
                <th colspan="3">Document No.</th>
                <td>{{ $document->document_number }}</td>
            </tr>
            <tr>
                <th>Effectivity Date</th>
                <td>
                    @if($current_version->effective_date!=null)
                    <span>
                        {{$current_version->effective_date_formatted}}
                    </span>
                    @else
                    <span>---</span>
                    @endif
                </td>
                <th colspan="3">Review Date</th>
                <td></td>
            </tr>


        </tbody>
    </table>
    <!--
    <table class="tbl">
        <tbody>

            <tr>
                <th>
                    <center>
                        <span class="header">
                            <span style="font-size:30px;  font-style:italic; color:red;">SCI</span><br>
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
                        <small>{{ $current_version->updated_at }}</small></a>
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
    -->

    <h3>REVISION LOGS</h3>
    <table class="tbl">
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
                <td>

                    @if($version_log->effective_date!=null)
                    <span>
                        {{$version_log->effective_date_formatted}}
                    </span>
                    @else
                    <span>---</span>
                    @endif

                </td>
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

    <br>
    @foreach($current_version->castedContent() as $content)
    <div class="policy-content">
        <strong>{{ $content->name }}:</strong><br>
        <div class="items">

            @foreach($content->items as $item)


            @switch($item->type)

            @case('list')
            {!! $item->data->htmlRepresentation() !!}
            @break

            @case('paragraph')
            {!! $item->data->meta['html'] !!}
            @break

            @case('table')

            @component('templates.content_types.table')
            @slot('header',$item->data->header)
            @slot('rows',$item->data->rows)
            @endcomponent
            @break



            @case('image')

            @if($item->data->meta['position']=='center')
            <center>
                <img class="{{ $item->data->meta['class'] }}" style="{{ $item->data->meta['style'] }}"
                    src="{{ $item->data->link }}">
            </center>
            @else
            <img class="{{ $item->data->meta['class'] }}" style="{{ $item->data->meta['style'] }}"
                src="{{ $item->data->link }}">
            @endif
            <div class="clearfix"></div>
            @break


            @endswitch


            @endforeach

        </div>

    </div>
    @endforeach

</body>

</html>
