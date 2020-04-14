@sintexlayouttop

@slot('page_title')
DMS - @yield('title','Create document')
@endslot



@slot('content')
<div id="app-document">
    <document-create @if($draft!=null) :draft="{{ json_encode($draft) }}" @endif>
    </document-create>
</div>

@endslot


@slot('start_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/loading-modal/css/jquery.loadingModal.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/css/logo.css">
<link rel="stylesheet" href="{{ asset('css/document.css') }}">
<link rel="stylesheet"
    href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.css">
<script src='http://cdn.sportscity.com.ph/tinymce/tinymce.min.js'></script>

<style>
    .fixed .content-wrapper,
    .fixed .right-side {
        padding-top: 0px !important;
    }
</style>
@endslot

@slot('end_script')
<script src="http://cdn.sportscity.com.ph/loading-modal/js/jquery.loadingModal.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.3/plugins/input-mask/jquery.inputmask.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.3/plugins/input-mask/jquery.inputmask.date.extensions.js">
</script>
<script src="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="{{ asset('js/manage/document.js') }}"></script>

@endslot


@slot('footer')

@include('layouts.footer')

@endslot

@endsintexlayouttop
