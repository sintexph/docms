<template>
    <div>

        <div class="box box-solid">
            <div class="box-body form-inline">
                <div class="form-group">
                    <label class="control-label">Find Account</label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" v-model="filters.find" @keydown.enter="find_account">
                        <span class="input-group-btn">
                            <button type="button" @click.prevent="find_account" class="btn btn-default btn-flat">
                                <i class="fa fa-search" aria-hidden="true"></i>
                                Find</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Account List</h3>

                <div class="box-tools pull-right">


                    <div class="btn-group">
                        <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-wrench"></i>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="#" @click.prevent="$refs.createAccount.show">Create Account</a>
                            </li>

                        </ul>
                    </div>

                </div>

            </div>
            <div class="box-body">
                <datatable fixedRightColumns="1" ref="datatables" :parameters="filters" :columns="columns"
                    url="/accounts/list"></datatable>
                <create-account ref="createAccount" @created="$refs.datatables.reload()"></create-account>
                <edit-account ref="editAccount" @updated="$refs.datatables.reload()"></edit-account>

            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data: function () {
            return {
                filters: {
                    find: '',
                },
                submitted: false,
                columns: [

                    {
                        label: '#',
                        name: 'id',
                        data: 'id',
                    },

                    {
                        label: 'Name',
                        name: 'name',
                        data: 'name',
                        className: 'fit',
                    },
                    {
                        label: 'Position',
                        name: 'position',
                        data: 'position',
                        className: 'fit',
                    },
                    {
                        label: 'Username',
                        name: 'username',
                        data: 'username',
                    },
                    {
                        label: 'Email',
                        name: 'email',
                        data: 'email',
                        className: 'fit',

                    },
                    {
                        label: 'Active',
                        name: 'active',
                        data: 'active',
                        className: 'fit',
                        render: function (data) {
                            if (data === true)
                                return '<span class="label label-success">ACTIVE</span>';
                            else
                                return '<span class="label label-default">IN ACTIVE</span>';
                        }

                    },
                    {
                        label: 'Administrator',
                        name: 'perm_administrator',
                        data: 'perm_administrator',
                        className: 'fit',
                        render: function (data) {
                            if (data === true)
                                return 'YES';
                            else
                                return '---';
                        }

                    },



                    {
                        label: 'Approver',
                        name: 'perm_approver',
                        data: 'perm_approver',
                        className: 'fit',
                        render: function (data) {
                            if (data === true)
                                return 'YES';
                            else
                                return '---';
                        }

                    },

                    {
                        label: 'Reviewer',
                        name: 'perm_reviewer',
                        data: 'perm_reviewer',
                        className: 'fit',
                        render: function (data) {
                            if (data === true)
                                return 'YES';
                            else
                                return '---';
                        }

                    },


                    {
                        label: 'Created By',
                        name: 'created_by',
                        data: 'created_by',
                        className: 'fit',
                    },
                    {
                        label: 'Created At',
                        name: 'created_at',
                        data: 'created_at',
                        className: 'fit',
                    },
                    {
                        label: 'Edited By',
                        name: 'edited_by',
                        data: 'edited_by',
                        className: 'fit',
                    },

                    {
                        label: 'Edited At',
                        name: 'updated_at',
                        data: 'updated_at',
                        className: 'fit',
                    },


                    {
                        label: 'Actions',
                        name: 'id',
                        data: 'id',
                        export: false,
                        className: 'fit',
                        render: function (data, meta, row) {
                            var delbtn = `<a href="#" data-id="` + data +
                                `" class="btn-delete text-red" ><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>`;
                            var editbtn = `<a href="#" data-id="` + data +
                                `" class="btn-edit text-yellow" ><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>`;

                            return editbtn + '&nbsp;&nbsp;&nbsp;' + delbtn;
                        }
                    }

                ]
            }
        },
        methods: {
            find_account: function () {
                this.$refs.datatables.reload();
            },
            delete: function (id) {
                var par = this;
                if (par.submitted == false) {
                    var r = confirm("Are you sure you want to delete the account?");
                    if (r == true) {
                        par.submitted = true;
                        axios.delete('/accounts/delete/' + id).then(function (response) {
                            par.alert_success(response);
                            par.submitted = false;
                            par.$refs.datatables.reload();
                        }).catch(function (error) {
                            par.submitted = false;
                            par.alert_failed(error);
                        });
                    }
                }


            }
        },
        mounted() {
            var parent = this;
            parent.$nextTick(function () {
                $(document).on('click', '.btn-delete', function (e) {
                    e.preventDefault();
                    var id = $(this).data("id");
                    parent.delete(id);
                });
                $(document).on('click', '.btn-edit', function (e) {
                    e.preventDefault();
                    var id = $(this).data("id");
                    parent.$refs.editAccount.show(id);
                });

            })
        }
    }

</script>
