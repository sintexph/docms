<?php

namespace App\Helpers\DocumentContent\ContentType\OrderedList;
use App\Helpers\DocumentContent\Datum;
use App\Helpers\DocumentContent\Util;
use App\Helpers\DocumentContent\Util\Representation;


class OrderedList extends Datum
{
    public $list_items;
    public $has_parent;

    public function __construct(array $list_items=null)
    {
        $this->type = 'image';
        if($list_items!=null)
            $this->list_items=$list_items;
        else
            $this->list_items=[];
        $this->has_parent=true;
        $this->meta =[
            'style'=>'',
        ];
    }
    

    public function toString($parentStyle)
    {
        return Representation::list_representation($this, $parentStyle);
    }
}