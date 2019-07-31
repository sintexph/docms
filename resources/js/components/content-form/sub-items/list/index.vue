<template>
    <modal :name="'index'+_uid" ref="modal" :extended_width="true">
        <template slot="header">Add List</template>
        <template slot="body">
            <list v-model="list"></list>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-default" @click.prevent="save">Save</button>
        </template>
    </modal>
</template>
<script>
    import ListUtil from "./Util.js";
    export default {
        mixins: [ListUtil],
        data: function () {
            return {
                id: '',
                list: new OrderedList(),
            }
        },
        methods: {
            show: function () {
                this.$refs.modal.show();
            },
            edit: function (data, id) {
                



                this.id = id;

                if ((data instanceof OrderedList) === false) {
                    // forcely cast the object to List class
                    this.list = this.cast_to_list(data);
                } else
                    this.list = data;



                this.show();
            },
            save: function () {


                if (this.id === '') {
                    // Save
                    this.$emit('saved', this.output);

                } else {
                    // update
                    this.$emit('updated', {
                        id: this.id,
                        updated_data: this.output
                    });
                    this.id = '';
                }

                // Clear the list
                this.list = new OrderedList();
                // Add new default item
                this.list.addListItem();
                this.$refs.modal.dismiss();
            }
        },
        mounted() {
            this.list.addListItem();
        },
        computed: {
            output: function () {
                return {
                    type: 'list',
                    data: this.list
                }
            }
        }
    }

</script>
