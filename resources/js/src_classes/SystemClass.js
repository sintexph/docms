export class SystemClass {
    constructor() {

        this._id = '';
        this._name = '';
        this._code = '';
        this._reviewer_ids = '';
        this._approver_ids = '';


        this._created_at = '';
        this._updated_at = '';

    }
    get id() {
        return this._id;
    }
    get name() {
        return this._name;
    }
    get code() {
        return this._code;
    }
    get reviewer_ids() {
        return this._reviewer_ids;
    }
    get approver_ids() {
        return this._approver_ids;
    }

    get created_at() {
        return this._created_at;
    }
    get updated_at() {
        return this._updated_at;
    }

    set id(value) {
        this._id = value;
    }
    set name(value) {
        this._name = value;
    }
    set code(value) {
        this._code = value;
    }
    set reviewer_ids(value) {
        this._reviewer_ids = value;
    }
    set approver_ids(value) {
        this._approver_ids = value;
    }

    set created_at(value) {
        this._created_at = value;
    }
    set updated_at(value) {
        this._updated_at = value;
    }



}
