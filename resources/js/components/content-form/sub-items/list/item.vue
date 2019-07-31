<template>
    <div v-if="item!==null">

        <textarea class="form-control" v-model="item.value" ref="item_input" rows="2"></textarea>
        <a href="#" @click.prevent="bold" type="button"><strong>Bold</strong></a>
        <span>&nbsp;&nbsp;</span>
        <a href="#" @click.prevent="italic" type="button"><strong>Italic</strong></a>

        <a href="#" class="text-blue pull-right text-uppercase" @click.prevent="item.addOrderedList()">
            <i class="fa fa-plus" aria-hidden="true"></i>
            <strong>ADD LIST</strong>
        </a>
        <table v-if="item.ordered_list.length!==0" class="table">
            <tbody>
                <tr v-for="(ordered_list,listKey) in item.ordered_list" :key="listKey">
                    <td>
                        <a href="#" @click.prevent="item.removeOrderedListIndex(listKey)"
                            class="text-red text-uppercase">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                            <strong>Remove List</strong>
                        </a>
                        <list v-model="item.ordered_list[listKey]"></list>
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
            }
        },
        data: function () {
            return {
                item: new ListItem(),
            }
        },
        mounted: function () {
            this.item = this.value;
        },
        methods: {
            bold: function () {
                this.item.value = this.replace_highlight("bold", this.$refs.item_input);
                this.item.meta.html = this.format_html(this.item.value);
            },
            italic: function () {
                this.item.value = this.replace_highlight("italic", this.$refs.item_input);
                this.item.meta.html = this.format_html(this.item.value);
            },
        },
        watch: {
            item: {
                deep: true,
                handler: function (val) {
                    this.$emit('input', val);
                }
            },
            value: {
                deep: true,
                handler: function (value) {
                    this.item = value;
                }
            }
        },

    }

</script>
