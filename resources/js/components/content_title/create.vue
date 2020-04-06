<template>
    <modal :name="'create-content-title'+_uid" ref="modal">
        <template slot="header">Create Content Title</template>
        <template slot="body">
            <content-title-form v-model="content_title"></content-title-form>
        </template>

        <template slot="footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-default" @click.prevent="save">Save</button>

        </template>

    </modal>
</template>

<script>
    export default {

        data: function () {
            return {
                content_title: new ContentTitle,
                submitted: false,
            }
        },
        methods: {
            show: function () {
                this.$refs.modal.show();
            },
            save: function () {
                var par = this;

                if (par.submitted === false) {
                    par.submitted = true;
                    axios.put('/content-titles/save', {
                        code: par.content_title.code,
                        name: par.content_title.name
                    }).then(function (response) {
                        par.alert_success(response);
                        par.submitted = false;
                        par.$emit('created');
                        par.content_title=new ContentTitle;
                        par.$refs.modal.dismiss();
                    }).catch(function (error) {
                        par.submitted = false;
                        par.alert_failed(error);
                    });
                }


            }
        }
    }

</script>
