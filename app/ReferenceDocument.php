<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReferenceDocument extends Model
{
    protected $fillable=[
        'public',
        'document_id',
        'created_by',
        'reference_document_id',
    ];

    protected $casts=[
        'public'=>'Boolean'
    ];

    public function document()
    {
        return $this->belongsTo('App\Document','document_id');
    }

    public function referred_document()
    {
        return $this->belongsTo('App\Document','reference_document_id');
    }

}
