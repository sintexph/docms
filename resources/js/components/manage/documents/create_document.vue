<template>
    <form @submit.prevent="createDocument">
        <document-form ref="docForm" v-model="document">
            <access-form v-model="document.access"></access-form>
        </document-form>
        <version-form :document="document" :show_version="false" v-model="document.current_version"></version-form>
        <div class="btn-float">
            <button class="btn btn-success" title="Create document">
             
                 <i class="fa fa-floppy-o" aria-hidden="true"></i>
            </button>
            <button class="btn btn-warning" title="Save document as draft" @click.prevent="save_draft">
               <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
            </button>
            <button @click.prevent="closeWindow" class="btn btn-danger" title="Close window creation">
                <i class="fa fa-window-close" aria-hidden="true"></i>
            </button>
        </div>
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
            closeWindow() {
                var closeWindow = confirm("Do you want to close this window?");
                if (closeWindow === true) {
                    if (this.draft !== null)
                        location.replace("/drafts");
                    else
                        location.replace("/manage/documents");
                }
            },
            createDocument: function () {
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
            },
            get_system(code) {
                axios.post('/util/get-system/' + code).then(response => {
                    EVENT_BUS.$emit("SYSTEM_LOADED", response.data);
                });
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
                    this.document.access = this.draft.document_access;
                    // Tell document component that there is changes on the system field
                    this.$refs.docForm.load_sections();


                    this.document.current_version.reviewers = this.draft.version_reviewer_ids;
                    this.document.current_version.approvers = this.draft.version_approver_ids;
                    this.document.current_version.effective_date = this.draft.version_effective_date;
                    this.document.current_version.content = this.cast_to_content(this.draft.version_content);
                    this.document.current_version.description = this.cast_to_content(this.draft
                        .version_description);

                    if (this.document.system)
                        this.get_system(this.document.system);

                } else {
                    this.document.current_version.content.addContentItem();
                    this.document.current_version.description.addContentItem();
                }

            });
        }

    }

</script>
