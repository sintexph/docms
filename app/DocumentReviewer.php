<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentReviewer extends Model
{
    protected $fillable=[
        'user_id', 
        'version_id', 
        'reviewed', 
        'reviewed_at',
        'submitted',
    ];

    protected $casts=[
        'reviewed'=>'boolean',
        'submitted'=>'boolean',
    ];
    protected $dates=[
        'reviewed_at',
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
