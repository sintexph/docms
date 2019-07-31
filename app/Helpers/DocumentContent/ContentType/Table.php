<?php

namespace App\Helpers\DocumentContent\ContentType;
use App\Helpers\DocumentContent\Datum;

class Table extends Datum
{
    public $header;
    public $rows;

    public function __construct(array $header=null,array $rows=null)
    {
        $this->type='table';
        
        if($header!=null)
            $this->header=$header;
        else
            $this->header=['', '', ''];

        if($rows!=null)
            $this->rows=$rows;
        else
            $this->rows=[
                ['', '', ''],
            ];
    }

    public function toString()
    {
        $display = '<table class="table table-bordered">';
        $display .= '<thead>';
        $display .= '<tr>';

        # Generate the header of the table
        foreach ($this->header as $key => $head) {
             $display .= '<th>' . $head . '</th>';
        }
        $display .= '</tr>';
        $display .= '</thead>';

        # Generate the table row and its cells
        $display .= '<tbody>';
        foreach ($this->rows as $row) {
            $display .= '<tr>';
            foreach ($row as $cell) {
                $display .= '<td>' . $cell . '</td>';
            }
            $display .= '</tr>';
        }

        $display .= '</tbody>';
        $display .= '</table>';
        return $display;
    }
}
