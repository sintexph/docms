<template>
    <modal :name="'edit-section'+_uid" ref="modal">
        <template slot="header">Edit Section</template>
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
                id: '',
                section: {
                    code: '',
                    name: '',
                    system_code: '',
                },
                submitted: false,
            }
        },
        methods: {
            show: function (id) {
                this.id = id;
                this.section.code = '';
                this.section.name = '';
                this.section.system_code='';
                this.fetch();
                this.$refs.modal.show();

            },
            save: function () {
                var par = this;

                if (par.submitted === false) {
                    par.submitted = true;
                    axios.patch('/sections/update/' + par.id, {
                        code: par.section.code,
                        name: par.section.name,
                        system_code: par.section.system_code
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
                axios.post('/sections/fetch/' + par.id).then(function (response) {
                    par.section.code = response.data.code;
                    par.section.name = response.data.name;
                    par.section.system_code = response.data.system_code;
                });
            }
        }
    }

</script>
