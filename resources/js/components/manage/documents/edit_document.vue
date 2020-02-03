<template>
    <form @submit.prevent="submit">
        <document-form ref="docForm" v-model="document">
            <template v-if="can_change_creator===true">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><i class="fa fa-user-circle" aria-hidden="true"></i> Document Owner</label>
                            <select2 :options="creator_options" v-model="document.created_by" style="width:100%;">
                            </select2>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><i class="fa fa-hashtag" aria-hidden="true"></i> Serial</label>
                            <input @change="check_document_exists" type="number" class="form-control"
                                v-model="document.serial">
                            <validation :errors="errors" field="serial"></validation>
                        </div>
                    </div>
                </div>
            </template>
        </document-form>
        <button class="btn btn-sm btn-warning" type="submit">Update Document</button>
    </form>
</template>
<script>
    export default {
        props: {
            can_change_creator: {
                type: Boolean,
                require: true,
                default () {
                    return false;
                }
            },
            created_by: {
                type: [Number, String],
                required: true,
            },
            title: {
                type: String
            },
            section: {
                type: String
            },
            system: {
                type: String
            },
            category: {
                type: String
            },
            serial: {
                type: String
            },
            keywords: {
                type: String
            },
            comment: {
                type: String
            },
            document_id: {
                type: String,
            },
            serial: {
                type: [String, Number],
            }
        },
        data: function () {
            return {
                submitted: false,
                document: new Document,
                creator_options: [],
                errors: [],
            }
        },

        methods: {


            check_document_exists() {
                var vm = this;
                axios.post('/util/serial-exists', {

                    category_code: vm.document.category,
                    serial: vm.document.serial,
                    document_id: vm.document_id,

                }).then(response=>{
                    vm.errors=[];
                }).catch(error=>{
                    vm.errors=error.response.data.errors;
                });
            },

            submit: function () {
                let parent = this;
                if (parent.submitted === false) {
                    parent.submitted = true;
                    axios.patch('/manage/documents/update_document/' + parent.document_id, parent.document
                        .toObject()).then(
                        function (response) {
                            parent.alert_success(response);
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }).catch(function (error) {
                        parent.error = error.errors;
                        parent.submitted = false;
                        parent.alert_failed(error);
                    });
                }

            }
        },

        mounted() {



            let vm = this;

            vm.document.title = vm.title;
            vm.document.section = vm.section;
            vm.document.system = vm.system;
            vm.document.category = vm.category;
            vm.document.serial = vm.serial;
            vm.document.keywords = vm.keywords;
            vm.document.comment = vm.comment;
            vm.document.created_by = vm.created_by;
            vm.document.serial = vm.serial;
            vm.$refs.docForm.load_sections();



            // Load the creator list
            axios.post('/util/users-select2').then(response => {
                vm.creator_options = response.data.results;
            });



        },


    }

</script>
