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

        <hr>
        <strong>Permission</strong>
        
        <br><br>
        <div class="form-group">
            <label class="label-control"><input type="checkbox" v-model="account.perm_administrator"> Administrator</label>

        </div>

        <div class="form-group">
            <label class="label-control"><input type="checkbox" v-model="account.perm_approver"> Approver</label>

        </div>

        <div class="form-group">
            <label class="label-control"><input type="checkbox" v-model="account.perm_reviewer"> Reviewer</label>

        </div>






    </div>

</template>

<script>
    export default {
        props: ['value'],
        data: function () {
            return {
                account: {
                    email: '',
                    name: '',
                    position: '',
                    username: '',
                    password: '',
                    password_confirmation: '',
                    perm_administrator: false,
                    perm_approver:false,
                    perm_reviewer:false,

                },

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
