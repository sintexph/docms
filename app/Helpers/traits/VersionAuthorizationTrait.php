<?php

namespace App\Helpers\Traits;
use App\DocumentVersion;
use App\Helpers\DocumentHelper;
use Illuminate\Http\Request;
use App\User;

trait VersionAuthorizationTrait 
{
    protected function version_save_reviewer(DocumentVersion $document_version,Request $request)
    {
        $system=$document_version->document->system;
        $request=(object)$request['version'];

        $reviewer_ids=\array_merge($request->reviewers,$system->reviewer_ids); # merge reviewer ids
        $document_version->reviewers()->whereNotIn('user_id',$reviewer_ids)->delete(); # remove the not exists

        foreach ($reviewer_ids as $reviewer_id) {
            if(!$document_version->reviewers()->where('user_id',$reviewer_id)->exists())
            {
                $reviewer=User::find($reviewer_id);
                abort_if($reviewer==null,404,'Reviewer not could not be found on the system');
                DocumentHelper::save_reviewer($reviewer,$document_version,true);
            }
        }
        
        return $document_version->reviewers;
    }
    
 
    protected function version_save_approver(DocumentVersion $document_version,Request $request)
    {
        $system=$document_version->document->system;
        $request=(object)$request['version'];

        $approver_ids=\array_merge($request->approvers,$system->approver_ids); # merge approver ids
        $document_version->approvers()->whereNotIn('user_id',$approver_ids)->delete(); # remove the not exists

        foreach ($approver_ids as $approver_id) {
            if(!$document_version->approvers()->where('user_id',$approver_id)->exists())
            {
                $approver=User::find($approver_id);
                abort_if($approver==null,404,'Reviewer not could not be found on the system');
                DocumentHelper::save_approver($approver,$document_version,true);
            }
        }
        
        return $document_version->approvers;
    }
    
}
