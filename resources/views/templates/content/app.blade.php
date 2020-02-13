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
            font-family: 'Consolas';
            font-size: 17px;
            margin: 0;
        }



        @page {
            margin: 0;
        }

        html {
            margin: 0
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
            color:#404040;
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


    <script>
        function resizeIframe(obj) {
            obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
        }

    </script>

</head>

<body>


@yield('content')



</body>

</html>
