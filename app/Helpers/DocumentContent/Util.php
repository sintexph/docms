<?php

namespace App\Helpers\DocumentContent;

use App\Helpers\DocumentContent\ContentType\Paragraph;
use App\Helpers\DocumentContent\ContentType\Table;
use App\Helpers\DocumentContent\ContentType\Image;
use App\Helpers\DocumentContent\ContentType\OrderedList\OrderedList;
use App\Helpers\DocumentContent\ContentType\OrderedList\ListItem;

class Util
{
    public static function generate_presentation(OrderedList $pList)
    {
        if(!empty($pList->meta['style']))
            $style='style="list-style-type: '.$pList->meta['style'].'"';
        else
            $style='';

        $result="<ol $style>";
        $list_items = $pList->list_items;
        
        for ($i = 0; $i<count($list_items); $i++) 
        {
            $list_item = $pList->list_items[$i];
            if(!empty($list_item->meta['html']))
                $result .='<li>'. $list_item->meta['html'];
            else
                $result .='<li>'. $list_item->value;

            $temp_list = $list_item->ordered_list;
            
            for ($j = 0; $j<count($temp_list); $j++) {
                $result.=static::generate_presentation($temp_list[$j]);
            }
            $result.= '</li>';

        }
            $result.= '</ol>';
        return $result;    
    }

    
    /**
     * Remove the trailing underscore in object/array keys
     * @param $array The object/array
     */
    public static function generalize_keys($array)
    {
        $encode=json_encode($array);
        $encode=str_replace("\"_","\"",$encode);
        return json_decode($encode);
    }

    public static function content_list_conversion($data)
    {
        $content=[];
        for ($i=0; $i < count($data); $i++) { 
            $content[]=static::convert_content($data[$i]);
        }
        return $content;
    }
    public static function convert_content($data)
    {
        $meta=isset($data['meta'])?$data['meta']:null;

        $content=new Content($data["name"],null,$meta);
        foreach ($data['items'] as $item) {

            $item_temp=new Item;
            $item_temp->type=$item['type'];

            switch ($item_temp->type) {
                case 'paragraph':
                    
                    if(!$item['data'] instanceof Paragraph)
                        $item_temp->setData(static::convert_paragraph($item['data']));
                    else
                        $item_temp->setData($item['data']);

                    break;
                case 'table':
                    if(!$item['data'] instanceof Table)
                        $item_temp->setData(static::convert_table($item['data']));
                    else
                        $item_temp->setData($item['data']);
                    break;
                case 'list':
                        $item_temp->setData(static::convert_ordered_list($item['data']));
                    break;
                case 'image':
                        $item_temp->setData(static::convert_image($item['data']));
                    break;
            }

            $content->items[]=$item_temp;
        }
        return $content;
    }
    public static function convert_paragraph($data)
    {
        $paragraph=new Paragraph;
        $paragraph->meta=$data["meta"];
        $paragraph->value=$data["value"];

        return $paragraph;
    }
    public static function convert_table($data)
    {
        $table=new Table($data['header'],$data['rows']);
        return $table;
    }

    public static function convert_ordered_list($data)
    {
        $list=new OrderedList;

        if(isset($data['meta']))
            $list->meta=$data["meta"];

        for ($i=0; $i < count($data["list_items"]); $i++) { 
            $temp_list_item = new ListItem();
            $temp_list_item->value = $data["list_items"][$i]["value"];

            if(isset($data["list_items"][$i]["meta"]))
                $temp_list_item->meta = $data["list_items"][$i]["meta"];

            $temp_list = $data["list_items"][$i]["ordered_list"];
            if(count($temp_list)!=0)
            {
                if(!$temp_list instanceof OrderedList)
                {
                    for ($j=0; $j < count($temp_list); $j++) { 
                        $temp_list_item->ordered_list[]=static::convert_ordered_list($temp_list[$j]);
                    }
                }
            }

            $list->list_items[]=$temp_list_item;


        }
        return $list;
    }

    public static function convert_image($data)
    {
        $image=new Image;
        $image->meta=$data["meta"];
        $image->link=$data["link"];

        return $image;
    }

    
}