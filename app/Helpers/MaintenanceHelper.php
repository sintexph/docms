<?php

namespace App\Helpers;
use App\DocumentVersion;
use App\Helpers\DocumentContent\Util\Cast;

class MaintenanceHelper 
{
    
    public static function change()
    {

        static::temporary_cast_table(DocumentVersion::find(68));

        /*
        foreach (DocumentVersion::all() as $version) {
            static::temporary_cast_table($version);
        }*/
    }
    public static function temporary_cast_table(DocumentVersion $version)
    {
        try {
            $version->content=Cast::cast_to_content($version->content);
            $version->description=Cast::cast_to_content($version->description);
            $version->save();

        } catch (\Throwable $th) {
            //dump($version);
        }
        //$version->save();
        return $version;
    }
    
    
}
