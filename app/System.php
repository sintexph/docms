<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class System extends Model
{
    use SoftDeletes;

    protected $fillable=[
        'code',
        'name',
        'created_by',
        'edited_by',
        'approver_ids',
        'reviewer_ids',
    ];

    protected $casts=[
        'approver_ids'=>'array',
        'reviewer_ids'=>'array',
    ];

    protected $appends=[
        'approver_names',
        'reviewer_names',
    ];
    

    public function sections()
    {
        return $this->hasMany('App\Section','system_code','code');
    }

    public function getApproverNamesAttribute()
    {
        if(!$this->approver_ids)
            return [];
        else
            return \App\User::whereIn('id',$this->approver_ids)->select(['name'])->get()->toArray();// $this->hasMany('App\User','id',$this->approver_ids);
    }
    public function getReviewerNamesAttribute()
    {
        if(!$this->reviewer_ids)
            return [];
        else
            return \App\User::whereIn('id',$this->reviewer_ids)->select(['name'])->get()->toArray();// $this->hasMany('App\User','id',$this->approver_ids);
    }
}
