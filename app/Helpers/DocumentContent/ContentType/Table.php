<?php

namespace App\Helpers\DocumentContent\ContentType;
use App\Helpers\DocumentContent\Datum;
use App\Helpers\DocumentContent\ContentType\TableCell;

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
            $this->header=[new TableCell,new TableCell,new TableCell];

        if($rows!=null)
            $this->rows=$rows;
        else
            $this->rows=[
                [new TableCell,new TableCell,new TableCell],
            ];
    }

    public function toString()
    {
        $display = '<table class="table table-bordered">';
        $display .= '<thead>';
        $display .= '<tr>';

        # Generate the header of the table
        foreach ($this->header as $key => $head) {
            if($head->fit==true)
                $display .= '<th style="white-space: nowrap; width: 1%;">' . $head->value . '</th>';
            else
                $display .= '<th>' . $head->value . '</th>';
        }
        $display .= '</tr>';
        $display .= '</thead>';

        # Generate the table row and its cells
        $display .= '<tbody>';

        foreach ($this->rows as $row) {
            $display .= '<tr>';
            foreach ($row as $cell) {
                if($cell->fit==true)
                    $display .= '<td style="white-space: nowrap; width: 1%;">' . $cell->value . '</td>';
                else
                    $display .= '<td>' . $cell->value . '</td>';
            }
            $display .= '</tr>';
        }

        $display .= '</tbody>';
        $display .= '</table>';
        return $display;
    }
}
