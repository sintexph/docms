<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Document;
use DocumentAccess;

class DocumentActionPolicy
{
    use HandlesAuthorization;
    
    /**
     * For creator policy
     */
    public function initiate_action(User $user,Document $document)
    {
        if($document->obsolete==true || $document->locked==true || $document->archived==true)
            return false;
        else
            return ($user->perm_administrator==true || $document->created_by==$user->id);
    }
    /**
     * For creator policy
     */
    public function lock(User $user,Document $document)
    {
        if($document->obsolete==true || $document->archived==true)
            return false;
        else
            return $user->perm_administrator;
    }
    public function status(User $user,Document $document)
    {
        if($document->locked==true || $document->archived==true)
            return false;
        else
            return $user->perm_administrator;
    }
    public function archive(User $user,Document $document)
    {
        if($document->locked==true)
            return false;
        else
            return $user->perm_administrator;
    }
    public function view(User $user,Document $document)
    {
        if($user->perm_administrator==true)
            return true;
        elseif($document->access==DocumentAccess::_CONFIDENTIAL || $document->access==DocumentAccess::_PUBLIC)
            return true;
        elseif($document->access==DocumentAccess::_ONLY_ME)
            return $document->created_by==$user->id;
        elseif($document->access==DocumentAccess::_CUSTOM)
            return $document->accessors()->where('user_id','=',$user->id)->exists();
    }
}
