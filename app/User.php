<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'position',
        'username',
        'created_by',
        'edited_by',
        'perm_administrator',
        'perm_reviewer',
        'perm_approver'
    ];

    protected $casts=[
        'perm_administrator'=>'boolean',
        'perm_reviewer'=>'boolean',
        'perm_approver'=>'boolean',
        'notify_changes'=>'boolean',
        'notify_followups'=>'boolean',
        'notify_comments'=>'boolean',
        'notify_reviewed'=>'boolean',
        'notify_approved'=>'boolean',
        'notify_to_approve'=>'boolean',
        'notify_to_review'=>'boolean',
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getNameAttribute($val)
    {
        return ucwords(strtolower($val));
    }
    public function getPositionAttribute($val)
    {
        return ucwords(strtolower($val));
    }
}
