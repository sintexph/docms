<template>
    <div class="box box-solid">
        <div class="box-header with-border">
            <list-style class_name="pull-left" v-model="content.style"></list-style>
            <div class=" pull-right">
                <a class="btn btn-xs btn-default" href="#" @click.prevent="content.addContentItem()">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                </a>
                <a class="btn btn-xs btn-default" href="#" @click.prevent="$refs.contentPreview.show(content)">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                </a>
            </div>
        </div>
        <div class="box-body">
            <draggable v-model="content.content_items" :group="'content'+_uid" @start="drag=true" @end="drag=false"
                handle=".control-drag-mover">
                <div class="box box-solid main-item" v-for="(value,key) in content.content_items" :key="key">
                    <div class="box-header with-border">

                        <input type="checkbox" @change="head_change(content.content_items[key].meta.with_header,key)"
                            v-model="content.content_items[key].meta.with_header" title="With header">

                        <span v-if="content.content_items[key].meta.with_header===false" style="margin-left:5px;"
                            class="text-gray"><i>No title applied...</i></span>

                        <select v-else class="input-title" v-model="content.content_items[key].name"
                            placeholder="Title should be here ....">
                            <option value="">-- SELECT TITLE --</option>
                            <option v-for="(value,key) in content_titles" :key="key" :value="value.code">
                                {{ value.name }}</option>
                        </select>
                        <div class="pull-right">
                            <div class="btn-group">
                                <button type="button" title="Settings" class="btn btn-xs btn-default dropdown-toggle"
                                    data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-wrench"></i></button>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <template v-if="content.content_items[key].box_hidden===true">
                                        <li><a href="" @click.prevent=""><i>You need to unhide the box so the tools will
                                                    show up</i></a></li>
                                    </template>
                                    <template v-else>
                                        <li><a href="#" @click.prevent="$refs.contentForm[key].show_add_table()"><i
                                                    class="fa fa-table" aria-hidden="true"></i> Table</a></li>
                                        <li><a href="#" @click.prevent="$refs.contentForm[key].show_add_item_list()"><i
                                                    class="fa fa-list" aria-hidden="true"></i> List</a></li>
                                        <li><a href="#" @click.prevent="$refs.contentForm[key].show_add_paragraph()"><i
                                                    class="fa fa-paragraph" aria-hidden="true"></i> Paragraph</a>
                                        </li>
                                        <li><a href="#" @click.prevent="$refs.contentForm[key].show_add_image()"><i
                                                    class="fa fa-file-image-o" aria-hidden="true"></i> Image</a></li>

                                    </template>

                                    <li class="divider"></li>

                                    <li><a href="#" @click.prevent="remove_content_item(key)"><i
                                                class="fa fa-trash text-red" aria-hidden="true"></i> Delete
                                            Item</a>
                                    </li>
                                </ul>
                            </div>

                            <a href="#" title="Move Order" class="btn btn-xs btn-default text-blue control-drag-mover"
                                @click.prevent="">
                                <i class="fa fa-arrows" aria-hidden="true"></i>
                            </a>

                            <button type="button" class="btn btn-xs btn-default"
                                :title="content.content_items[key].box_hidden===true?'Unhide':'Hide'"
                                @click="content.content_items[key].box_hidden===true?content.content_items[key].box_hidden=false:content.content_items[key].box_hidden=true">
                                <i class="fa fa-minus"></i>
                            </button>

                        </div>
                    </div>
                    <div v-show="content.content_items[key].box_hidden===false" class="box-body">
                        <content-form ref="contentForm" v-model="content.content_items[key].data"></content-form>
                    </div>
                </div>
            </draggable>


            <content-preview ref="contentPreview"></content-preview>
        </div>
    </div>
</template>
<script>
    import contentPreview from './content-preview.vue';

    export default {
        components: {
            'content-preview': contentPreview,
        },
        props: {
            value: {
                required: true,
                type: [Array, Object],
                default: function () {
                    return new Content;
                }
            }
        },
        data: function () {
            return {
                content: new Content,
                content_titles: [],
                change_content_title: false,
            }
        },
        methods: {

            loadContentTitles() {
                axios.post('/util/content-titles').then(response => {
                    this.content_titles = response.data;
                }).catch(error => {
                    this.alert_failed(error);
                });
            },

            head_change: function (value, index) {
                if (value === false)
                    this.content.content_items[index].name = '';
            },
            remove_content_item(index) {
                if (confirm('Are you sure you want to remove the ' + this.content.content_items[index].name + '?'))
                    this.content.removeContentItemIndex(index);
            }
        },
        mounted: function () {
            this.$nextTick(function () {
                if (this.value !== null && this.value !== undefined) {
                    this.content = this.value;
                }
            });
        },
        watch: {
            content: {
                deep: true,
                handler: function (val) {
                    this.$emit('input', val);
                }
            },
            value: {
                deep: true,
                handler: function (val) {
                    this.content = val;
                }
            }
        },
        mounted() {

            this.$nextTick(() => {

                this.loadContentTitles();

                $(document).on('click', '.main-item *', function () {

                    $('.box').not($(this).parent()).removeClass('main-item-focus');
                    $(this).parent().addClass('main-item-focus');

                });
            })

        }
    }

</script>
<style>
    .input-title {
        margin-left: 5px;
        display: inline-block;
        -webkit-box-sizing: content-box;
        -moz-box-sizing: content-box;
        box-sizing: content-box;
        border: 0 solid #b7b7b7;
        border-bottom-width: 1px;
        padding: 3px 0px 0px 0px;
        -o-text-overflow: clip;
        text-overflow: clip;

        background: rgba(252, 252, 252, 1);
    }

    .input-title:focus {
        border: 0;
    }

    .main-item-input {
        margin: 10px 0px 10px 0px;
    }

    .item-holder {
        margin: 10px 0px 10px 0px;
    }

    .box.main-item-focus {
        -webkit-box-shadow: 0px 0px 3px 1px rgba(27, 111, 150, 1);
        -moz-box-shadow: 0px 0px 3px 1px rgba(27, 111, 150, 1);
        box-shadow: 0px 0px 3px 1px rgba(27, 111, 150, 1);
    }

</style>
