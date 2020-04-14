<?php

namespace App\Helpers\DocumentContent\Util;

use App\Helpers\DocumentContent\ContentType\Paragraph;
use App\Helpers\DocumentContent\ContentType\Table;
use App\Helpers\DocumentContent\ContentType\TableCell;
use App\Helpers\DocumentContent\ContentType\Image;
use App\Helpers\DocumentContent\ContentType\OrderedList\OrderedList;
use App\Helpers\DocumentContent\ContentType\OrderedList\ListItem;
use App\Helpers\DocumentContent\Datum;
use App\Helpers\DocumentContent\Content;
use App\Helpers\DocumentContent\ContentItem;

class Cast
{
    public static function cast_to_content($data) {
        # Cast a content to an instance of Content Class
        if (($data instanceof Content) == false) {
            
            
            $content = new Content;
            $content->style = $data['style'];

            for ($i = 0; $i < count($data['content_items']); $i++) {
                $content->content_items[]=static::cast_to_content_item($data['content_items'][$i]);
            }

            return $content;
        }else
            return $data;
    }
    
    public static function cast_to_content_item($data) {
        // Cast a content to an instance of Content Class
        if (($data instanceof ContentItem) == false) {
            $content_item = new ContentItem;
            $content_item->name = $data['name'];
            $content_item->box_hidden=$data['box_hidden'];
            $content_item->meta = $data['meta'];
            
            for ($i = 0; $i < count($data['data']); $i++) {
                $content_item->data[]=static::cast_to_datum($data['data'][$i]);
            }

            return $content_item;
        }else
            return $data;
    }
    
    public static function cast_to_datum($data) {
        $datum = new Datum;
        switch ($data['type']) {
            case 'list':
                $datum =static::cast_to_list($data);

                break;

            case 'image':
                $datum =static::cast_to_image($data);
                break;

            case 'table':
                $datum =static::cast_to_table($data);
                break;

            case 'paragraph':
                $datum =static::cast_to_paragraph($data);
                break;

        }
                
        $datum->type = $data['type'];
        $datum->meta = $data['meta'];
        $datum->hidden = $data['hidden'];

        return $datum;
    }

    public static function cast_to_list($data) {
        
        $list = new OrderedList();
        
        $list->has_parent=$data['has_parent'];
        $list->meta=$data['meta'];

        //if($data['type']=='image')
        //    $list->type='list';
        
        foreach ($data['list_items'] as $list_item) {
            
            # Instantiate new list item
            $temp_list_item = new ListItem();
            $temp_list_item->data = static::cast_to_datum($list_item['data']);

            /*
            switch ($list_item['data']['type']) {
                case 'table':
                    $temp_list_item->data = static::cast_to_table($list_item['data']);
                    break;
                case 'paragraph':
                    $temp_list_item->data = static::cast_to_paragraph($list_item['data']);
                    break;
                case 'image':
                    $temp_list_item->data = static::cast_to_image($list_item['data']);
                    break;
            }
            */
          

            $temp_list_item->is_list = $list_item['is_list'];
            $temp_list_item->meta = $list_item['meta'];
            $temp_list=$list_item['ordered_list'];

            foreach ($temp_list as $li) {
                //$temp_list_item->ordered_list[]=static::cast_to_datum($li);
                $temp_list_item->ordered_list[]=static::cast_to_list($li);
            }
            $list->list_items[]=$temp_list_item;
        }
        
        return $list;
    }
    
    public static function cast_to_table($data) {
        
        $rows=[];
        $header=[];

            foreach ($data['header'] as $value) {
                $header[]=static::cast_to_cell($value);
            }
            foreach ($data['rows'] as $cells) {
                $row=[];
                foreach ($cells as $cell) {
                    $row[]=static::cast_to_cell($cell);
                }

                $rows[]=$row;
            }
            

            $temp = new Table($header,$rows);

            return $temp;
    }
    public static function cast_to_cell($data) {
        
        $temp = new TableCell($data['value'],$data['fit']);
        $temp->rowspan=$data['rowspan'];
        $temp->colspan=$data['colspan'];
        $temp->center=$data['center'];
        
        return $temp;
    }
    public static function cast_to_paragraph($data) {
            $temp = new Paragraph;
            $temp->value = $data['value'];
            return $temp;
    }

    public static function cast_to_image($data) {
            $temp = new Image;
            $temp->upload_id = $data['upload_id'];
            return $temp;
    }

    public static function generalize_keys($array)
    {
        $encode=json_encode($array);
        $encode=str_replace("\"_","\"",$encode);
        return json_decode($encode);
    }
}