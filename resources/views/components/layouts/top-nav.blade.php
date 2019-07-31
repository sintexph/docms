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
    {{ $start_script }}
    <link rel="stylesheet" href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="http://cdn.sportscity.com.ph/css/logo.css">
    <link rel="stylesheet" href="http://cdn.sportscity.com.ph/fonts/google.css">
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->

<body class="hold-transition skin-black layout-top-nav">
    <div class="wrapper">

        <header class="main-header">
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">

                        {!! $nav_brand !!}
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>
                    {{ $navigation }}
                </div>
            </nav>
        </header>
        <div class="breadcrumb-container">
            <div class="container">
                <ol class="breadcrumb">
                    {{ $breadcrumbs }}
                </ol>
            </div>
        </div>
        <div class="content-wrapper">
            <div class="container">
                <section class="content-header">
                    <h1>{{ $header_title }}<small>{{ $header_title_sm }}</small></h1>

                    {{ $header_tools }}


                </section>
                <section class="content">
                    {{ $content }}
                </section>
            </div>
        </div>
        <footer class="main-footer">
            <div class="container">
                {{$footer}}
            </div>
        </footer>
    </div>
    <script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/fastclick/lib/fastclick.js"></script>
    <script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/dist/js/adminlte.min.js"></script>
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
