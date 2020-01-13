<template>
    <div class="content-items">

        <draggable v-model="data" :group="'content-items'+_uid" @start="drag=true" @end="drag=false"
            handle=".citem-drag">
            <div class="item" v-for="(value,key) in data" :key="key">
                <datum @edit="edit_item" :id="key" @removed="remove_item" v-model="data[key]"></datum>
            </div>
        </draggable>

        <par @saved="add_item" @updated="updated_item" ref="par"></par>
        <tbl @saved="add_item" ref="table" @updated="updated_item"></tbl>
        <com-image @saved="add_item" @updated="updated_item" ref="image"></com-image>
    </div>

</template>
<script>
    import datumTemplate from './form_src/datum.vue';

    export default {
        props: ['value'],
        components: {
            'datum': datumTemplate,
        },
        methods: {
            show_add_paragraph() {
                this.$refs.par.show()
            },
            show_add_image() {
                this.$refs.image.show()
            },
            show_add_table() {
                this.$refs.table.show()
            },
            show_add_item_list: function () {
                this.data.push(new OrderedList);
            },


            add_item: function (item) {
                this.data.push(item);
            },
            remove_item: function (index) {
                this.data.splice(index, 1);
            },
            updated_item: function (item) {
                this.data[item.id] = item.updated_data;
                this.$forceUpdate();
            },
            edit_item: function (key, value) {
                switch (value.type) {
                    case 'table':
                        this.$refs.table.edit(value, key);
                        break;
                    case 'paragraph':
                        this.$refs.par.edit(value, key);
                        break;
                    case 'image':
                        this.$refs.image.edit(value, key);
                        break;
                }

            }
        },
        data: function () {
            return {
                data: []
            }
        },
        mounted() {
            this.data = this.value;
        },
        watch: {
            data: {
                deep: true,
                handler: function (data) {
                    this.$emit('input', data);
                }
            },
            value: {
                deep: true,
                handler: function (value) {
                    this.data = value;
                }
            }
        }
    }

</script>
