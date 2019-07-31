<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentVersionComment extends Model
{
    protected $fillable=[
        'version_id',
        'comment',
        'created_by',
    ];

    protected $appends=[
        'comment_html',
        'creator_initials'
    ];
    
    public function version(){
        return $this->belongsTo('App\DocumentVersion','version_id');
    }

    public function getCreatedAtAttribute($value)
    {
        $date=new \Carbon\Carbon($value);
        $date=$date->format("M d, Y h:i A");
        return $date;
    }

    public function getCommentHtmlAttribute()
    {
        return \nl2br($this->comment);
    }

    public function creator()
    {
        return $this->belongsTo('App\User','created_by');
    }

    public function getCreatorInitialsAttribute()
    {
        $words=explode(" ",$this->creator->name);
        
        if(count($words)>=2)
            return strtoupper(substr($words[0],0,1).substr($words[1],0,1));
        else {
             return strtoupper(substr($words[0],0,2));
        }

        
            
    }
    
}
