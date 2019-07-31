import {
    Datum
}
from '../Datum.js';

import {
    ParagraphHelper
}
from '../../../helpers/ParagraphHelper';

export class Paragraph extends Datum {

    constructor(value) {
        super();
        this._type = 'paragraph';
        if (value !== undefined)
            this._value = value;
        else
            // invokes the setter
            this._value = '';
        this._meta = {
            html: '',
        };
    }
    get value() {
        return this._value;
    }
    set value(value) {
        this._value = value;
    }

    toShortString()
    {
         if (this.toString().length > 30)
             return this.toString().substring(0, 30) + '...';
         else
             return this.toString();
    }
    toString() {
        return ParagraphHelper.format_html(this._value);
    }
}
