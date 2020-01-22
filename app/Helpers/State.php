<?php

namespace App\Helpers;
use App\DocumentVersion;

class State 
{
    const PENDING=1;
    const RELEASED=2;
    const APPROVED=3;
    const FOR_APPROVAL=4;
    const FOR_REVIEW=5;


    public static function state($state_value) {
        
        $stateClass = new \ReflectionClass ( '\App\Helpers\State' );
        $constants = $stateClass->getConstants();

        $constName = null;
        foreach ( $constants as $name => $value )
        {
            if ( $value == $state_value )
            {
                $constName = $name;
                break;
            }
        }

        $constName=strtolower($constName);
        $constName=\str_replace("_"," ",$constName);

        return ucwords($constName);
    }


    public static function getState(DocumentVersion $version)
    {
        if($version->released==true)
            return static::RELEASED;
        elseif($version->approved==true)
            return static::APPROVED;
        elseif($version->reviewed==true && $version->approved==false && $version->for_approval==true) # Document must be reviewed so it can set that the document is for approval
            return static::FOR_APPROVAL;
        elseif($version->reviewed==false && $version->for_review==true)
            return static::FOR_REVIEW;
        else
            return static::PENDING;
    }
}
