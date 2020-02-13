<template>
    <div>

        <div class="box box-solid">
            <div class="box-body form-inline">
                <div class="form-group">
                    <label class="control-label">Find System</label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" v-model="filters.find" @keydown.enter="find_system">
                        <span class="input-group-btn">
                            <button type="button" @click.prevent="find_system" class="btn btn-default btn-flat">
                                <i class="fa fa-search" aria-hidden="true"></i>
                                Find</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">System List</h3>

                <div class="box-tools pull-right">


                    <div class="btn-group">
                        <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-wrench"></i>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="#" @click.prevent="$refs.createSystem.show">Create System</a>
                            </li>

                        </ul>
                    </div>

                </div>

            </div>
            <div class="box-body">
                <datatable ref="datatables" :fixedRightColumns="1" :parameters="filters" :columns="columns" url="/systems/list"></datatable>
                <create-system ref="createSystem" @created="$refs.datatables.reload()"></create-system>
                <edit-system ref="editSystem" @updated="$refs.datatables.reload()"></edit-system>

            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data: function () {
            return {
                filters:{
                    find:'',
                },
                submitted: false,
                columns: [

                    {
                        label: '#',
                        name: 'id',
                        data: 'id',
                        className: 'fit',
                    },
                    {
                        label: 'Code',
                        name: 'code',
                        data: 'code',
                        className: 'fit',
                    },
                    {
                        label: 'Name',
                        name: 'name',
                        data: 'name',
                        className: 'fit',
                    },

                      {
                        label: 'reviewers',
                        name: 'reviewer_ids',
                        data: 'reviewer_ids',
                        className: 'fit',
                        render(data,meta,row)
                        {
                            
                            var display=``;
                            row.reviewer_names.forEach(element => {
                                display+=`<span class="label label-default">`+element.name+`</span>&nbsp;`;
                            });
                            return display;
                        }
                    },
                      {
                        label: 'approvers',
                        name: 'approver_ids',
                        data: 'approver_ids',
                        className: 'fit',
                        render(data,meta,row)
                        {
                            
                            var display=``;
                            row.approver_names.forEach(element => {
                                display+=`<span class="label label-default">`+element.name+`</span>&nbsp;`;
                            });
                            return display;
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
                        label: 'Archived',
                        name: 'deleted_at',
                        data: 'deleted_at',
                        className: 'fit',
                        render(data) {
                            if (data !== null)
                                return `<span class="text-red">` + data + `</span>`;
                            else
                                return '';
                        }
                    },

                    {
                        label: 'Actions',
                        name: 'id',
                        data: 'id',
                        className: 'fit',
                        render: function (data, meta, row) {
                            
                            if (row.deleted_at !== null) {
                                var restorebtn = `<a href="#" data-id="` + data +
                                    `" class="btn-restore text-green" ><i class="fa fa-refresh" aria-hidden="true"></i> Restore</a>`;
                                var delbtn = `<a href="#" data-id="` + data +
                                    `" class="btn-delete text-red" ><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>`;
                                return restorebtn + '&nbsp;&nbsp;&nbsp;' + delbtn;

                            } else {

                                var archbtn = `<a href="#" data-id="` + data +
                                    `" class="btn-archive" ><i class="fa fa-archive" aria-hidden="true"></i> Archive</a>`;
                                var editbtn = `<a href="#" data-id="` + data +
                                    `" class="btn-edit text-yellow" ><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>`;
                                return editbtn + '&nbsp;&nbsp;&nbsp;' + archbtn;

                            }

                        }
                    }

                ]
            }
        },
        methods: {
            find_system: function () {
                this.$refs.datatables.reload();
            },
            archive: function (id) {
                var par = this;
                if (par.submitted == false) {
                    var r = confirm("Are you sure you want to archive the system?");
                    if (r == true) {
                        par.submitted = true;
                        axios.patch('/systems/archive/' + id).then(function (response) {
                            par.alert_success(response);
                            par.submitted = false;
                            par.$refs.datatables.reload();
                        }).catch(function (error) {
                            par.submitted = false;
                            par.alert_failed(error);
                        });
                    }
                }
            },
            restore: function (id) {
                var par = this;
                if (par.submitted == false) {
                    var r = confirm("Are you sure you want to restore the system?");
                    if (r == true) {
                        par.submitted = true;
                        axios.patch('/systems/restore/' + id).then(function (response) {
                            par.alert_success(response);
                            par.submitted = false;
                            par.$refs.datatables.reload();
                        }).catch(function (error) {
                            par.submitted = false;
                            par.alert_failed(error);
                        });
                    }
                }
            },
            delete: function (id) {
                var par = this;
                if (par.submitted == false) {
                    var r = confirm("Are you sure you want to delete the system?");
                    if (r == true) {
                        par.submitted = true;
                        axios.delete('/systems/delete/' + id).then(function (response) {
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



                $(document).on('click', '.btn-restore', function (e) {
                    e.preventDefault();
                    var id = $(this).data("id");
                    parent.restore(id);
                });


                $(document).on('click', '.btn-archive', function (e) {
                    e.preventDefault();
                    var id = $(this).data("id");
                    parent.archive(id);
                });
                $(document).on('click', '.btn-edit', function (e) {
                    e.preventDefault();
                    var id = $(this).data("id");
                    parent.$refs.editSystem.show(id);
                });

            })
        }
    }

</script>
