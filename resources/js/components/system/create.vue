<template>
    <modal :name="'create-system'+_uid" ref="modal">
        <template slot="header">Create System</template>
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
    import SystemForm from './form.vue';

    export default {
        components: {
            'system-form': SystemForm
        },
        data: function () {
            return {
                system: {
                    name: '',
                    code: '',
                },
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
                    axios.put('/systems/save', {
                        code: par.system.code,
                        name: par.system.name
                    }).then(function (response) {
                        par.alert_success(response);
                        par.submitted = false;
                        par.$emit('created');
                        par.system.code='';
                        par.system.name='';
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
