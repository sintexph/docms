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
                <div class="box box-solid" v-for="(value,key) in content.content_items" :key="key">
                    <div class="box-header with-border">
                        <a href="#"
                            @click.prevent="change_content_title==true?change_content_title=false:change_content_title=true"
                            title="Edit title" class="btn btn-xs btn-default text-yellow">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </a>
                        <strong v-text="content.content_items[key].name"></strong>
                        <div class="pull-right">
                            <button type="button" class="btn btn-xs btn-default"
                                @click="content.content_items[key].box_hidden===true?content.content_items[key].box_hidden=false:content.content_items[key].box_hidden=true">
                                <i class="fa fa-minus"></i>
                            </button>
                            <a href="#" title="Remove item" @click.prevent="remove_content_item(key)"
                                class="btn btn-xs btn-default text-red"><i class="fa fa-trash"
                                    aria-hidden="true"></i></a>
                            <a href="#" title="Move Order" class="btn btn-xs btn-default text-blue control-drag-mover"
                                @click.prevent="">
                                <i class="fa fa-arrows" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                    <div v-if="content.content_items[key].box_hidden===false" class="box-body">
                        <template v-if="change_content_title===true">
                            <div class="form-group">
                                <div class="input-group  main-item-input">
                                    <span class="input-group-addon">
                                        <input type="checkbox"
                                            @change="head_change(content.content_items[key].meta.with_header,key)"
                                            v-model="content.content_items[key].meta.with_header" title="With header">
                                    </span>
                                    <input type="text" :disabled="content.content_items[key].meta.with_header===false"
                                        v-model="content.content_items[key].name" class="form-control">

                                    <span class="input-group-addon">
                                        <a href="#" @click.prevent="change_content_title=false" class="text-green"><i
                                                class="fa fa-check" aria-hidden="true"></i></a>
                                    </span>
                                </div>
                            </div>
                        </template>
                        <content-form v-model="content.content_items[key].data"></content-form>
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
            'content-preview': contentPreview
        },
        props: {
            value: {
                required:true,
                type: [Array, Object],
                default: function () {
                    return new Content;
                }
            }
        },
        data: function () {
            return {
                content: new Content,
                change_content_title: false,
            }
        },
        methods: {
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
        }
    }

</script>
<style>
    .main-item-input {
        margin: 10px 0px 10px 0px;
    }
    .item-holder {
        margin: 10px 0px 10px 0px;
    }
</style>
