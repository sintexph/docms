<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentApprover extends Model
{
    protected $fillable=[
        'user_id', 
        'version_id', 
        'approved', 
        'approved_at',
    ];

    protected $casts=[
        'approved'=>'boolean'
    ];
    protected $dates=[
        'approved_at',
    ];
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
    public function document_version()
    {
        return $this->belongsTo('App\DocumentVersion','version_id');
    }
}
