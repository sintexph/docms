<template>
    <modal :name="'create-account'+_uid" ref="modal">
        <template slot="header">Create Account</template>
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
                account: new User,
                submitted: false,
            }
        },
        methods: {
            show: function () {
                this.$refs.modal.show();
            },
            save: function () {
                var par = this;

                if (par.submitted === false) {
                    par.show_wait("Please wait while the system is processing your request....");
                    par.submitted = true;
                    axios.put('/accounts/save', {

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
                        view_confidential: par.account.view_confidential,


                    }).then(function (response) {
                        par.alert_success(response);
                        par.submitted = false;
                        par.$emit('created');


                        par.account = new User;


                        par.$refs.modal.dismiss();
                    }).catch(function (error) {
                        par.submitted = false;
                        par.alert_failed(error);
                    }).finally(() => {
                        par.hide_wait();
                    });
                }


            }
        }
    }

</script>
