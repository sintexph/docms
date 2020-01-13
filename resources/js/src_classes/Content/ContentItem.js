import {
    Datum
}
from './Datum.js';

export class ContentItem {
    constructor(name, data, meta, box_hidden) {
        // invokes the setter
        if (name !== undefined)
            this._name = name;
        else
            this._name = '';

        if (data !== undefined) {
            this._data = data;
        } else
            this._data = new Array();

        if (meta !== undefined) {
            this._meta = meta;
        } else {
            this._meta = {
                with_header: true
            };

        }


        if (box_hidden !== undefined)
            this._box_hidden = box_hidden;
        else
            this._box_hidden = false;
    }

    get box_hidden() {
        return this._box_hidden;
    }
    set box_hidden(box_hidden) {
        this._box_hidden = box_hidden;
    }

    get name() {
        return this._name;
    }
    set name(name) {
        this._name = name;
    }
    get data() {
        return this._data;
    }
    set data(data) {
        this._data = data;
    }

    get meta() {
        return this._meta;
    }
    set meta(meta) {
        this._meta = meta;
    }

    addDatum(datum) {
        if (datum !== undefined) {
            this.data.push(datum);
        } else
            this.data.push(new Datum());
    }
    removeDatum(datum) {
        const index = this._data.indexOf(datum);
        this.removeDatumIndex(index);
    }
    removeDatumIndex(index) {
        this._data.splice(index, 1);
    }
}
