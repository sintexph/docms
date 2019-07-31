<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentAttachment extends Model
{
    protected $fillable=[
        'document_id', 
        'file_id',
    ];

    public function upload()
    {
        return $this->belongsTo('App\Upload','file_id');
    }

    public function document()
    {
        return $this->belongsTo('App\Document','document_id');
    }
}
