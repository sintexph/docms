<template>
    <div class="table-responsive">
        <div style="padding-bottom:5px;">

            <a href="#" class="btn btn-xs btn-default" @click.prevent="addRow" title="Add Row"><i
                    class="fa fa-list-alt" aria-hidden="true"></i></a>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <td class="fit">
                        <a title="Add Header" @click.prevent="addHeader" href="#"><i class="fa fa-plus"
                                aria-hidden="true"></i></a>
                    </td>
                    <th :rowspan="cell.rowspan" :colspan="cell.colspan" v-for="(cell,cellIndex) in table.header"
                        :key="cellIndex">

                        <input class="form-control" type="text" v-model="table.header[cellIndex].value">

                        <div class="cell-tools">
                            <input title="Extend column" placeholder="col" style="width:50px;"
                                v-model="table.header[cellIndex].colspan" type="text">
                            <button class="btn btn-xs btn-default" @click.prevent="removeHeaderCell(cellIndex)" type="button" title="Remove header"><i
                                    class="fa fa-trash text-red" aria-hidden="true"></i></button>
                            <button :class="'btn btn-xs btn-default '+(cell.center===true?'active':'')" @click.prevent="setHeaderCenter(cellIndex)"
                                type="button" title="Align center">
                                <i class="fa fa-align-center" aria-hidden="true"></i>
                            </button>

                            <button :class="'btn btn-xs btn-default '+(cell.fit===true?'active':'')" @click.prevent="setHeaderFit(cellIndex)"
                                type="button" title="Fit header cell">
                                <i class="fa fa-arrows-h" aria-hidden="true"></i>
                            </button>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(row,rowIndex) in table.rows" :key="rowIndex">
                    <td class="fit">
                        <a href="#" title="Add Cell" @click.prevent="addRowCell(rowIndex)"><i class="fa fa-plus"
                                aria-hidden="true"></i></a>
                        <a href="#" class="text-red" title="Remove row" @click.prevent="removeRow(rowIndex)"><i
                                class="fa fa-trash" aria-hidden="true"></i></a>
                    </td>
                    <td :rowspan="cell.rowspan" :colspan="cell.colspan" v-for="(cell,cellIndex) in row"
                        :key="cellIndex">
                        <textarea style="height:100%;" class="form-control"
                            v-model="table.rows[rowIndex][cellIndex].value"></textarea>

                        <div class="cell-tools">
                            <input title="Extend column" placeholder="col" style="width:50px;"
                                v-model="table.rows[rowIndex][cellIndex].colspan" type="text">
                            <input title="Extend row" placeholder="row" style="width:50px;"
                                v-model="table.rows[rowIndex][cellIndex].rowspan" type="text">
                            <button class="btn btn-xs btn-default" @click.prevent="removeRowCell(rowIndex,cellIndex)" type="button"
                                title="Remove cell"><i class="fa fa-trash text-red" aria-hidden="true"></i></button>

                            <button :class="'btn btn-xs btn-default '+(cell.center===true?'active':'')"
                                @click.prevent="setRowCellCenter(rowIndex,cellIndex)" type="button"
                                title="Align center">
                                <i class="fa fa-align-center" aria-hidden="true"></i>
                            </button>
                            <button :class="'btn btn-xs btn-default '+(cell.fit===true?'active':'')"
                                @click.prevent="setRowCellFit(rowIndex,cellIndex)" type="button" title="Fit row cell">
                                <i class="fa fa-arrows-h" aria-hidden="true"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>

</template>
<script>
    export default {
        props: {
            value: {
                type: [Object, Array],
                default: function () {
                    return new Table;
                }
            }
        },
        data: function () {
            return {
                table: new Table,
            }
        },
        methods: {



            setRowCellFit(rowIndex, cellIndex) {

                if (this.table.rows[rowIndex][cellIndex].fit === true)
                    this.table.rows[rowIndex][cellIndex].fit = false;
                else
                    this.table.rows[rowIndex][cellIndex].fit = true;

            },
            setHeaderFit(index) {
                if (this.table.header[index].fit === true)
                    this.table.header[index].fit = false;
                else
                    this.table.header[index].fit = true;
            },
            setRowCellCenter(rowIndex, cellIndex) {

                if (this.table.rows[rowIndex][cellIndex].center === true)
                    this.table.rows[rowIndex][cellIndex].center = false;
                else
                    this.table.rows[rowIndex][cellIndex].center = true;

            },
            setHeaderCenter(index) {
                if (this.table.header[index].center === true)
                    this.table.header[index].center = false;
                else
                    this.table.header[index].center = true;
            },

            removeHeaderCell(cellIndex) {
                this.table.removeHeaderCell(cellIndex);
            },

            removeRow(rowIndex) {
                this.table.removeRow(rowIndex);
            },

            removeRowCell(rowIndex, cellIndex) {
                this.table.removeRowCell(rowIndex, cellIndex);
            },
            addRowCell(index) {
                this.table.addRowCell(index);
            },
            addRow() {
                this.table.addRow();
            },
            addHeader() {
                this.table.addHeaderCell();
            },

            reset: function () {
                this.table = new Table;
                this.table.init();
                this.$emit('input', this.table);

            },


        },

        mounted() {
            //this.reset();
            this.$nextTick(function () {
                this.table = this.value;
            });
        },
        watch: {
            table: {
                deep: true,
                handler: function (table) {
                    this.$emit('input', table);
                }
            },
            value: {
                deep: true,
                handler: function (value) {
                    this.table = value;
                }
            }
        }


    }

</script>
<style lang="scss">

    .cell-tools {
        margin-top: 5px;
        margin-bottom: 5px;

      

    }

    .cell-tools button.active {
        color: rgb(118, 199, 132);

    }
</style>
