<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentVersionAttachment extends Model
{
    protected $fillable=[
        'version_id', 
        'file_id',
    ];

    public function upload()
    {
        return $this->belongsTo('App\Upload','file_id');
    }

    public function version()
    {
        return $this->belongsTo('App\DocumentVersion','version_id');
    }
}
