<?php

namespace App\Helpers;
use App\DocumentVersion;
use App\User;

class DocumentUserRole 
{
    const NONE=0;
    const CREATOR=1;
    const REVIEWER=2;
    const APPROVER=3;

    public static function checkRole(DocumentVersion $document_version,User $user)
    {
        if($user->id==$document_version->document->created_by)
            return static::CREATOR;
        elseif ($document_version->reviewers()->where('user_id',$user->id)->exists())
            return static::REVIEWER;
        elseif ($document_version->approvers()->where('user_id',$user->id)->exists())
            return static::APPROVER;
        else
            return static::NONE;
    }

}
