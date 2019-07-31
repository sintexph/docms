export class ListItem {
    constructor(data, ordered_list, is_list) {
        // invokes the setter
        if (data !== undefined)
            this._data = data;
        else
            this._data = new Paragraph;

        if (ordered_list !== undefined && ordered_list!==null)
            this._ordered_list = ordered_list;
        else
            this._ordered_list = new Array();


        if (is_list !== undefined)
            this._is_list = is_list;
        else
            this._is_list = true;

        this._meta = {
            html: ''
        };
    }


    get is_list() {
        return this._is_list;
    }
    set is_list(is_list) {
        this._is_list = is_list;
    }


    get meta() {
        return this._meta;
    }
    set meta(meta) {
        this._meta = meta;
    }

    get data() {
        return this._data;
    }
    set data(data) {
        this._data = data;
    }


    get ordered_list() {
        return this._ordered_list;
    }
    set ordered_list(ordered_list) {
        this._ordered_list = ordered_list;
    }

    addOrderedList(obj) {
        if (obj === undefined) {
            //var ordered_list = new OrderedList();
            //ordered_list.addListItem();
            this.ordered_list.push(new OrderedList);
        } else {
            this.ordered_list.push(obj);
        }
    }
    removeOrderedList(ordered_list) {
        const index = this.ordered_list.indexOf(ordered_list);
        this.removeOrderedListIndex(index);
    }

    removeOrderedListIndex(index) {
        this.ordered_list.splice(index, 1);
    }


}
