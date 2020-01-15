@if($selected_version->reviewed==false || $selected_version->approved==false)
<div class="pull-right">
    <a href="{{ route('manage.documents.edit_current_version',$document->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-television" aria-hidden="true"></i> Edit in large workspace</a>
</div>
 
    <edit-version 
        document_id="{{ $document->id }}"
        document_type="{{ $selected_version->document_type }}"
        :description="{{ json_encode($selected_version->description) }}"
        effective_date="{{ $selected_version->effective_date }}"
        expiry_date="{{ $selected_version->expiry_date }}"
        :content="{{ json_encode($selected_version->content) }}"
        :reviewers="{{ $selected_version->reviewers->map(function($user_r){return $user_r->user->id;}) }}"
        :approvers="{{ $selected_version->approvers->map(function($user_r){return $user_r->user->id;}) }}"
        version_id="{{ $selected_version->id }}"
    >
    </edit-version>
@endif