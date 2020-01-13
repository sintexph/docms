<reference-documents  
:document="{{ json_encode($document) }}"
:references="{{ json_encode($references) }}"
:can_initiate="{{ auth()->user()->can('initiate_action',$document)==true?'true':'false' }}"
>
</reference-documents>