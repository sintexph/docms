<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentDraft extends Model
{
    protected $fillable=[
        'user_id', 
        'document_title', 
        'document_system_code', 
        'document_section_code', 
        'document_category_code',
        'document_comment', 
        'document_keywords', 

        'version_content', 
        'version_description', 
        'version_effective_date', 
        'version_approver_ids', 
        'version_reviewer_ids', 
        'revision_has_content',
        'version_for_approval',
        'version_for_review',


    ];

    protected $casts=[
        'version_reviewer_ids'=>'array',
        'version_approver_ids'=>'array',
        'version_for_review'=>'boolean',
        'version_for_approval'=>'boolean',
        'version_content'=>'array',
        'version_description'=>'array',
    ];


}
