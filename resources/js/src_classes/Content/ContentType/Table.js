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

            var style = '';

            if (head.fit === true)
                style += `white-space: nowrap; width: 1%; `;

            if (head.center === true)
                style += `text-align:center; `;

            display += '<th rowspan="' + head.rowspan + '" colspan="' + head.colspan + '" style=' + style + '>' + head.value + '</th>';
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

                var style = '';

                if (cell.fit === true)
                    style += `white-space: nowrap; width: 1%; `;

                if (cell.center === true)
                    style += `text-align:center; `;

                display += '<td rowspan="' + cell.rowspan + '" colspan="' + cell.colspan + '" style="' + style + '">' + cell.value + '</td>';
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









    addRow() {
        this._rows.push([new TableCell]);
    }

    addRowCell(rowIndex) {
        this._rows[rowIndex].push(new TableCell);
    }



    removeRow(rowIndex) {
        if (this._rows.length == 1) {
            alert("Table must have at least 1 row!");
            return;
        }
        this._rows.splice(rowIndex, 1);
    }


    removeRowCell(rowIndex, cellIndex) {

        if (this._rows[rowIndex].length == 1) {
            alert("Table must have at least 1 cell!");
            return;
        }


        this._rows[rowIndex].splice(cellIndex, 1);
    }

    addHeaderCell(tableCell) {
        if (tableCell === undefined) {
            this._header.push(new TableCell);

        } else {
            this._header.push(tableCell);
        }
    }
    removeHeaderCell(cellIndex) {

        if (this._header.length == 1) {
            alert("Table must have at least 1 header!");
            return;
        }

        this._header.splice(cellIndex, 1);
    }


}
