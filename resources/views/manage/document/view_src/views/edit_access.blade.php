<edit-access 
    document_id="{{ $document->id }}" 
    current_access="{{ $document->access }}"
    :current_accessors="{{ json_encode($document->accessors()->with('user')->get()) }}"
    :current_moderators="{{ json_encode($document->moderators()->with('user')->get()) }}"
    >
</edit-access>