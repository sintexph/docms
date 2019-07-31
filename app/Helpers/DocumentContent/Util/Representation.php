<?php

namespace App\Helpers\DocumentContent\Util;

use App\Helpers\DocumentContent\ContentType\Paragraph;
use App\Helpers\DocumentContent\ContentType\Table;
use App\Helpers\DocumentContent\ContentType\Image;
use App\Helpers\DocumentContent\ContentType\OrderedList\OrderedList;
use App\Helpers\DocumentContent\ContentType\OrderedList\ListItem;

class Representation
{

    public static function list_representation(OrderedList $ordered_list, $parent_label=null) {
        $count = 0;

        $result = '<table class="ordered-list"><tbody>';
        $list_items = $ordered_list->list_items;

        for ($i = 0; $i < count($list_items); $i++) {
            $list_item = $ordered_list->list_items[$i];
            $result .= '<tr>';
            
            $item_label = static::generate_before_label($ordered_list->meta['style'], $count + 1);

            # Put a styled list order if it is list
            if ($list_items[$i]->is_list == true) {
                # Check if there is a parent label and concatenate it to the current label
                if ($ordered_list->has_parent==true && $parent_label != null)
                    $item_label = $parent_label.$item_label;
                    
                $count++;
                # User rtrim to remove the last dot from the numbering
                $result .= '<td class="ol-label"><strong>' . $item_label. '</strong></td>';
            }

            $result .= '<td class="ol-content">' . $list_item->data->toString();
            $temp_list = $list_item->ordered_list;

            for ($j = 0; $j < count($temp_list); $j++) {
                $result .= static::list_representation($temp_list[$j], $item_label);
            }
            $result .= '</td></tr>';

        }
        $result .= '</tbody></table>';
        return $result;
    }


    public static function content_item_representation($content) {
        $count = 0;

        $result = '<table class="ordered-list"><tbody>';
        $content_items = $content->content_items;

        for ($i = 0; $i < count($content_items); $i++) {
            $content_item = $content->content_items[$i];
            $result .= '<tr>';

            $item_label = static::generate_before_label($content->style, $count + 1);




            $result .= '<td class="ol-label" style="padding-bottom: 1em;"><strong>' . $item_label . '</strong></td>';
            $count++;
            $result .= '<td class="ol-content" style="padding-bottom: 1em;">';
                        
            if ($content_item->meta['with_header'] == true)
                $result .= '<strong>' . $content_item->name . ':</strong>';

            foreach ($content_item->data as $datum)
            {
            
                switch ($datum->type) {
                    case 'list':
                        $result .= $datum->toString($item_label); // Pass the parent label to adapt to the child list
                        break;
                    case 'paragraph':
                        $result .='<br>'. $datum->toString($item_label); // Pass the parent label to adapt to the child list
                        break;
                    default:
                        $result .= $datum->toString();
                        break;
                }

            }
            $result .= '</td></tr>';
        }
        
        $result .= '</tbody></table>';
        return $result;
    }


    public static function generate_before_label($style, $index) {
        // none = ''
        // number
        // lower_alpha
        // upper_alpha
        // circle
        // square
        // upper_roman
        // lower_roman

        $style_str = '';

        switch ($style) {
            case 'lower_alpha':
                $style_str = strtolower(Cipher::decode($index)). '.';
                break;
            case 'upper_alpha':
                $style_str = strtoupper(Cipher::decode($index)). '.';
                break;
            case 'number':
                $style_str = $index. '.';
                break;
            case 'circle':
                $style_str = '&#9679;';
                break;
            case 'square':
                $style_str = '&#9632;';
                break;
            case 'upper_roman':
                $style_str = strtoupper(Roman::encode($index)). '.';
                break;
            case 'lower_roman':
                $style_str = strtolower(Roman::encode($index)). '.';
                break;
        }

        return $style_str;
    }
}