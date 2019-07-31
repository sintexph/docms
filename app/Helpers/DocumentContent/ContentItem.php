<?php
namespace App\Helpers\DocumentContent;

use App\Helpers\DocumentContent\Datum;

class ContentItem {


    public $name; 
    public $data; 
    public $meta;
    public $box_hidden;


    public function __construct($name=null, $data=null, $meta=null, $box_hidden=null) {
        // invokes the setter
        if ($name!=null)
            $this->name = $name;
        else
            $this->name = 'Title should be here ....';

        if ($data != null) {
            $this->data = $data;
        } else
            $this->data =[];

        if ($meta != null) {
            $this->meta = $meta;
        } else {
            $this->meta =[
                'with_header'=>true
            ];

        }

        if ($box_hidden != null)
            $this->box_hidden = $box_hidden;
        else
            $this->box_hidden = false;
    }

    

    public function addDatum($datum=null) {
        if ($datum!=null) {
            $this->data[]=$datum;
        } else
            $this->data[]=new Datum;
    }
    public function removeDatum(Datum $datum) {
        $index=array_search($datum,$this->data);
        $this->removeDatumIndex($index);
    }
    public function removeDatumIndex($index) {
        unset($this->data[$index]);
    }
}
