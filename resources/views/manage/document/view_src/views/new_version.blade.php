<new-version 
document_id="{{ $document->id }}" 
document_system="{{ $document->system_code }}"
:current_version_content="{{ json_encode($selected_version->content) }}" >
</new-version>
