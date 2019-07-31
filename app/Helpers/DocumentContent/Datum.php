<?php

namespace App\Helpers\DocumentContent;

class Datum
{
    public $meta;
    public $type;
    public $hidden;

    public function __construct()
    {
        $this->meta=[];
        $this->type='Data';
        $this->hidden=false;
    }

    public function __toString()
    {
        return 'Welcome data';
    }
}

