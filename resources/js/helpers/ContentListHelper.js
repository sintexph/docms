import {
    Cipher
} from './Cipher';
import {
    Roman
}
from './Roman';

export class ContentListHelper {

    static list_representation(ordered_list, parent_label) {
        var count = 0;

        var result = '<table class="ordered-list"><tbody>';
        var list_items = ordered_list.list_items;

        for (var i = 0; i < list_items.length; i++) {
            var list_item = ordered_list.list_items[i];
            result += '<tr>';

            var item_label = this.generate_before_label(ordered_list.meta.style, count + 1);

            // Put a styled list order if it is list
            if (list_items[i].is_list === true) {
                // Check if there is a parent label and concatenate it to the current label
                if (ordered_list.has_parent === true && parent_label !== undefined && parent_label !== null)
                    item_label = parent_label + item_label;

                count++;
                result += '<td class="ol-label"><strong>' + item_label.replace(/(.$)/g, "") + '</strong></td>';
            }

            result += '<td class="ol-content">' + list_item.data.toString();
            var temp_list = list_item.ordered_list;

            for (var j = 0; j < temp_list.length; j++) {
                result += this.list_representation(temp_list[j], item_label);
            }
            result += '</td></tr>';

        }
        result += '</tbody></table>';
        return result;
    }


    static content_item_representation(content) {
        var count = 0;

        var result = '<table class="ordered-list"><tbody>';
        var content_items = content.content_items;

        for (var i = 0; i < content_items.length; i++) {
            var content_item = content.content_items[i];
            result += '<tr>';

            var item_label = this.generate_before_label(content.style, count + 1);

            result += '<td class="ol-label" style="padding-bottom: 1em;"><strong>' + item_label + '</strong></td>';
            count++;
            result += '<td class="ol-content" style="padding-bottom: 1em;">';

            if (content_item.meta.with_header === true && content_item.name !== null && content_item.name !== undefined)
                result += '<strong>' + content_item.name + ':</strong>';

            content_item.data.forEach(function (datum) {


                switch (datum.type) {
                    case 'list':
                        result += datum.toString(item_label); // Pass the parent label to adapt to the child list
                        break;
                    case 'paragraph':
                        result += '<br>' + datum.toString(item_label); // Pass the parent label to adapt to the child list
                        break;
                    default:
                        result += datum.toString();
                        break;
                }

            });
            result += '</td></tr>';
        }
        result += '</tbody></table>';
        return result;
    }


    static generate_before_label(style, index) {
        // none = ''
        // number
        // lower_alpha
        // upper_alpha
        // circle
        // square
        // upper_roman
        // lower_roman

        var style_str = '';
        switch (style) {
            case 'lower_alpha':
                style_str = Cipher.decode(index).toLowerCase() + '.';
                break;
            case 'upper_alpha':
                style_str = Cipher.decode(index).toUpperCase() + '.';
                break;
            case 'number':
                style_str = index + '.';
                break;
            case 'circle':
                style_str = '&#9679;';
                break;
            case 'square':
                style_str = '&#9632;';
                break;
            case 'upper_roman':
                style_str = Roman.encode(index).toUpperCase() + '.';
                break;
            case 'lower_roman':
                style_str = Roman.encode(index).toLowerCase() + '.';
                break;
        }

        return style_str;
    }
}
