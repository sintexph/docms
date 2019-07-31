<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentActionHistory extends Model
{
    protected $fillable=[
        'document_id', 
        'created_by', 
        'content', 
    ];
}
