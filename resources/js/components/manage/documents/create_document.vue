<template>
    <form @submit.prevent="submit">
        <document-form ref="docForm" v-model="document">
            <access-form v-model="document.access_data"></access-form>
        </document-form>
        <version-form :document="document" :show_version="false" v-model="document.current_version"></version-form> 
        <button class="btn btn-sm btn-success" type="submit">Submit Document</button>
        <button class="btn btn-sm btn-warning" type="button" @click.prevent="save_draft">Save Draft</button>
    </form>
</template>
<script>
    export default {
        props: {
            draft: {
                type: [Object, Array],
                default: function () {
                    return null;
                }
            }
        },
        data: function () {
            return {
                submitted: false,
                document: new Document, 
            }
        },
        methods: {
            submit: function () {
                let parent = this;
                if (parent.submitted === false) {
                    parent.submitted = true;
                    // Show wait modal
                    parent.show_wait("Please wait while the system is saving the document...");

                    // Generate link for saving document
                    var link = '/manage/documents/save';
                    if (parent.draft !== null)
                        link = '/manage/documents/save?draft=' + parent.draft.id;



                    axios.put(link, {
                        document: parent.document.toObject(),
                        version: parent.document.current_version.toObject(),
                    }).then(function (response) {
                        parent.hide_wait();
                        parent.alert_success(response);
                        setTimeout(() => {
                            window.location.replace(response.data.url);
                        }, 1000);

                    }).catch(function (error) {
                        // Hide wait modal
                        parent.hide_wait();
                        parent.submitted = false;
                        parent.alert_failed(error);
                    });
                }
            },
            save_draft: function () {
                let parent = this;
                if (parent.submitted === false) {
                    parent.submitted = true;
                    // Show wait modal
                    parent.show_wait("Please wait while the system is saving the document as draft...");

                    // Generate link for saving draft
                    var link = '/manage/documents/save_draft';
                    if (parent.draft !== null)
                        link = '/manage/documents/update_draft/' + parent.draft.id;

                    axios.put(link, {
                        document: parent.document.toObject(),
                        version: parent.document.current_version.toObject(),
                    }).then(function (response) {
                        parent.hide_wait();
                        parent.alert_success(response);
                        setTimeout(() => {
                            window.location.replace(response.data.url);
                        }, 1000);

                    }).catch(function (error) {
                        // Hide wait modal
                        parent.hide_wait();
                        parent.submitted = false;
                        parent.alert_failed(error);
                    });
                }
            }
        },
        mounted() {

            this.$nextTick(function () {
                if (this.draft !== null) {

                    this.document.title = this.draft.document_title;
                    this.document.section = this.draft.document_section_code;
                    this.document.system = this.draft.document_system_code;
                    this.document.category = this.draft.document_category_code;
                    this.document.keywords = this.draft.document_keywords;
                    this.document.comment = this.draft.document_comment;
                    // Tell document component that there is changes on the system field
                    this.$refs.docForm.load_sections();


                    this.document.current_version.reviewers = this.draft.version_reviewer_ids;
                    this.document.current_version.approvers = this.draft.version_approver_ids;
                    this.document.current_version.effective_date = this.draft.version_effective_date;
                    this.document.current_version.content = this.cast_to_content(this.draft.version_content);
                    this.document.current_version.description = this.cast_to_content(this.draft.version_description);

                } else {
                    this.document.current_version.content.addContentItem();
                    this.document.current_version.description.addContentItem();
                }

            });
        }

    }

</script>
