<?php

namespace App\Helpers\DocumentContent;
use App\Helpers\DocumentContent\Util\Representation;

class Content
{
    public $style;
    public $content_items;

    public function __construct($style=null,array $content_items=null)
    {
        if($style!=null)
            $this->style=$style;
        else
            $this->style='';
        
        if($content_items!=null)
            $this->content_items=$content_items;
        else
            $this->content_items=[];
    }


    public function toString()
    {
        return Representation::content_item_representation($this);
    }
}