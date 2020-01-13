<template>
    <div class="table-responsive">
        <div style="padding-bottom:5px;">
            <a href="#" class="btn btn-xs btn-default" @click.prevent="table.addHeader()" title="Add Header"><i
                    class="fa fa-header" aria-hidden="true"></i></a>
            <a href="#" class="btn btn-xs btn-default" @click.prevent="add_row" title="Add Row"><i
                    class="fa fa-list-alt" aria-hidden="true"></i></a>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="fit">
                    </th>
                    <th v-for="(value,key) in table.header" :key="key">
                        <div class="input-group">
                            <input type="text" class="form-control" :name="'head-input-'+key"
                                v-model="table.header[key].value">
                            <span class="input-group-btn">
                                <a href="#" v-if="table.header.length>1" @click.prevent="remove_header(key)"
                                    class="btn btn-default text-red" title="Remove header"><i class="fa fa-trash"
                                        aria-hidden="true"></i></a>
                            </span>
                        </div>
                        <div><input type="checkbox" v-model="table.header[key].fit"> Fit</div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(rowvalue,rowkey) in table.rows" :key="rowkey">
                    <td class="fit">
                        <a v-if="table.rows.length>1" href="#" @click.prevent="remove_row(rowkey)"
                            class="btn btn-default btn-xs text-red" title="Remove row"><i class="fa fa-trash"
                                aria-hidden="true"></i></a>
                    </td>
                    <td v-for="(value,tdkey) in rowvalue" :key="tdkey">
                        <textarea class="form-control" :name="'tditem-tarea-'+tdkey"
                            v-model="table.rows[rowkey][tdkey].value"></textarea>
                            <div><input type="checkbox" v-model="table.rows[rowkey][tdkey].fit"> Fit</div>
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


            add_row: function () {
                let vm = this;
                // initiate empty row
                var row = [];
                // Loop all data on first row of the table and push empty data to new row
                // This is to initiate exact data to be inserted in the new row
                vm.table.rows[0].forEach(function () {
                    row.push(new TableCell);
                });
                // Push new row to table row
                vm.table.rows.push(row);
            },

            remove_row: function (index) {
                let vm = this;

                // If the table row is 1 then do not remove
                if (vm.table.rows.length <= 1) {
                    alert('Could not remove anymore the first row!');
                    return;
                }
                vm.table.rows.splice(index, 1);
            },


            remove_header: function (index) {
                let vm = this;

                // If the table header is 1 then do not remove
                if (vm.table.header.length <= 1) {
                    alert('Could not remove anymore the first header!');
                    return;
                }
                vm.table.removeHeaderIndex(index);
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
