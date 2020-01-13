<?php

namespace App\Helpers;
use App\DocumentVersion;
use App\DocumentReviewer;
use App\DocumentApprover;
use App\Helpers\MailHelper;

class DocumentVersionHelper 
{
    public static function review(DocumentReviewer $reviewer)
    {
        $reviewer->reviewed=true;
        $reviewer->reviewed_at=\Carbon\Carbon::now();
        $reviewer->save();

        # Send email to creator that the document is reviewed by reviewer
        MailHelper::send_email_reviewed_creator($reviewer);

        static::update_review_status($reviewer->document_version);

        return $reviewer;
    }
    public static function update_review_status(DocumentVersion $document_version)
    {
        $reviewed=$document_version->reviewers->where('reviewed','=',true)->count();
        $total_reviewers=$document_version->reviewers->count();

        if($reviewed==$total_reviewers && $total_reviewers!=0)
        {
            # If all reviewers has been reviewed the document version, set it to reviewed
            $document_version->reviewed=true;
            $document_version->save();

           
            # Send email notification to the approvers
            foreach ($document_version->approvers as $approver) {
                MailHelper::send_email_approver($approver);
            }
           
        }

        return $document_version;
    }


    public static function approve(DocumentApprover $approver)
    {
        $approver->approved=true;
        $approver->approved_at=\Carbon\Carbon::now();
        $approver->save();

        static::update_approve_status($approver->document_version);

        return $approver;

    }


    public static function update_approve_status(DocumentVersion $document_version)
    {
        $approved=$document_version->approvers->where('approved','=',true)->count();
        $total_approvers=$document_version->approvers->count();

      
        if($approved==$total_approvers && $total_approvers!=0)
        {
            $document_version->approved=true;
            $document_version->save();

            MailHelper::send_email_approved_creator($document_version);
        }

        return $document_version;
    }

    /*
    public static function update_test()
    {
        $versions=\App\DocumentVersion::all();
        foreach ($versions as $version) {

            static::update_review_status($version);
            static::update_approve_status($version);
        }
    }
    */
}
