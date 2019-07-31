<template>
    <div>
        <a href="#" class="text-blue text-uppercase" @click.prevent="list.addListItem()">
            <i class="fa fa-plus" aria-hidden="true"></i>
            <strong>ADD ITEM</strong>
        </a>

        <div class="pull-right">
            <select v-model="list.meta.style">
                <option value="">-- select style --</option>
                <option value="lower-alpha">lower-alpha</option>
                <option value="circle">circle</option>
                <option value="square">square</option>
                <option value="upper-roman">upper-roman</option>
            </select>
        </div>
        <table class="table table-bordered table-hover">
            <tbody>
                <tr v-for="(item,key) in list.list_items" :key="key">
                    <td>
                        <a href="#" @click.prevent="list.removeListItemIndex(key)" class="text-red"><i class="fa fa-trash"
                                aria-hidden="true"></i></a>
                        <list-item v-model="list.list_items[key]"></list-item>
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
                    return new OrderedList();
                }
            }
        },
        data:function(){
            return {
                list:new OrderedList()
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

        }
    }

</script>
