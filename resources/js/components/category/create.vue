<template>
    <modal :name="'create-category'+_uid" ref="modal">
        <template slot="header">Create Category</template>
        <template slot="body">
            <category-form v-model="category"></category-form>
        </template>

        <template slot="footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-default" @click.prevent="save">Save</button>

        </template>

    </modal>
</template>

<script>
    import CategoryForm from './form.vue';

    export default {
        components: {
            'category-form': CategoryForm
        },
        data: function () {
            return {
                category: {
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
                    par.show_wait("Please wait while the system is processing your request....");
                    par.submitted = true;
                    axios.put('/categories/save', {
                        code: par.category.code,
                        name: par.category.name
                    }).then(function (response) {
                        par.alert_success(response);
                        par.submitted = false;
                        par.$emit('created');
                        par.category.code = '';
                        par.category.name = '';
                        par.$refs.modal.dismiss();
                    }).catch(function (error) {
                        par.submitted = false;
                        par.alert_failed(error);
                    }).finally(() => {
                        par.hide_wait();
                    });
                }


            }
        }
    }

</script>
