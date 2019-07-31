<template>
    <form @submit.prevent="submit">
        <version-form :show_version="false" :data_required="false" v-model="version">
            <template slot="header">Edit version information</template>
        </version-form>
        <button class="btn btn-sm btn-success" type="submit">Update Document</button>
    </form>
</template>


<script>
    export default {
        props: {
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
                version: {
                    number: '',
                    content: new Content,
                    description: new Content,
                    for_review: false,
                    for_approval: false,
                    reviewers: [],
                    approvers: [],
                    effective_date: '',
                    expiry_date: '',
                },
                submitted: false,
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
                        version: this.version,
                    }).then(function (response) {
                        parent.alert_success(response);
                        location.reload();
                    }).catch(function (error) {
                        // Hide wait modal
                        parent.hide_wait();
                        parent.submitted = false;
                        parent.alert_failed(error);
                    });
                }

            }
        },
        mounted: function () {

            this.$nextTick(function () {

                this.version.effective_date = this.effective_date;
                this.version.expiry_date = this.expiry_date;
                this.version.reviewers = this.reviewers;
                this.version.approvers = this.approvers;
                this.version.for_approval = this.for_approval;
                this.version.for_review = this.for_review;

                this.version.description = this.cast_to_content(this.description);
                this.version.content = this.cast_to_content(this.content);
            });


        },

    }

</script>
