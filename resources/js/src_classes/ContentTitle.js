export class ContentTitle {
    constructor() {

        this._id = '';
        this._name = '';
        this._code = ''; 
  


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
   
 

    set created_at(value) {
        this._created_at = value;
    }
    set updated_at(value) {
        this._updated_at = value;
    }



}
