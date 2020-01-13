<template>
    <div>
        <form @submit.prevent="submit">
            <version-form :show_version="false" :data_required="false" v-model="version">
                <template slot="header">Edit version information</template>
            </version-form>
            <button class="btn btn-sm btn-warning" @click="submit_only=true" type="submit"
                title="Save the changes of the document, it will not send notification to the approver and reviewer of the document.">Save
                Only</button>
            <button class="btn btn-sm btn-success" @click="submit_only=false" type="submit"
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
            for_approval: Boolean,
            for_review: Boolean,
        },
        data: function () {
            return {
                version: new DocumentVersion,
                submitted: false,
                submit_only: false,
                idle: null,
            }
        },
        methods: {
            submit: function () {
                let parent = this;
                if (parent.submitted === false) {
                    parent.submitted = true;

                    if (parent.submit_only === false) {
                        parent.version.for_review = true;
                        parent.version.for_approval = true;
                    }

                    // Show wait modal
                    parent.show_wait("Please wait while the system is saving the document...");
                    axios.patch('/manage/documents/update_version/' + parent.version_id, {
                        version: this.version.toObject(),
                        submit_only: this.submit_only,
                    }).then(function (response) {
                        parent.hide_wait();
                        parent.alert_success(response);

                        setTimeout(() => {
                            if (parent.submit_only === true)
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
                        submit_only: true,
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


            }


        },

        mounted: function () {




            var par = this;


            par.$nextTick(function () {

                par.version.effective_date = par.effective_date;
                par.version.expiry_date = par.expiry_date;
                par.version.reviewers = par.reviewers;
                par.version.approvers = par.approvers;
                par.version.for_approval = par.for_approval;
                par.version.for_review = par.for_review;

                par.version.description = par.cast_to_content(par.description);
                par.version.content = par.cast_to_content(par.content);


                par.idle = idleTimeout(par.auto_save, {
                    element: document,
                    timeout: 60000 * 5,
                    loop: true
                });


            });







        },




    }

</script>
