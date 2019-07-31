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
    ];

    public function sections()
    {
        return $this->hasMany('App\Section','system_code','code');
    }
}
