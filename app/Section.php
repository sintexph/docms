<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use SoftDeletes;

    protected $fillable=[
        'code',
        'name',
        'system_code',
        'created_by',
        'edited_by',
    ];

    public function system()
    {
        return $this->belongsTo('App\System','system_code','code');
    }
}
