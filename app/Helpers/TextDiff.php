<?php

namespace App\Helpers;

class TextDiff 
{
    private $additional_strings=[];
    private $removed_strings=[];


    public function __construct($old, $new)
    {
        $this->htmlDiff($old,$new);
    }
    function diff($old, $new){

        $maxlen=0;
        foreach($old as $oindex => $ovalue){
            $nkeys = array_keys($new, $ovalue);
            foreach($nkeys as $nindex){
                $matrix[$oindex][$nindex] = isset($matrix[$oindex - 1][$nindex - 1]) ?
                    $matrix[$oindex - 1][$nindex - 1] + 1 : 1;
                if($matrix[$oindex][$nindex] > $maxlen){
                    $maxlen = $matrix[$oindex][$nindex];
                    $omax = $oindex + 1 - $maxlen;
                    $nmax = $nindex + 1 - $maxlen;
                }
            }	
        }
        if($maxlen == 0) 
            return array(array('d'=>$old, 'i'=>$new));


        return array_merge(
            $this->diff(array_slice($old, 0, $omax), array_slice($new, 0, $nmax)),
            array_slice($new, $nmax, $maxlen),
            $this->diff(array_slice($old, $omax + $maxlen), array_slice($new, $nmax + $maxlen)));
    }
    function htmlDiff($old, $new){
        

        $old= htmlspecialchars(trim(strip_tags($old)));
        $old=trim(preg_replace('/\s+/S', " ", $old));
        $new= htmlspecialchars(trim(strip_tags($new)));
        $new=trim(preg_replace('/\s+/S', " ", $new));

        $diff = $this->diff(explode(' ', $old), explode(' ', $new));

        foreach($diff as $key=>$k){
            if(is_array($k))
            {
                $char_limit=2;
                # Get the arrays before the specified key index
                $beforeNames=implode(" ",array_map(function($value){
                    if(!is_array($value))
                    return $value;
                    else
                    return '';
                },$this->get_before_elements($diff,$char_limit,$key)));

                # Get the arrays after the specified key index
                $afterNames=implode(" ",array_map(function($value){
                    if(!is_array($value))
                    return $value;
                    else
                    return '';
                },array_slice($diff,$key+1,$char_limit+5)));

                if(!empty($k['d']))
                {
                    $this->removed_strings[]=$beforeNames." <del>".implode(' ',$k['d'])."</del> ".$afterNames;
                }

                if(!empty($k['i']))
                {
                    $this->additional_strings[]=$beforeNames." <ins>".implode(' ',$k['i'])."</ins> ".$afterNames;
                }
            }
        }
    }

    function get_before_elements($array,$character_limit,$key)
    {
        $result=array_slice($array,0,$key);
        $result=array_splice($result,count($result)-$character_limit);
        return $result;
    }

    public function hasChanges()
    {
        return (count($this->additional_strings) || count($this->removed_strings));
    }

    public function getResult()
    {
        return (string)$this;
    }
    public function __toString()
    {
        $string='<html><head><style>del{background-color: red; color:white;} ins{ background-color: green; color:white;}</style></head><body>';

        foreach ($this->additional_strings as $value)
        {
            $string.='<p style="background-color: #edffe8;">'.$value.'</p>';
        }
        foreach ($this->removed_strings as $value)
        {
            $string.='<p style="background-color: #ffe8e8; ">'.$value.'</p>';
        }
        $string.='</body></html>';

        return $string;
    }


}


 
