<template>
    <modal :name="'table'+_uid" ref="modal" :extended_width="true">
        <template slot="header">Create Table</template>
        <template slot="body">
            <c-table ref="cTable" v-model="table"></c-table>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" @click.prevent="save">Save</button>
        </template>
    </modal>


</template>
<script>
    export default {
        data: function () {
            return {
                id: '',
                table: new Table(),
            }
        },
        methods: {
            show: function () {
                this.$refs.cTable.reset();
                this.$refs.modal.show();
            },

            edit: function (table, id) {
                this.$refs.cTable.reset();
                this.id = id;
                this.table.header = table.header;
                this.table.rows = table.rows;
                this.$refs.modal.show();
            },
            save: function () {
                if (this.id === '') {
                    // Save
                    this.$emit('saved', this.table);
                } else {
                    // update
                    this.$emit('updated', {
                        id: this.id,
                        updated_data:this.table
                    });
                }


                this.$refs.modal.dismiss();

            }
        },


    }

</script>
