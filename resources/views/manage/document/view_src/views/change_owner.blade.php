@if(auth()->user()->can('change_owner',$document))
<change-owner
:created_by="{{ $document->created_by }}"
:document_id="{{ $document->id }}"
></change-owner>
@endif