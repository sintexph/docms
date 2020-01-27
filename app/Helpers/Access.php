<?php

namespace App\Helpers;


class Access 
{
    const _CONFIDENTIAL=1;
    const _CUSTOM=2;
    const _PUBLIC=3;
    const _ONLY_ME=4;

    public static function text_access($access)
    {
        $type='None';
        switch($access)
        {
            case self::_CONFIDENTIAL:
                $type='Confidential';
            break;
            case self::_CUSTOM:
                $type='Custom';
            break;
            case self::_ONLY_ME:
                $type='Private';
            break;
            case self::_PUBLIC:
                $type='Public';
            break;
        }
        return $type;
        
    }


    public static function icon_access($access)
    {
        $type='';
        switch($access)
        {
            case self::_CONFIDENTIAL:
                $type='<i class="fa fa-user-secret" aria-hidden="true"></i>';
            break;
            case self::_CUSTOM:
                $type='<i class="fa fa-cog" aria-hidden="true"></i>';
            break;
            case self::_ONLY_ME:
                $type='<i class="fa fa-user" aria-hidden="true"></i>';
            break;
            case self::_PUBLIC:
                $type='<i class="fa fa-globe" aria-hidden="true"></i>';
            break;
        }
        return $type;
    }

}
