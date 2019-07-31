<reference-documents  
:document="{{ json_encode($document) }}"
:references="{{ json_encode($reference_documents) }}"
:can_initiate="{{ auth()->user()->can('initiate_action',$document)==true?'true':'false' }}"
>
</reference-documents>