<edit-access 
    document_id="{{ $document->id }}" 
    current_access="{{ $document->access }}"
    :current_accessors="{{ json_encode($document->accessors()->with('user')->get()) }}"
    >
</edit-access>