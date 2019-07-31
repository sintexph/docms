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
                account: {
                    email: '',
                    name: '',
                    position: '',
                    username: '',
                    password: '',
                    password_confirmation: '',
                    perm_administrator: false,
                    perm_approver: false,
                    perm_reviewer: false,
                },
                submitted: false,
            }
        },
        methods: {
            show: function (id) {
                this.id = id;

                this.account.email = '';
                this.account.name = '';
                this.account.position = '';
                this.account.username = '';
                this.account.password = '';
                this.account.password_confirmation = '';
                this.account.perm_administrator = false;
                this.account.perm_approver = false;
                this.account.perm_reviewer = false;



                this.fetch();
                this.$refs.modal.show();

            },
            save: function () {
                var par = this;

                if (par.submitted === false) {
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
                axios.post('/accounts/fetch/' + par.id).then(function (response) {
                    par.account.email = response.data.email;
                    par.account.name = response.data.name;
                    par.account.position = response.data.position;
                    par.account.username = response.data.username;
                    par.account.perm_administrator = response.data.perm_administrator;

                    par.account.perm_approver = response.data.perm_approver;
                    par.account.perm_reviewer = response.data.perm_reviewer;
                });
            }
        }
    }

</script>
