<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentAccessor extends Model
{
    protected $fillable=[
        'document_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
    public function document()
    {
        return $this->belongsTo('App\Document','document_id');
    }
}
