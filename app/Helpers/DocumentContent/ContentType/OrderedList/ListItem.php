<?php

namespace App\Helpers\DocumentContent\ContentType\OrderedList;
use App\Helpers\DocumentContent\ContentType\Paragraph;

class ListItem
{
    public $ordered_list;
    public $data;
    public $is_list;

    public function __construct($data=null,array $ordered_list=null, $is_list=null)
    {
        if($data!=null)
            $this->data=$data;
        else
            $this->data=new Paragraph; // Set default item as paragraph

        if($ordered_list!=null)
            $this->ordered_list=$ordered_list;
        else
            $this->ordered_list=[];

        if($is_list!=null)
            $this->is_list=$is_list;
        else
            $this->is_list=true;

    }
}


