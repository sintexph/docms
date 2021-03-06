<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Access;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    
    protected $fillable=[
        'document_number', 
        'title', 
        'system_code', 
        'section_code', 
        'category_code', 
        'serial', 
        'comment', 
        'keywords', 
        'created_by',
        'version',
        'locked',
        'access',
        'obsolete',
        'archived',
        'archived_by',
        'archived_date',
    ];


    protected $casts=[
        'keywords'=>'array',
        'locked'=>'boolean',
        'access'=>'integer',
        'obsolete'=>'boolean',
        'archived'=>'boolean',
        
    ];

    protected $appends=[
        'implode_keywords',
        'url_title',
        'access_type',
        'access_icon',
    ];

    protected $dates = ['deleted_at'];

    public function getAccessTypeAttribute()
    {
        return Access::text_access($this->access);
    }
    
    public function getAccessIconAttribute()
    {
        return Access::icon_access($this->access);
    }
    
    public function action_histories()
    {
        return $this->hasMany('App\DocumentActionHistory','document_id');
    }
    
    public function getUrlTitleAttribute()
    {
        return \strtolower(\str_replace(" ","-",$this->title));
    }

 
    public function getVersionAttribute($value)
    {
        return str_pad($value,3,"0",STR_PAD_LEFT);
    }
    
    public function getImplodeKeywordsAttribute()
    {
        return implode(',',$this->keywords);
    }

    public function creator()
    {
        return $this->belongsTo('App\User','created_by');
    }

    public function system()
    {
        return $this->belongsTo('App\System','system_code','code');
    }
    public function section()
    {
        return $this->belongsTo('App\Section','section_code','code');
    }
    public function category()
    {
        return $this->belongsTo('App\Category','category_code','code');
    }

    public function current_version()
    {
        return $this->hasOne('App\DocumentVersion','document_id')->where('current','=',true);
    }
    public function active_version()
    {
        return $this->hasOne('App\DocumentVersion','document_id')->where('active','=',true);
    }
    public function versions()
    {
        return $this->hasMany('App\DocumentVersion','document_id');
    }
    public function old_versions()
    {
        return $this->hasMany('App\DocumentVersion','document_id')->where('current','<>',true);
    }

    
    public function references()
    {
        return $this->hasMany('App\Reference','document_id');
    }
 
}
