@if(auth()->user()->can('archive',$document))
<document-archive document_id="{{ $document->id }}" :archived="{{ $document->archived==true?'true':'false' }}"></document-archive>
@endif