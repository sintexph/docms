<template>
    <modal :name="'edit-category'+_uid" ref="modal">
        <template slot="header">Edit Category</template>
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
                id: '',
                category: {
                    name: '',
                    code: '',
                },
                submitted: false,
            }
        },
        methods: {
            show: function (id) {
                this.id = id;
                this.category.code='';
                this.category.name='';
                 this.fetch();
                this.$refs.modal.show();
               
            },
            save: function () {
                var par = this;

                if (par.submitted === false) {
                    par.submitted = true;
                    axios.patch('/categories/update/' + par.id, {
                        code: par.category.code,
                        name: par.category.name
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
            fetch:function(){
                var par=this;
                axios.post('/categories/fetch/'+par.id).then(function(response){
                    par.category.code=response.data.code;
                    par.category.name=response.data.name;
                });
            }
        }
    }

</script>
