@if(auth()->user()->can('lock',$document))
<lock-document document_id="{{ $document->id }}" :lock="{{ $document->locked==true?'true':'false' }}"></lock-document>
@endif