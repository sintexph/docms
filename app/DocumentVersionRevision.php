<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentVersionRevision extends Model
{
    protected $fillable=[
        'version_id',
        'content'
    ];
    
}
