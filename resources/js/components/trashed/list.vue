<template>
    <div>
        <div class="box box-solid">
            <div class="box-body">
                <div class="form-inline">
                    <div class="form-group">
                        <label class="control-label">Search Document </label>
                        <div class="input-group input-group-sm">
                            <input type="text" placeholder="Search" class="form-control" @keydown.enter="filter_list"
                                v-model="filters.find">
                            <span class="input-group-btn">
                                <button type="button" class="btn" @click.prevent="filter_list">
                                    <i aria-hidden="true" class="fa fa-search"></i>
                                    <span>Search</span>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title text-red"><i class="fa fa-trash" aria-hidden="true"></i> Trashed Document List</h3>
            </div>
            <div class="box-body">
                <datatable ref="datatables" :parameters="filters" :columns="columns" url="/trashed/list"></datatable>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            systems: {
                type: [Object, Array],
                default: function () {
                    return [];
                }
            }
        },
        data: function () {
            return {
                filters: {
                    find: '',
                },
                columns: [{
                        label: '#',
                        name: 'id',
                        data: 'id',
                        className: 'fit',
                        render: function (data, meta, row) {
                            var ic =
                                `<a href="#" class="text-green" title="Active"><i class="fa fa-file" aria-hidden="true"></i></a>`;
                            if (row.archived === true)
                                ic =
                                `<a href="#" title="Archived" class="text-red"><i class="fa fa-archive " aria-hidden="true"></i></a>`;
                            else if (row.obsolete === true)
                                ic =
                                `<a href="#" title="Obsolete" class="text-yellow"><i class="fa fa-file " aria-hidden="true"></i></a>`;
                            return ic + '&nbsp;&nbsp;' + data;
                        }
                    },
                    {
                        label: 'Title',
                        name: 'title',
                        data: 'title',
                        className: 'fit',
                        render: function (data, meta, row) {
                            var text = '';
                            if (row.archived === true)
                                text = `<strong class="text-red">` + data + `</strong>`;
                            else if (row.obsolete === true)
                                text = `<strong class="text-yellow">` + data + `</strong>`;
                            else
                                text = `<strong class="text-green">` + data + `</strong>`;

                            return `<a href="/manage/documents/view/` + row.id + `">` + text + `</a>`
                        }
                    },

                    {
                        label: 'Document No.',
                        name: 'document_number',
                        data: 'document_number',
                        className: 'fit'
                    },
                    {
                        label: 'Version',
                        name: 'version',
                        data: 'version',
                        className: 'fit'
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
                                return `<code>` + data + `</code>`;
                        }
                    },
                    {
                        label: 'Section',
                        name: 'section_code',
                        data: 'section_code',
                        className: 'fit',
                        render: function (data, meta, row) {
                            if (row.section !== null)
                                return row.section.name;
                            else
                                return `<code>` + data + `</code>`;
                        }
                    },
                    {
                        label: 'Category',
                        name: 'category_code',
                        data: 'category_code',
                        className: 'fit',
                        render: function (data, meta, row) {
                            if (row.category !== null)
                                return row.category.name;
                            else
                                return `<code>` + data + `</code>`;
                        }
                    },
                    {
                        label: 'Create By',
                        name: 'created_by',
                        data: 'creator',
                        className: 'fit',
                        render: function (data) {
                            return data.name;
                        }
                    },
                    {
                        label: 'Created At',
                        name: 'created_at',
                        data: 'created_at',
                        className: 'fit',
                    },
                    {
                        label: 'Action',
                        name: 'id',
                        data: 'id',
                        className: 'fit',
                        export: false,
                        render: function (data) {
                            var permanent = `<a href="#" data-id="` + data +
                                `" class="btn-delete text-red">Permanent Delete</a>`;
                            var restore = `<a href="#" data-id="` + data +
                                `" class="btn-restore text-green">Restore</a>`;
                            return restore + `&nbsp;&nbsp;&nbsp;` + permanent;
                        }
                    }
                ],
                system_data: [],
            }
        },
        methods: {
            filter_list: function () {
                this.$refs.datatables.reload();
            },
            perment_delete: function (id) {
                var par = this;
                var r = confirm("Are you sure do you want to permanently remove the document from the system?");
                if (r == true) {
                    axios.delete('/trashed/permanent/' + id).then(function (
                        response) {
                        par.alert_success(response);
                        par.filter_list();
                    }).catch(function (error) {
                        par.submitted = false;
                        par.alert_failed(error);
                    });
                }
            },
            restore: function (id) {
                var par = this;
                var r = confirm("Do you want to restore the document?");
                if (r == true) {
                    axios.patch('/trashed/restore/' + id).then(function (
                        response) {
                        par.alert_success(response);
                        par.filter_list();
                    }).catch(function (error) {
                        par.submitted = false;
                        par.alert_failed(error);
                    });
                }
            }
        },
        mounted() {
            let par = this;
            par.systems.forEach(function (system) {
                par.system_data.push({
                    id: system.code,
                    text: system.name
                });
            });

            $(document).on('click', '.btn-delete', function () {
                var id = $(this).data("id");
                par.perment_delete(id);
            });
            $(document).on('click', '.btn-restore', function () {
                var id = $(this).data("id");
                par.restore(id);
            });
        }

    }

</script>
