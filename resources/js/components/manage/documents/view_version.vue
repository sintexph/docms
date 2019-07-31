<template>
    <modal :name="'view-version'+_uid" ref="modal" :extended_width="true">
        <template slot="header">View Full</template>
        <template slot="body">
          <iframe :src="src" width="100%" height="600"></iframe>
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
                src: '',
                
            }
        },
        methods: {
            show: function (src) {
                this.src = src;
                this.$refs.modal.show();
            }
        }
    }

</script>
