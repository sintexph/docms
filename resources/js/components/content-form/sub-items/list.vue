<template>
    <div class="nav-tabs-custom list-tab">
        <ul class="nav nav-tabs">
            <li class="active"><a :href="'#tab_rep'+_uid" data-toggle="tab">Display</a></li>
            <li><a :href="'#tab_struct'+_uid" data-toggle="tab">Structure</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" :id="'tab_rep'+_uid">
                <div v-html="list.toString()"></div>
            </div>
            <div class="tab-pane" :id="'tab_struct'+_uid">
                <c-list v-model="list"></c-list>
            </div>
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
                list: new OrderedList()
            }
        },
        mounted() {
            this.$nextTick(function () {
                // Value must be loaded after the whole component is loaded
                this.list = this.value;
            })

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

    }

</script>

<style>
    .list-tab>.tab-content {
        min-height: 0px;
    }

</style>
