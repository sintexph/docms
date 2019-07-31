<?php

namespace App\Helpers\DocumentContent\Util;

class ParagraphHelper {
    
    public static function format_html($str) {
        $value = nl2br($str);
        $value=str_replace("<bold>","<b>",$value);
        $value=str_replace("</bold>","</b>",$value);
        $value=str_replace("<italic>","<i>",$value);
        $value=str_replace("</italic>","</i>",$value);
        return $value;
    }
    
}
