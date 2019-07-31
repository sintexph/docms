<template>
    <modal :name="'preview'+_uid" ref="modal" :extended_width="true">
        <template slot="header">List Preview</template>
        <template slot="body">
            <div v-html="list.toString()"></div>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </template>
    </modal>
</template>
<script>
    export default {
        data: function () {
            return {
                id: '',
                list: new OrderedList,

            }
        },
        methods: {
            show: function (list) {
                this.list = list;
                this.$refs.modal.show();
            },

        }
    }

</script>
