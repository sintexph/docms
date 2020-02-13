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
use App\Mail\AccountRegistrationMailable;
use App\Mail\AccountApprovalMailable;
use App\Mail\AccountActivatedMailable;
use App\Mail\AccountDeactivatedMailable;

use App\Helpers\DocumentUserRole;
use App\DocumentReviewer;
use App\DocumentApprover;
use App\DocumentVersion;
use Mail;
use App\User;

class MailHelper 
{
    public static function account_activated(User $registered_user)
    {
        Mail::to($registered_user->email)->send(new AccountActivatedMailable($registered_user));
    }
    public static function account_deactivated(User $registered_user)
    {
        Mail::to($registered_user->email)->send(new AccountDeactivatedMailable($registered_user));
    }

    public static function account_approval(User $registered_user)
    {
        foreach (User::where('perm_administrator',true)->get() as $user) {
            Mail::to($user->email)->send(new AccountApprovalMailable($user,$registered_user));
        }
    }

    public static function user_registered(User $registered_user)
    {
        Mail::to($registered_user->email)->send(new AccountRegistrationMailable($registered_user));
    }

    public static function followup_reviewer(DocumentReviewer $document_reviewer)
    {
        $user=$document_reviewer->user;
        if($user->notify_followups==true)
        {
            Mail::to($user->email)->send(new FollowupReviewerMailable($document_reviewer));
        }
    }

    public static function followup_approver(DocumentApprover $document_approver)
    {
        $user=$document_approver->user;
        if($user->notify_followups==true)
        {
            Mail::to($user->email)->send(new FollowupApproverMailable($document_approver));
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
            Mail::to($user->email)->send(new VersionChangeApproverMailable($document_approver));
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
            Mail::to($user->email)->send(new VersionChangeReviewerMailable($document_reviewer));
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
            Mail::to($user->email)->send(new ReviewingMailable($document_reviewer));
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
            Mail::to($user->email)->send(new ApprovingMailable($document_approver));
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
                Mail::to($user->email)->send(new ApprovedMailable($user,$document_version));
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
            Mail::to($user->email)->send(new DocumentReviewedMailable($user,$reviewer));
    }
    
    /**
     * Send email to the users who commented the document version
     */
    public static function send_email_to_comments(DocumentVersion $document_version,User $commenter)
    {
        # Get the users who commented the document version
        $user_ids=$document_version->comments()->select(['created_by'])->groupBy('created_by')->get()->map(function($u){ return $u->created_by; });

        # Get approvers and reviewers
        $reviewer_ids=$document_version->reviewers->map(function($u){ return $u->user_id; });
        $approver_ids=$document_version->approvers->map(function($u){ return $u->user_id; });

        # Merge arrays
        $user_ids=\array_merge($reviewer_ids->toArray(),$approver_ids->toArray(),$user_ids->toArray());

        # Push the creator so it can receive also a notification
        $user_ids[]=$document_version->created_by;

        # Find the users based on the use ids fetched
        $users=User::whereIn('id',$user_ids)->get();

        # Send the notification to the users
        foreach ($users as $user) {
            if(!empty($user->email))
            {
                if($user->notify_comments==true)
                {
                    # Set different urls based on the role of the user in the document
                    $url=null;
                    switch (DocumentUserRole::checkRole($document_version,$user)) {
                        case DocumentUserRole::CREATOR:
                            $url=route('manage.documents.view',$document_version->document->id);
                            break; 
                        case DocumentUserRole::REVIEWER:
                            $document_reviewer=$document_version->reviewers()->where('user_id',$user->id)->first();
                            $url=route('for_review.view',$document_reviewer->id);
                            break; 
                        case DocumentUserRole::APPROVER:
                            $document_approver=$document_version->approvers()->where('user_id',$user->id)->first();
                            $url=route('for_approval.view',$document_approver->id);
                            break; 
                    }

                    Mail::to($user->email)->send(new CommentMailable($document_version,$commenter,$url)); 
                }
            }   
        }
    }
}
