<template>
    <form @submit.prevent="submit">
        <document-form ref="docForm" v-model="document">
            <div class="form-group">
                <label><i class="fa fa-hashtag" aria-hidden="true"></i> Serial</label>
                <input @change="check_document_exists" type="number" class="form-control" v-model="document.serial">
                <validation :errors="errors" field="serial"></validation>
            </div>
        </document-form>
        <button class="btn btn-sm btn-warning" type="submit">Update Document</button>
    </form>
</template>
<script>
    export default {
        props: {
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

                }).then(response => {
                    vm.errors = [];
                }).catch(error => {
                    vm.errors = error.response.data.errors;
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
            vm.document.serial = vm.serial;
            vm.$refs.docForm.load_sections();





        },


    }

</script>
