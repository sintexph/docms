<template>

    <div>
        <div class="form-group">
            <label class="label-control">Name</label>
            <input class="form-control  text-uppercase" v-model="account.name">
        </div>

        <div class="form-group">
            <label class="label-control">Email</label>
            <input type="email" class="form-control" v-model="account.email">
        </div>

        <div class="form-group">
            <label class="label-control">Position</label>
            <input type="text" class="form-control" v-model="account.position">
        </div>

        <div class="form-group">
            <label class="label-control">Username</label>
            <input type="text" class="form-control" v-model="account.username">
        </div>
        <div class="form-group">
            <label class="label-control">Password</label>
            <input type="password" class="form-control" v-model="account.password">
        </div>
        <div class="form-group">
            <label class="label-control">Password Confirmation</label>
            <input type="password" class="form-control" v-model="account.password_confirmation">
        </div>


        <br><br>
        <div class="form-group">
            <label class="control-label">Permissions</label>

            <p>
                <icheck-checkbox v-model="account.perm_administrator"></icheck-checkbox>
                Administrator
            </p>
            <p>
                <icheck-checkbox v-model="account.perm_approver"></icheck-checkbox> Approver
            </p>
            <p>
                <icheck-checkbox v-model="account.perm_reviewer"></icheck-checkbox> Reviewer
            </p>

        </div>




        <div class="form-group">
            <label class="control-label">Notifications</label>
            <p>
                <icheck-checkbox v-model="account.notify_changes"></icheck-checkbox> Notify when there are changes of
                the document.
            </p>
            <p>
                <icheck-checkbox v-model="account.notify_followups"></icheck-checkbox> Notify for document approval
                or review followups.
            </p>
            <p>
                <icheck-checkbox v-model="account.notify_comments"></icheck-checkbox> Notify if there are comments in
                the documents.
            </p>

            <p>
                <icheck-checkbox v-model="account.notify_reviewed"></icheck-checkbox> Notify if documents were
                reviewed.
            </p>
            <p>
                <icheck-checkbox v-model="account.notify_approved"></icheck-checkbox> Notify if documents were
                approved.
            </p>

            <p>
                <icheck-checkbox v-model="account.notify_to_review"></icheck-checkbox> Notify if there are documents
                that needs to review.
            </p>
            <p>
                <icheck-checkbox v-model="account.notify_to_approve"></icheck-checkbox> Notify if there are documents
                that needs to approve.
            </p>

        </div>




    </div>

</template>

<script>
    export default {
        props: ['value'],
        data: function () {
            return {
                account: new User,

            }
        },
        watch: {
            value: {
                deep: true,
                handler: function (val) {
                    this.account = val;
                }
            },
            account: {
                deep: true,
                handler: function (val) {
                    this.$emit('input', val);
                }
            }
        },


    }

</script>
