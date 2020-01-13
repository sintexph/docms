<template>
    <div class="box box-solid">
        <div class="box-body with-border">
            <list-style v-model="list.meta.style">
                <slot></slot>
            </list-style>
            <a href="#" class="btn btn-xs btn-default" title="Add table" @click.prevent="$refs.table.show()"><i
                    class="fa fa-table" aria-hidden="true"></i></a>
            <a href="#" class="btn btn-xs btn-default" title="Add paragraph" @click.prevent="$refs.par.show()"><i
                    class="fa fa-file-o" aria-hidden="true"></i></a>

            <a href="#" class="btn btn-xs btn-default" title="Add image" @click.prevent="$refs.image.show()"><i
                    class="fa fa-picture-o" aria-hidden="true"></i></a>
            <a href="#" class="btn btn-xs btn-default" title="Preview list"
                @click.prevent="$refs.listPreview.show(list)"><i class="fa fa-eye" aria-hidden="true"></i></a>

            <label class="btn btn-xs btn-default" title="Inheret the label of parent"><input style="margin:0px;" type="checkbox"
                    v-model="list.has_parent"></label>

        </div>
        <div class="box-body">
            <table class="tbl-style">
                <tbody>
                    <draggable v-model="list.list_items" :group="'od_list_item'+_uid" @start="drag=true" @end="drag=false"
                        handle=".control-odi">
                        <tr v-for="(value,key) in list.list_items" :key="key">
                            <td class="fit">
                                <input v-model="list.list_items[key].is_list" type="checkbox">
                            </td>
                            <td>
                                <span v-html="value.data.toString()"></span>
                                <div class="item-btn-action">
                                    <a href="#" title="Edit item" class="btn btn-xs btn-default text-yellow"
                                        v-if="value.data.type==='table'"
                                        @click.prevent="$refs.table.edit(value.data,key)">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                    <a href="#" title="Edit item" class="btn btn-xs btn-default text-yellow"
                                        v-else-if="value.data.type==='paragraph'"
                                        @click.prevent="$refs.par.edit(value.data,key)">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                    <a href="#" title="Edit item" class="btn btn-xs btn-default text-yellow"
                                        v-else-if="value.data.type==='image'"
                                        @click.prevent="$refs.image.edit(value.data,key)">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                    <a href="#" class="btn btn-xs btn-default text-red" title="Remove list"
                                        @click.prevent="remove_item(key)">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                    <a href="#" class="btn btn-xs btn-default" title="Add list"
                                        @click.prevent="list.list_items[key].addOrderedList()">
                                        <i class="fa fa-list" aria-hidden="true"></i>
                                    </a>

                                    <a href="#" title="Move Order" class="btn btn-xs btn-default text-blue control-odi"
                                        @click.prevent="">
                                        <i class="fa fa-arrows" aria-hidden="true"></i></a>
                                </div>
                                <div style="padding-top:10px;" v-for="(orValue,orKey) in value.ordered_list"
                                    :key="orKey">
                                    <c-list v-model="value.ordered_list[orKey]">
                                        <a href="#" class="btn btn-xs btn-default text-red pull-right"
                                            title="Remove list" @click.prevent="remove_order_list(key,orKey)"><i
                                                class="fa fa-trash" aria-hidden="true"></i></a>
                                    </c-list>
                                </div>
                            </td>
                        </tr>
                    </draggable>
                </tbody>
            </table>
            <par @saved="add_item" @updated="updated_item" ref="par"></par>
            <tbl @saved="add_item" ref="table" @updated="updated_item"></tbl>
            <com-image @saved="add_item" @updated="updated_item" ref="image"></com-image>
            <list-preview ref="listPreview"></list-preview>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            value: {
                type: [Object, Array],
                default: function () {
                    return new OrderedList();
                }
            }
        },
        data: function () {
            return {
                selected_item_form: '',
                list: new OrderedList()
            }
        },
        mounted() {
            this.list = this.value;
        },
        watch: {
            list: {
                deep: true,
                handler: function (val) {
                    this.$emit('input', val);
                }
            },
            value: {
                deep: true,
                handler: function (val) {
                    this.list = val;
                }
            },

        },
        methods: {
            remove_order_list: function (item_index, ordered_list_index) {
                if (confirm('Are you sure you want to remove the list?') === true)
                    this.list.list_items[item_index].removeOrderedListIndex(ordered_list_index);
            },
            add_item: function (item) {
                this.list.addListItem(new ListItem(item));
            },
            remove_item: function (index) {
                if (confirm('Are you sure you want to remove the item from list?') === true)
                    this.list.removeListItemIndex(index);
            },
            updated_item: function (item) {
                this.list.list_items[item.id].data = item.updated_data;
                this.$forceUpdate();
            }
        }
    }

</script>
<style>
    .tbl-style {
        width: 100%;
        margin-top: 10px;
        text-align: left;
    }

    .tbl-style tr {
        border-bottom: 1px solid #44444446;
    }


    .tbl-style td,
    .tbl-style th {
        padding-top: 10px;
        padding-bottom: 10px;
        padding-right: 10px;
        vertical-align: baseline;

    }

    .tbl-style td.fit,
    .tbl-style th.fit {

        white-space: nowrap;
        width: 1%;
    }

</style>
