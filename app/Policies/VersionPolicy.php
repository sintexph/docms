<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\DocumentVersion;


class VersionPolicy
{
    use HandlesAuthorization;
    
 
    public function review(User $user,DocumentVersion $version)
    {
        return $version->reviewers()->where('user_id','=',$user->id)->exists();
    }

    public function approve(User $user,DocumentVersion $version)
    {
        return $version->reviewers()->where('user_id','=',$user->id)->exists();
    }
    
}
