<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\FileHelper;

class Upload extends Model
{
    protected $fillable=[
        'file_name',
        'file_type',
        'file_path',
        'file_mime',
        'file_size',
        'uploaded_by',
        'has_preview',
    ];

    protected $casts=[
        'has_preview'=>'boolean',
        
    ];
    protected $appends=['download_link','preview_link'];


    public function getFileSizeAttribute($value)
    {
        return FileHelper::formatSizeUnits($value);
    }

    

    public function getDownloadLinkAttribute()
    {
        return route('file.download',$this->id);
    }

    public function getPreviewLinkAttribute()
    {
        return route('file',$this->id);
    }
    
}
