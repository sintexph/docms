<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentTitle extends Model
{
    use SoftDeletes;

    protected $fillable=[
        'code',
        'name',
        'created_by',
        'edited_by',
    ];
}
