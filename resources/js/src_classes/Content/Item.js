import {
    Data
}
from './Data.js';

export class Item {
    constructor(data, type) {
        // invokes the setter
        if (data !== undefined)
            this._data = data;
        else
            this._data = new Data();

        if (type !== undefined) {
            this._type = type;
        } else
            this._type = '';

    }
    get data() {
        return this._data;
    }
    set data(data) {
        this._data = data;
    }
    get type() {
        return this._type;
    }
    set type(type) {
        this._type = type;
    }
}
