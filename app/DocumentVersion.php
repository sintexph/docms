<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\DocumentContent\Util\Cast;

class DocumentVersion extends Model
{
    protected $fillable=[
        'document_id', 
        'version', 
        'content', 
        'document_type', 
        'created_by', 
        'description', 
        'reviewed', 
        'approved', 
        'released', 
        'released_date',
        'active',
        'obsolete',
        'effective_date',
        'expiry_date',
        'for_approval',
        'for_review',
    ];

    

    protected $dates=[
        'released_date',
        'effective_date',
        'expiry_date',
        'created_at',
    ];

    protected $casts=[
        'reviewed'=>'boolean',
        'approved'=>'boolean',
        'released'=>'boolean',
        'active'=>'boolean',
        'obsolete'=>'boolean',
        'for_review'=>'boolean',
        'for_approval'=>'boolean',
        'content'=>'array',
        'description'=>'array',
    ];
    
    protected $appends=[ 
        'effective_date_formatted',
        'expiry_date_formatted',
        'description_of_changed'
    ];



    public function attachments()
    {
        return $this->hasMany('App\DocumentVersionAttachment','version_id');
    }


    public function castedContent()
    {
        return Cast::cast_to_content($this->content);
    }
    public function castedDescription()
    {
        return Cast::cast_to_content($this->description);
    }

    public function getDescriptionOfChangedAttribute()
    {
        return $this->castedDescription()->toString();
    }


    public function getEffectiveDateFormattedAttribute()
    {
        $date=$this->effective_date;
        if($date==null)
        return '';
        else
        return $date->format('F d, Y');
    }
    public function getExpiryDateFormattedAttribute($val)
    {
        $date=$this->expiry_date;
        if($date==null)
        return '';
        else
        return $date->format('F d, Y');
    }
    
    public function getCreatedAtAttribute($value)
    {
        $date=new \Carbon\Carbon($value);
        $date=$date->format("M d, Y h:i A");
        return $date;
    }


    public function comments()
    {
        return $this->hasMany('App\DocumentVersionComment','version_id');
    }
    
    public function getVersionAttribute($value)
    {
        return str_pad($value,3,"0",STR_PAD_LEFT);
    }

    public function creator()
    {
        return $this->belongsTo('App\User','created_by');
    }
    
    public function document()
    {
        return $this->belongsTo('App\Document','document_id');
    }

    public function reviewers()
    {
        return $this->hasMany('App\DocumentReviewer','version_id');
    }

    public function approvers()
    {
        return $this->hasMany('App\DocumentApprover','version_id');
    }

    public function revision()
    {
        return $this->hasOne('App\DocumentVersionRevision','version_id');
    }

    public function last_version()
    {
        $last_version=DocumentVersion::where('document_id','=',$this->document_id)->where('version','<',$this->version)->first();
        return $last_version;
    }

}

