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
            this._header = [new TableCell, new TableCell, new TableCell];

        if (rows !== undefined)
            this._rows = rows;
        else
            // invokes the setter
            this._rows = [
                [new TableCell, new TableCell, new TableCell],
                [new TableCell, new TableCell, new TableCell],
                [new TableCell, new TableCell, new TableCell],
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
            this._header.push(new TableCell);

        } else {
            this._header.push(header);
        }

        // Insert new cell in row
        this._rows.forEach(function (row, key) {
            par._rows[key].push(new TableCell);
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
        if (row !== undefined) {
            this._rows.push(row);
        } else {
            var rw = [];
            this._header.forEach(function () {
                rw.push(new TableCell);
            });
            this._rows.push(rw);
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
        this._header = [new TableCell, new TableCell, new TableCell];
        this._rows = [
            [new TableCell, new TableCell, new TableCell],
            [new TableCell, new TableCell, new TableCell],
            [new TableCell, new TableCell, new TableCell],
        ];

    }


    thead() {

        var display = '<thead>';
        display += '<tr>';
        this._header.forEach(function (head) {
            if (head.fit === true)
                display += '<th style="white-space: nowrap; width: 1%;">' + head.value + '</th>';
            else
                display += '<th>' + head.value + '</th>';
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
                if (cell.fit === true)
                    display += '<td style="white-space: nowrap; width: 1%;">' + cell.value + '</td>';
                else
                    display += '<td>' + cell.value + '</td>';
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
