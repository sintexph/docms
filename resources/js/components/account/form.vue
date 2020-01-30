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
           <notification-form v-model="account"></notification-form>

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

        mounted() {
            if (this.value)
                this.account = this.value;

        }
    }

</script>
