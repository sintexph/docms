<template>
    <div class="box box-solid">
        <div class="box-header">
            <div class="pull-right item-btn-action">
                <template v-if="datum.hidden===false && datum.type!=='list'">
                    <a href="#" class="btn btn-xs btn-default text-yellow" @click.prevent="edit_item"
                        title="Edit table"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                </template>
                <a href="#" class="btn btn-xs btn-default text-red" @click.prevent="item_remove" title="Remove table"><i
                        class="fa fa-trash" aria-hidden="true"></i></a>
                <a href="#" title="Move Order" class="btn btn-xs btn-default text-blue citem-drag" @click.prevent="">
                    <i class="fa fa-arrows" aria-hidden="true"></i></a>
                <a href="#" title="Hide/Show item" @click.prevent="hide" class="btn btn-xs btn-default">
                    <i class="fa fa-minus"></i>
                </a>
            </div>
            <div class="pull-left" v-if="datum.hidden===true" v-html="datum.toShortString()"></div>
            <div class="pull-left" v-if="datum.hidden===false">
                <strong>{{ datum.type.toUpperCase() }}</strong>
            </div>
        </div>
        <div class="box-body" v-if="datum.hidden===false">
            <template v-if="datum.type==='list'">
                <list v-model="datum"></list>
            </template>
            <div v-else v-html="datum.toString()"></div>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            id: [Number, String],
            value: {
                type: [Object, Array],
                required: true,
                default: function () {
                    return new Datum;
                }
            }
        },
        data() {
            return {
                datum: new Datum
            }
        },
        mounted() {
            this.$nextTick(function () {
                this.datum = this.value;
            });
        },
        methods: {
            item_remove: function () {
                if (confirm('Are you sure you want to remove the ' + this.datum.type + '?') === true)
                    this.$emit('removed', this.id);
            },
            edit_item: function () {
                this.$emit('edit', this.id, this.datum);
            },
            hide: function () {
                if (this.datum.hidden === false)
                    this.datum.hidden = true;
                else
                    this.datum.hidden = false;
            }
        },
        watch: {
            datum: {
                deep: true,
                handler: function (datum) {
                    this.$emit('input', datum);
                }
            },
            value: {
                deep: true,
                handler: function (value) {
                    this.datum = value;
                }
            }
        }
    }

</script>
