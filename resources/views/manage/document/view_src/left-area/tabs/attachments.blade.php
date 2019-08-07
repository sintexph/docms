
<doc-ver-attachments :attachments="{{ json_encode($attachments) }}"></doc-ver-attachments>


@if(auth()->user()->can('initiate_action',$document))
    <upload-file version_id="{{ $selected_version->id }}"></upload-file>
@endif
