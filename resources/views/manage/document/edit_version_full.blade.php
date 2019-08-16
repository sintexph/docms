@sintexlayouttop

@slot('page_title')
{{ $document->document_number }} | {{ $document->title }} - Edit
@endslot

@slot('content')

<div id="app-document">
    @if($selected_version->reviewed==false && $selected_version->approved==false)
    <div class="pull-right">
    <a href="{{ route('manage.documents.view',$document->id) }}" class="btn btn-xs btn-danger"><i class="fa fa-window-close" aria-hidden="true"></i>&nbsp;&nbsp;Exit Workspace</a>
</div>
 

    <edit-version 
        document_id="{{ $document->id }}"
        document_type="{{ $selected_version->document_type }}"
        :description="{{ json_encode($selected_version->description) }}"
        effective_date="{{ $selected_version->effective_date }}" expiry_date="{{ $selected_version->expiry_date }}"
        :content="{{ json_encode($selected_version->content) }}"
        :reviewers="{{ $selected_version->reviewers->map(function($user_r){return $user_r->user->id;}) }}"
        :approvers="{{ $selected_version->approvers->map(function($user_r){return $user_r->user->id;}) }}"
        version_id="{{ $selected_version->id }}"
        :for_approval="{{ $selected_version->for_approval==true?'true':'false' }}"
        :for_review="{{ $selected_version->for_review==true?'true':'false' }}">
    </edit-version>
    @endif
</div>
@endslot


@slot('start_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/loading-modal/css/jquery.loadingModal.min.css">
<link rel="stylesheet" href="{{ asset('css/document.css') }}">
<link rel="stylesheet" href="/css/timeline.css">
<link rel="stylesheet"
    href="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.css">
<script src='http://cdn.sportscity.com.ph/tinymce/tinymce.min.js'></script>

@endslot

@slot('end_script')

<script src="http://cdn.sportscity.com.ph/idle-timeout.js/idle-timeout.min.js"></script>
<script src="http://cdn.sportscity.com.ph/loading-modal/js/jquery.loadingModal.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.3/plugins/input-mask/jquery.inputmask.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.3/plugins/input-mask/jquery.inputmask.date.extensions.js">
</script>
<script src="http://cdn.sportscity.com.ph/bs-tag-input/src/bootstrap-tagsinput.js"></script>
<script src="http://cdn.sportscity.com.ph/AdminLTE-2.4.5/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="{{ asset('js/manage/document.js') }}"></script>


@endslot




@endsintexlayouttop
