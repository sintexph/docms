<template>
    <form @submit.prevent="submit">
        <document-form ref="docForm" v-model="document"></document-form>
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
            document_id:{
                type:String,
            }
        },
        data: function () {
            return {
                submitted: false,
                document: {
                    number: '',
                    title: '',
                    section: '',
                    system: '',
                    category: '',
                    serial: '',
                    keywords: '',
                    comment: '',
                },
            }
        },

        methods: {

            submit: function () {
                let parent = this;
                if (parent.submitted === false) {
                    parent.submitted = true;
                    axios.patch('/manage/documents/update_document/'+parent.document_id,parent.document).then(function (response) {
                        parent.alert_success(response);
                        location.reload();
                    }).catch(function (error) {
                        parent.submitted = false;
                        parent.alert_failed(error);
                    });
                }

            }
        },

        mounted() {
            this.document.title = this.title;
            this.document.section = this.section;
            this.document.system = this.system;
            this.document.category = this.category;
            this.document.serial = this.serial;
            this.document.keywords = this.keywords;
            this.document.comment = this.comment;
            this.$refs.docForm.system_changed();
        }

    }

</script>
