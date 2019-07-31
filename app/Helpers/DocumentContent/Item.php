<?php

namespace App\Helpers\DocumentContent;

class Item
{
    public $data;
    public $type;

    public function __construct(Data $data=null,$type=null)
    {
        if($data!=null)
            $this->data=$data;
        else
            $this->data=new Data;

        if($type!=null)
            $this->type=$type;
        else
            $this->type='';
    }

    public function setData(Data $data)
    {
        $this->data=$data;
    }
}