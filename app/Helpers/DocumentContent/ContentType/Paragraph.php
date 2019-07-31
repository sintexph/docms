<?php

namespace App\Helpers\DocumentContent\ContentType;
use App\Helpers\DocumentContent\Datum;
use App\Helpers\DocumentContent\Util\ParagraphHelper;

class Paragraph extends Datum
{
    public $value;

    public function __construct($value=null)
    {
        $this->type='paragraph';

        if($value!=null)
            $this->value=$value;
        else
            $this->value='';

        $this->meta =[
            'html'=>'',
        ];
    }

    public function toString()
    {
        //return $this->value;
        return ParagraphHelper::format_html($this->value);
    }
}