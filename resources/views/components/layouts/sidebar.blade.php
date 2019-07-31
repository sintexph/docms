<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $page_title }}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/jvectormap/jquery-jvectormap.css">

    {{ $start_script }}

    <link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="http://cdn.sportscity.com.ph/fonts/google.css">
</head>

<body class="hold-transition skin-black sidebar-mini">
    <div class="wrapper">

        <header class="main-header">

            <a href="/" class="logo">
            {!! $nav_brand !!}
            </a>
            <nav class="navbar navbar-static-top">
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        {{ $navigation }}
                    </ul>
                </div>

            </nav>
        </header>
        <aside class="main-sidebar">
            <section class="sidebar">
                {!! $sidebar !!}
            </section>

        </aside>



        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="breadcrumb-container">
                <ol class="breadcrumb">

                    {{ $breadcrumbs }}

                </ol>
            </div>
            <section class="content-header">
                <h1>{{ $header_title }}<small>{{ $header_title_sm }}</small></h1>

            </section>

            <section class="content">
                {{ $content }}
            </section>

        </div>

        <footer class="main-footer">

        {{$footer}}
        </footer>

        <div class="control-sidebar-bg"></div>

    </div>

    <script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/fastclick/lib/fastclick.js"></script>
    <script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/dist/js/adminlte.min.js"></script>
    <script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
    <script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    </script>
    {{ $end_script }}
</body>

</html>
