<template>
    <modal :name="'content-description'+_uid" ref="modal" :extended_width="true">
        <template slot="header">Content Preview</template>
        <template slot="body">
            <div v-html="content.toString()"></div>
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
                content: new Content,
            }
        },
        methods: {
            show: function (cl) {
                this.content=new Content;
                this.content = cl;
                this.$refs.modal.show();
            },

        }
    }

</script>
