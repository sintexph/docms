<document-edit 

 
    document_id="{{ $document->id }}"
    created_by="{{ $document->created_by }}"
    title="{{ $document->title }}"
    section="{{ $document->section_code }}"
    system="{{ $document->system_code }}"
    category="{{ $document->category_code }}"
    serial="{{ $document->serial }}"
    keywords="{{ $document->implode_keywords }}"
    comment="{{ $document->comment }}"

></document-edit>

 