
<template>
    <modal :name="'edit-content-title'+_uid" ref="modal">
        <template slot="header">Edit Content Title</template>
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
                id: '',
                content_title: new ContentTitle,
                submitted: false,
            }
        },
        methods: {
            show: function (id) {
                this.id = id;
                this.content_title=new ContentTitle;
                this.fetch();
                this.$refs.modal.show();

            },
            save: function () {
                var par = this;

                if (par.submitted === false) {
                    par.submitted = true;
                    axios.patch('/content-titles/update/' + par.id, {
                        code: par.content_title.code,
                        name: par.content_title.name
                    }).then(function (response) {
                        par.alert_success(response);
                        par.submitted = false;
                        par.content_title=new ContentTitle;
                        par.$emit('updated');
                        par.$refs.modal.dismiss();
                    }).catch(function (error) {
                        par.submitted = false;
                        par.alert_failed(error);
                    });
                }


            },
            fetch: function () {
                var par = this;
                axios.post('/content-titles/fetch/' + par.id).then(function (response) {
                    par.content_title.code = response.data.code;
                    par.content_title.name = response.data.name;
 
                });
            }
        }
    }

</script>
