import {
    ContentItem
}
from './ContentItem.js';

import {
    ContentListHelper
}
from '../../helpers/ContentListHelper.js';

export class Content {
    constructor(style, content_items) {
        // invokes the setter
        if (style !== undefined)
            this._style = style;
        else
            this._style = null;

        if (content_items !== undefined) {
            this._content_items = content_items;
        } else
            this._content_items = [];
    }
    get style() {
        return this._style;
    }
    set style(style) {
        this._style = style;
    }

    
    get content_items() {
        return this._content_items;
    }
    set content_items(content_items) {
        this._content_items = content_items;
    }
    addContentItem(content_item) {
        if (content_item !== undefined) {
            this._content_items.push(content_item);
        } else
            this._content_items.push(new ContentItem());
    }
    removeContentItem(content_item) {
        const index = this._content_items.indexOf(content_item);
        this.removeContentItemIndex(index);
    }
    removeContentItemIndex(index) {
        this._content_items.splice(index, 1);
    }

    toString()
    {
        return ContentListHelper.content_item_representation(this);
    }
}
