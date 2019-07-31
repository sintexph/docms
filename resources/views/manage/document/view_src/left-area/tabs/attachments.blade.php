
<doc-attachments :attachments="{{ json_encode($attachments) }}"></doc-attachments>


@if(auth()->user()->can('initiate_action',$document))
    <upload-file document_id="{{ $document->id }}"></upload-file>
@endif
