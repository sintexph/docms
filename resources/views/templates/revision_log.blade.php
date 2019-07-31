<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Revision Logs</title>
 <style>
        body { 
            font-family: 'Arial'; 
            font-size:12px;
        }
        .table,
        .table td,
        .table th {
            border: 1px solid #2b2b2b;
            text-align: left;
        }

        .table {
            border-collapse: collapse;
            width: 100%;
        }

        .table th,
        .table td {
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

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .watermark {
            position: fixed;
            bottom: 0px;
            color: #d12f2fc4;
            font-size: 13px;
            margin-left: 35%;
            /** Your watermark should be behind every content**/
            z-index: -1000;
        }

        /*Imported from the source of document_content.css*/
        table.ordered-list tr td.ol-label,
        table.ordered-list tr td.ol-content {
            vertical-align: top;
            text-align: left;
            border: 0px;
        }

    </style>
</head>

<body> 
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>Revision Level</th>
                <th>Effectivity Date</th>
                <th>Description of Change</th>
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
                <td>{!! $version_log->castedDescription()->toString() !!}</td>

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

</body>

</html>
