<template>
    <modal :name="'edit-system'+_uid" ref="modal">
        <template slot="header">Edit System</template>
        <template slot="body">
            <system-form v-model="system"></system-form>
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
                system: new SystemClass,
                submitted: false,
            }
        },
        methods: {
            show: function (id) {
                this.id = id;
                this.system.code = '';
                this.system.name = '';
                this.fetch();
                this.$refs.modal.show();

            },
            save: function () {
                var par = this;

                if (par.submitted === false) {
                    par.submitted = true;
                    axios.patch('/systems/update/' + par.id, {
                        code: par.system.code,
                        name: par.system.name,
                        reviewer_ids: par.system.reviewer_ids,
                        approver_ids: par.system.approver_ids,
                    }).then(function (response) {
                        par.alert_success(response);
                        par.submitted = false;
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
                axios.post('/systems/fetch/' + par.id).then(function (response) {
                    par.system.code = response.data.code;
                    par.system.name = response.data.name;
                    par.system.reviewer_ids = response.data.reviewer_ids;
                    par.system.approver_ids = response.data.approver_ids;
 
                });
            }
        }
    }

</script>
