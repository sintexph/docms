export class Datum {
    constructor() {
        this._type = 'Datum';
        this._meta = {};
        this._hidden = false;
    }
    get meta() {
        return this._meta;
    }
    set meta(meta) {
        this._meta = meta;
    }

    get hidden() {
        return this._hidden;
    }
    set hidden(hidden) {
        this._hidden = hidden;
    }

    get type() {
        return this._type;
    }
    set type(type) {
        this._type = type;
    }

    toShortString() {
        return this.toString();
    }
    toString() {
        return '....';
    }
}
