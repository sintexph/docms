<?php

namespace App\Helpers\DocumentContent\ContentType;
 
class TableCell
{
    public $value;
    public $fit;


    public $rowspan = "";
    public $colspan = "";
    public $center = false;



    public function __construct($value=null,$fit=null)
    {
        if($value!=null)
            $this->value=$value;
        else
            $this->value='';

        if($fit!=null)
            $this->fit=$fit;
        else
            $this->fit=false;
    }
}
