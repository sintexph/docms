<template>
    <div>
        <form @submit.prevent="submit">
            <version-form :show_version="false" :data_required="false" v-model="version">
                <template slot="header">Edit version information</template>
            </version-form>
            <a :href="'/manage/documents/view/'+document_id" class="btn btn-sm btn-danger" title="Cancel">Cancel</a>
            <button class="btn btn-sm btn-warning" @click="save_only=true" type="submit"
                title="Save the changes of the document, it will not send notification to the approver and reviewer of the document.">Save
                Only</button>
            <button class="btn btn-sm btn-success" @click="save_only=false" type="submit"
                title="Submit the document which means it is ready to be reviewed and approved.">Submit
                Document</button>
        </form>
    </div>
</template>

<script>
    export default {
        props: {
            document_id: {
                required: true,
                type: String
            },
            version_id: String,
            document_type: String,
            description: [Object, Array],
            content: [Object, Array],
            effective_date: String,
            expiry_date: String,
            reviewers: [Object, Array],
            approvers: [Object, Array],
        },
        data: function () {
            return {
                version: new DocumentVersion,
                submitted: false,
                save_only: false,
                idle: null,
            }
        },
        methods: {
            submit: function () {
                let parent = this;
                if (parent.submitted === false) {
                    parent.submitted = true;
                    // Show wait modal
                    parent.show_wait("Please wait while the system is saving the document...");
                    axios.patch('/manage/documents/update_version/' + parent.version_id, {
                        version: this.version.toObject(),
                        save_only: this.save_only,
                    }).then(function (response) {
                        parent.hide_wait();
                        parent.alert_success(response);

                        setTimeout(() => {
                            if (parent.save_only === true)
                                location.reload();
                            else
                                location.replace("/manage/documents/view/" + parent.document_id);
                        }, 1000);

                    }).catch(function (error) {
                        // Hide wait modal
                        parent.hide_wait();
                        parent.submitted = false;
                        parent.alert_failed(error);
                    });
                }
            },
            save() {
                var parent = this;
                if (parent.submitted === false) {
                    parent.submitted = true;
                    // Show wait modal
                    parent.show_wait("Auto saving the document.....");
                    axios.patch('/manage/documents/update_version/' + parent.version_id, {
                        version: this.version.toObject(),
                        save_only: true,
                    }).then(function (response) {
                        parent.hide_wait();
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }).catch(function (error) {
                        // Hide wait modal
                        parent.hide_wait();
                        parent.submitted = false;
                        parent.alert_failed(error);
                    });
                }
            },
            auto_save() {
                this.idle.pause();
                this.save();
            },
            get_document() {
                axios.post('/util/get-doc-system/' + this.document_id).then(response => {
                    EVENT_BUS.$emit("SYSTEM_LOADED", response.data);
                });
            }
        },

        mounted: function () {

            this.$nextTick(function () {
                this.version.effective_date = this.effective_date;
                this.version.expiry_date = this.expiry_date;
                this.version.reviewers = this.reviewers;
                this.version.approvers = this.approvers;
                this.version.description = this.cast_to_content(this.description);
                this.version.content = this.cast_to_content(this.content);
                this.idle = idleTimeout(this.auto_save, {
                    element: document,
                    timeout: 60000 * 5,
                    loop: true
                });

                this.get_document();
            });


        },




    }

</script>
