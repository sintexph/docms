@if(auth()->user()->can('status',$document))
<change-status document_id="{{ $document->id }}"></change-status>
@endif