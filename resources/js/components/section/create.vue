<template>
    <modal :name="'create-section'+_uid" ref="modal">
        <template slot="header">Create Section</template>
        <template slot="body">
            <section-form v-model="section"></section-form>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-default" @click.prevent="save">Save</button>
        </template>
    </modal>
</template>

<script>
    import SectionForm from './form.vue';

    export default {
        components: {
            'section-form': SectionForm
        },
        data: function () {
            return {
                section: {
                    code: '',
                    name: '',
                    system_code: '',
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
                    axios.put('/sections/save', {
                        code: par.section.code,
                        name: par.section.name,
                        system_code: par.section.system_code
                    }).then(function (response) {
                        par.alert_success(response);
                        par.submitted = false;
                        par.$emit('created');
                        par.section.code = '';
                        par.section.name = '';
                          par.section.system_code = '';
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
