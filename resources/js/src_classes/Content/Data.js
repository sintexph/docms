export class Data {
    constructor() {
        this._meta = {};
    }
    get meta() {
        return this._meta;
    }
    set meta(meta) {
        this._meta = meta;
    }
}