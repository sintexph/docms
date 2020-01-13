<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    protected $fillable=[
        'document_id',
        'created_by',
        'reference',
    ];

    public function document()
    {
        return $this->belongsTo('App\Document','document_id');
    }

}
