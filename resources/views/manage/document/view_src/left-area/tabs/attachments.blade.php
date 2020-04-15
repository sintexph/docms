
<doc-ver-attachments :can_delete="{{ ($selected_version->for_review==false && $selected_version->for_approval==false ?'true':'false') }}" :attachments="{{ json_encode($attachments) }}"></doc-ver-attachments>
@if(auth()->user()->can('initiate_action',$document) && $selected_version->for_review==false && $selected_version->for_approval==false)
    <upload-file version_id="{{ $selected_version->id }}"></upload-file>
@endif
