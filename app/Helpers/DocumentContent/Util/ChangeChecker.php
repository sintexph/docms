<?php

namespace App\Helpers\DocumentContent\Util;
use App\Helpers\DocumentContent\ContentType\Table;

class ChangeChecker
{


    public static function check_diff_multi($array1, $array2){
        $result = array();
        foreach($array1 as $key => $val) {
            if(isset($array2[$key])){
            if(is_array($val) && $array2[$key]){
                $result[$key] = static::check_diff_multi($val, $array2[$key]);
            }
        } else {
            $result[$key] = $val;
        }
        }

        return $result;
    }
    public function content_check(Content $old,Content $new)
    {
        if(count($old->content_items)!=count($new->content_items))
        {
               
        }
    }

    public function content_item_check()
    {

    }

    public function list_check(OrderedList $new,OrderedList $old)
    {
        
    }

    public function paragraph_check(Paragraph $new, Paragraph $old)
    {
        if($new->value!==$old->value)
            return $new;
        else
            return null;
    }

    public static function table_check()//(Table $new, Table $old)
    {
        /*
        $tbl=new Table;
        foreach (array_diff($new->header,$old->header) as $value) {
            $tbl->header[]=$value;
        }

        foreach ($table->rows as $key => $value) {
            # code...
        }
        */
        $new=new Table;
        $new->rows[]=[1,2,3,0,5,6];
        $new->rows[]=[1,2,3,3,9,2];
        $new->rows[]=[1,2,3,4,7,6];
        
        
        $old=new Table;
        $old->rows[]=[1,2,3,4,5,6];
        $old->rows[]=[1,2,3,4,9,2];
        $old->rows[]=[1,2,3,4,7,6];
        

        $rows=[];
        for ($i=0; $i < count($new->rows) ; $i++) {
            $temp=$new->rows[$i];
            for($j=0; $j < count($old->rows); $j++){
                $temp2=$old->rows[$j];
                //dump([$temp,$temp2]);
                if(count(array_diff($temp,$temp2))==0)
                {
                    $temp=null;
                    break;
                }
            }
            
            if($temp!=null)
                $rows[]=$temp;

        }

        return $rows;

        //foreach (array_diff($new->rows,$old->rows) as $value) {
        //    $tbl->header[]=$value;
        //}
        
    }
}