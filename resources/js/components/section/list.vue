<template>
    <div>

        <div class="box box-solid">
            <div class="box-body form-inline">
                <div class="form-group">
                    <label class="control-label">Find Section</label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" v-model="filters.find" @keydown.enter="find_section">
                        <span class="input-group-btn">
                            <button type="button" @click.prevent="find_section" class="btn btn-default btn-flat">
                                <i class="fa fa-search" aria-hidden="true"></i>
                                Find</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Section List</h3>

                <div class="box-tools pull-right">


                    <div class="btn-group">
                        <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-wrench"></i>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="#" @click.prevent="$refs.createSection.show">Create Section</a>
                            </li>

                        </ul>
                    </div>

                </div>

            </div>
            <div class="box-body">
                
                <datatable ref="datatables" :parameters="filters" :columns="columns" url="/sections/list"></datatable>
                <create-section ref="createSection" @created="$refs.datatables.reload()"></create-section>
                <edit-section ref="editSection" @updated="$refs.datatables.reload()"></edit-section>
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
                    },
                    {
                        label: 'Code',
                        name: 'code',
                        data: 'code',
                    },
                    {
                        label: 'Name',
                        name: 'name',
                        data: 'name',
                        className: 'fit',
                    },

                    {
                        label: 'System',
                        name: 'system_code',
                        data: 'system_code',
                        className: 'fit',
                        render: function (data, meta, row) {
                            if (row.system !== null)
                                return row.system.name;
                            else
                                 return `<code>`+data+`</code>`;
                        }
                    },
                    {
                        label: 'Created By',
                        name: 'created_by',
                        data: 'created_by',
                    },
                    {
                        label: 'Created At',
                        name: 'created_at',
                        data: 'created_at',
                    },
                    {
                        label: 'Edited By',
                        name: 'edited_by',
                        data: 'edited_by',
                    },

                    {
                        label: 'Edited At',
                        name: 'updated_at',
                        data: 'updated_at',
                    },

                    {
                        label: 'Archived',
                        name: 'deleted_at',
                        data: 'deleted_at',
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
            find_section: function () {
                this.$refs.datatables.reload();
            },
             archive: function (id) {
                var par = this;
                if (par.submitted == false) {
                    var r = confirm("Are you sure you want to archive the section?");
                    if (r == true) {
                        par.submitted = true;
                        axios.patch('/sections/archive/' + id).then(function (response) {
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
                    var r = confirm("Are you sure you want to restore the section?");
                    if (r == true) {
                        par.submitted = true;
                        axios.patch('/sections/restore/' + id).then(function (response) {
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
                    var r = confirm("Are you sure you want to delete the section?");
                    if (r == true) {
                        par.submitted = true;
                        axios.delete('/sections/delete/' + id).then(function (response) {
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
                    parent.$refs.editSection.show(id);
                });

            })
        }
    }

</script>
