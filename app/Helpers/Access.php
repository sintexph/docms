<?php

namespace App\Helpers;


class Access 
{
    const _CONFIDENTIAL=1;
    const _PUBLIC=2;

    public static function text_access($access)
    {
        $type='None';
        switch($access)
        {
            case self::_CONFIDENTIAL:
                $type='Confidential';
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
            case self::_PUBLIC:
                $type='<i class="fa fa-globe" aria-hidden="true"></i>';
            break;
        }
        return $type;
    }

}
