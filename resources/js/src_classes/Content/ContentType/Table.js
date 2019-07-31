import {
    Datum
}
from '../Datum.js';

export class Table extends Datum {
    constructor(header, rows) {
        super();

        this._type = 'table';

        if (header !== undefined)
            this._header = header;
        else
            // invokes the setter
            this._header = ['', '', ''];

        if (rows !== undefined)
            this._rows = rows;
        else
            // invokes the setter
            this._rows = [
                ['', '', ''],
            ];
    }
    get header() {
        return this._header;
    }
    set header(header) {
        this._header = header;
    }

    get rows() {
        return this._rows;
    }
    set rows(rows) {
        this._rows = rows;
    }


    addHeader(header) {
        var par = this;
        if (header === undefined) {
            this._header.push(header);
        } else {
            this._header.push('');
        }
        // Insert new cell in row
        this._rows.forEach(function (row, key) {
            par._rows[key].push('');
        });

    }
    removeHeader(element) {
        const index = this._header.indexOf(element);
        this.removeHeaderIndex(index);
    }

    removeHeaderIndex(index) {
        var par = this;
        this._header.splice(index, 1);

        this._rows.forEach(function (row, key) {
            par._rows[key].splice(index, 1);
        });
    }

    addRow(row) {
        if (row === undefined) {
            this._rows.push(row);
        } else {
            var row = [];
            this._header.forEach(function () {
                row.push('');
            });
            this._rows.push(row);
        }
    }
    removeRow(element) {
        const index = this._rows.indexOf(element);
        this.removeRowIndex(index);
    }

    removeRowIndex(index) {
        this._rows.splice(index, 1);
    }

    init() {
        this._header = ['', '', ''];
        this._rows = [
            ['', '', ''],
            ['', '', ''],
            ['', '', ''],
        ];

    }


    thead() {

        var display = '<thead>';
        display += '<tr>';
        this._header.forEach(function (head) {
            display += '<th>' + head + '</th>';
        });
        display += '</tr>';
        display += '</thead>';
        return display;
    }
    tbody() {
        var display = '<tbody>';
        this._rows.forEach(function (cells) {
            display += '<tr>';
            cells.forEach(function (cell) {
                display += '<td>' + cell + '</td>';
            });
            display += '</tr>';
        });
        display += '</tbody>';
        return display;
    }
    toString() {
        return '<table class="table table-bordered">' + this.thead() + this.tbody() + '</table>';
    }
    toShortString() {
        return '<table class="table table-bordered">' + this.thead() + '</table>';
    }

}
