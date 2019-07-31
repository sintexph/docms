@if($selected_version->reviewed==false && $selected_version->approved==false)
    <edit-version 
        document_type="{{ $selected_version->document_type }}"
        :description="{{ json_encode($selected_version->description) }}"
        effective_date="{{ $selected_version->effective_date }}"
        expiry_date="{{ $selected_version->expiry_date }}"
        :content="{{ json_encode($selected_version->content) }}"
        :reviewers="{{ $selected_version->reviewers->map(function($user_r){return $user_r->user->id;}) }}"
        :approvers="{{ $selected_version->approvers->map(function($user_r){return $user_r->user->id;}) }}"
        version_id="{{ $selected_version->id }}"
        :for_approval="{{ $selected_version->for_approval==true?'true':'false' }}"
        :for_review="{{ $selected_version->for_review==true?'true':'false' }}"
    >
    </edit-version>
@endif