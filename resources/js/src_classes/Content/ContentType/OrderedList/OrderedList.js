import {
    Datum
}
from '../../Datum.js';
import {
    ContentListHelper
}
from '../../../../helpers/ContentListHelper.js';

export class OrderedList extends Datum {
    constructor() {
        // invokes the setter
        super();

        this.clear();

    }
    get list_items() {
        return this._list_items;
    }
    set list_items(list_items) {
        this._list_items = list_items;
    }

    get has_parent() {
        return this._has_parent;
    }
    set has_parent(has_parent) {
        this._has_parent = has_parent;
    }

    addListItem(list_item) {

        if (list_item === undefined) {
            this._list_items.push(new ListItem());
        } else
            this._list_items.push(list_item);
    }
    removeListItem(list_item) {
        const index = this._list_items.indexOf(list_item);
        this.removeListItemIndex(index);
    }
    removeListItemIndex(index) {
        this._list_items.splice(index, 1);
    }
    clear() {
        this._type = 'list';
        this._has_parent = true;

        this._list_items = new Array();
        this._meta = {
            style: 'number',
            // none = ''
            // number
            // lower_alpha
            // upper_alpha
            // circle
            // square
            // upper_roman
            // lower_roman
        }
    }


    toShortString() {
        var str = this.toString();
        if ((str === null) || (str === ''))
            return false;
        else {

            // Remove special chars/html
            str = str.toString();
            str = str.replace(/<[^>]*>/g, '');
            // Shorten the content
            if (str.length > 50)
                return str.substring(0, 50) + '...';
            else
                return str;
        }

    }
    toString(parentStyle) { 
     
        return ContentListHelper.list_representation(this, parentStyle);
    }
}
