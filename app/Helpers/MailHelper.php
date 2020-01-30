<?php

namespace App\Helpers;
use App\Mail\ReviewingMailable;
use App\Mail\ApprovingMailable;
use App\Mail\ApprovedMailable;
use App\Mail\CommentMailable;
use App\Mail\DocumentReviewedMailable;
use App\Mail\VersionChangeReviewerMailable;
use App\Mail\VersionChangeApproverMailable;
use App\Mail\FollowupApproverMailable;
use App\Mail\FollowupReviewerMailable;

use App\DocumentReviewer;
use App\DocumentApprover;
use App\DocumentVersion;
use Mail;
use App\User;

class MailHelper 
{

    public static function followup_reviewer(DocumentReviewer $document_reviewer)
    {
        $user=$document_reviewer->user;
        if($user->notify_followups==true)
        {
            Mail::to($user->email)->queue(new FollowupReviewerMailable($document_reviewer));
        }
    }

    public static function followup_approver(DocumentApprover $document_approver)
    {
        $user=$document_approver->user;
        if($user->notify_followups==true)
        {
            Mail::to($user->email)->queue(new FollowupApproverMailable($document_approver));
        }
    }

    /**
     * Send email to approver that the document version was changed
     * @param $document_approver The instance model of the document approver
     */
    public static function document_version_changed_approver(DocumentApprover $document_approver)
    {
        $user=$document_approver->user;
        if($user->notify_changes==true)
        {
            Mail::to($user->email)->queue(new VersionChangeApproverMailable($document_approver));
        }
    }

    /**
     * Send email to reviewer that the document version was changed
     * @param $document_reviewer The instance model of the document reviewer
     */
    public static function document_version_changed_reviewer(DocumentReviewer $document_reviewer)
    {
        $user=$document_reviewer->user;

        if($user->notify_changes==true)
        {
            Mail::to($user->email)->queue(new VersionChangeReviewerMailable($document_reviewer));
        }
    }

    /**
     * Send email to reviewer and handles the logic for send the email
     * @param $document_reviewer The instance model of the document reviewer
     */
    public static function send_email_reviewer(DocumentReviewer $document_reviewer)
    {
        $user=$document_reviewer->user;

        if($user->notify_to_review==true)
        {
            $version=$document_reviewer->document_version;
            Mail::to($user->email)->queue(new ReviewingMailable($document_reviewer));
        }
    }
    /**
     * Send email to approver and handles the logic for send the email
     * @param $document_approver The instance model of the document approver
     */
    public static function send_email_approver(DocumentApprover $document_approver)
    {
        $user=$document_approver->user;
        if($user->notify_to_approve==true)
        {
            $version=$document_approver->document_version;
            Mail::to($user->email)->queue(new ApprovingMailable($document_approver));
        }
    }

    /**
     * Send email to creator that the document version has been approved and handles the logic for send the email
     */
    public static function send_email_approved_creator(DocumentVersion $document_version)
    {
        $user=$document_version->creator;
        if($user->notify_approved==true)
        {
            # Send only the email to creator it has been approved
            if($document_version->approved==true)
            {
                Mail::to($user->email)->queue(new ApprovedMailable($user,$document_version));
            }
        }

    }
    
    /**
     * Send email to creator that the document version has been reviewed by reviewer and handles the logic for send the email
     */
    public static function send_email_reviewed_creator(DocumentReviewer $reviewer)
    {
        $user=$reviewer->document_version->creator;
        if($user->notify_reviewed==true)
            Mail::to($user->email)->queue(new DocumentReviewedMailable($user,$reviewer));
    }
    
    /**
     * Send email to the users who commented the document version
     */
    public static function send_email_to_comments(DocumentVersion $document_version,User $commenter)
    {
        # Get the users who commented the document version
        $user_ids=$document_version->comments()->select(['created_by'])->groupBy('created_by')->get();
        # Transform to array ids
        $user_ids=$user_ids->map(function($u){ return $u->created_by; });
        # Push the creator so it can receive also a notification
        $user_ids[]=$document_version->created_by;

        # Find the users based on the use ids fetched
        $users=User::find($user_ids);

        # Send the notification to the users
        foreach ($users as $user) {
            if(!empty($user->email))
            {
                if($user->notify_comments==true)
                    Mail::to($user->email)->queue(new CommentMailable($document_version,$commenter)); 
            }   
        }
    }
}
