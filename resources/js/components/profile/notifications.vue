<template>
    <div class="box box-solid">
        <div class="box-header">
            <h3 class="box-title">Notification Settings</h3>
        </div>
        <div class="box-body">
            <p>
                <icheck-checkbox v-model="account.notify_changes"></icheck-checkbox> Notify me when there are changes of
                the document.
            </p>
            <p>
                <icheck-checkbox v-model="account.notify_followups"></icheck-checkbox> Notify me for document approval
                or review followups.
            </p>
            <p>
                <icheck-checkbox v-model="account.notify_comments"></icheck-checkbox> Notify me if there are comments in
                the documents.
            </p>

            <p>
                <icheck-checkbox v-model="account.notify_reviewed"></icheck-checkbox> Notify me if my documents were
                reviewed.
            </p>
            <p>
                <icheck-checkbox v-model="account.notify_approved"></icheck-checkbox> Notify me if my documents were
                approved.
            </p>

            <p>
                <icheck-checkbox v-model="account.notify_to_review"></icheck-checkbox> Notify me if there are documents
                that i need to review.
            </p>
            <p>
                <icheck-checkbox v-model="account.notify_to_approve"></icheck-checkbox> Notify me if there are documents
                that i need to approve.
            </p>
        </div>
        <div class="box-footer">
            <div class="pull-right">
                <button @click.prevent="save()" class="btn btn-primary">
                    Save Notifications
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data: function () {
            return { 
                account: new User,
                submitted: false,
            }
        },
        methods: {

            save: function () {
                var par = this;

                if (par.submitted === false) {
                    par.submitted = true;
                    axios.patch('/profile/notification-settings', {

                        notify_changes: par.account.notify_changes,
                        notify_followups: par.account.notify_followups,
                        notify_comments: par.account.notify_comments,
                        notify_reviewed: par.account.notify_reviewed,
                        notify_approved: par.account.notify_approved,
                        notify_to_approve: par.account.notify_to_approve,
                        notify_to_review: par.account.notify_to_review,

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
            fetch: function () {
                var par = this;
                axios.post('/profile/notification-settings').then(function (response) {


                    par.account.notify_changes = response.data.notify_changes;
                    par.account.notify_followups = response.data.notify_followups;
                    par.account.notify_comments = response.data.notify_comments;
                    par.account.notify_reviewed = response.data.notify_reviewed;
                    par.account.notify_approved = response.data.notify_approved;
                    par.account.notify_to_approve = response.data.notify_to_approve;
                    par.account.notify_to_review = response.data.notify_to_review;

                });
            }
        },
        mounted() {
            this.fetch();
        }
    }

</script>
