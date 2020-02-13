<template>
    <modal :name="'edit-account'+_uid" ref="modal">
        <template slot="header">Edit Account</template>
        <template slot="body">
            <account-form v-model="account"></account-form>
        </template>

        <template slot="footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-default" @click.prevent="save">Save</button>

        </template>

    </modal>
</template>

<script>
    import AccountForm from './form.vue';

    export default {
        components: {
            'account-form': AccountForm
        },
        data: function () {
            return {
                id: '',
                account: new User,
                submitted: false,
            }
        },
        methods: {
            show: function (id) {
                this.id = id;
                this.account = new User;
                this.fetch();
                this.$refs.modal.show();
            },
            save: function () {
                var par = this;

                if (par.submitted === false) {
                    par.show_wait("Please wait while the system is processing your request....");

                    par.submitted = true;
                    axios.patch('/accounts/update/' + par.id, {

                        email: par.account.email,
                        name: par.account.name,
                        position: par.account.position,
                        username: par.account.username,
                        password: par.account.password,
                        password_confirmation: par.account.password_confirmation,
                        perm_administrator: par.account.perm_administrator,

                        perm_approver: par.account.perm_approver,
                        perm_reviewer: par.account.perm_reviewer,


                        notify_changes: par.account.notify_changes,
                        notify_followups: par.account.notify_followups,
                        notify_comments: par.account.notify_comments,
                        notify_reviewed: par.account.notify_reviewed,
                        notify_approved: par.account.notify_approved,
                        notify_to_approve: par.account.notify_to_approve,
                        notify_to_review: par.account.notify_to_review,
                        active: par.account.active,


                    }).then(function (response) {
                        par.alert_success(response);
                        par.submitted = false;
                        par.$emit('updated');
                        par.$refs.modal.dismiss();
                    }).catch(function (error) {
                        par.submitted = false;
                        par.alert_failed(error);
                    }).finally(() => {
                        par.hide_wait();
                    });
                }


            },
            fetch: function () {
                var par = this;
                axios.post('/accounts/fetch/' + par.id).then(function (response) {
                    par.account.email = response.data.email;
                    par.account.name = response.data.name;
                    par.account.position = response.data.position;
                    par.account.username = response.data.username;
                    par.account.perm_administrator = response.data.perm_administrator;

                    par.account.perm_approver = response.data.perm_approver;
                    par.account.perm_reviewer = response.data.perm_reviewer;

                    par.account.notify_changes = response.data.notify_changes;
                    par.account.notify_followups = response.data.notify_followups;
                    par.account.notify_comments = response.data.notify_comments;
                    par.account.notify_reviewed = response.data.notify_reviewed;
                    par.account.notify_approved = response.data.notify_approved;
                    par.account.notify_to_approve = response.data.notify_to_approve;
                    par.account.notify_to_review = response.data.notify_to_review;
                    par.account.active = response.data.active;

                });
            }
        }
    }

</script>
