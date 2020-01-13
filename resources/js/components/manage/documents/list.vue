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
                    <div class="form-group">
                        <label class="control-label">State</label>
                        <select class="form-control input-sm" v-model="filters.state" @change="filter_list">
                            <option value="">-- Filter State --</option>
                            <option value="archived">Archived</option>
                            <option value="obsolete">Obsolete</option>
                            <option value="active">Active</option>
                            <option value="1">Pending</option>
                            <option value="6">For Review</option>
                            <option value="4">Reviewed</option>
                            <option value="5">For Approval</option>
                            <option value="3">Approved</option>
                            <option value="2">Released</option>
                        </select>
                    </div>
                </div>
                <a href="#" data-toggle="collapse" data-target="#collapse-filter" aria-expanded="false"
                    aria-controls="collapse-filter">
                    <i class="fa fa-filter" aria-hidden="true"></i> More Filters
                </a>
            </div>
        </div>
        <div class="collapse" id="collapse-filter">
            <div class="box box-solid">
                <div class="box-body">
                    <div class="form-inline">
                        <div class="form-group">
                            <label class="control-label">System</label>
                            <select class="form-control input-sm" v-model="filters.system" @change="filter_list">
                                <option value="">-- Filter System --</option>
                                <option v-for="(value,key) in systems" :key="key" :value="value.code">
                                    {{ value.name }}
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Section</label>
                            <select class="form-control input-sm" v-model="filters.section" @change="filter_list">
                                <option value="">-- Filter Section --</option>
                                <option v-for="(value,key) in sections" :key="key" :value="value.code">
                                    {{ value.name }}
                                </option>
                            </select>

                        </div>
                        <div class="form-group">
                            <label class="control-label">Category</label>
                            <select class="form-control input-sm" v-model="filters.category" @change="filter_list">
                                <option value="">-- Filter Category --</option>
                                <option v-for="(value,key) in categories" :key="key" :value="value.code">
                                    {{ value.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Document List</h3>
                <div class="pull-right">
                    <div class="btn-group btn-group-xs">
                        
                        <a href="/manage/documents/download" class="btn btn-default"><i class="fa fa-download" aria-hidden="true"></i> Download List</a>
              
                    </div>
                </div>
            </div>
            <div class="box-body">
                <datatable ref="datatables" :buttons="false" :parameters="filters" :columns="columns"
                    url="/manage/documents/list">
                </datatable>
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
            },
            sections: {
                type: [Object, Array],
                default: function () {
                    return [];
                }
            },
            categories: {
                type: [Object, Array],
                default: function () {
                    return [];
                }
            }
        },
        data: function () {
            return {


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
                            return ic + `&nbsp;` + data;
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
                        label: 'State',
                        name: 'current_version.state',
                        data: 'current_version.state',
                        className: 'fit',
                        render: function (data, meta, row) {
                            return row.current_version.state;
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
                        label: 'Access',
                        name: 'access',
                        className: 'fit',
                        render: function (data, meta, row) {
                            return row.access_icon + '&nbsp;&nbsp;&nbsp;' + row.access_type;
                        }
                    },

                    {
                        label: 'Created By',
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
                            return `<a href="/manage/documents/view/` + data + `">View More</a>`;
                        }
                    }
                ],
                system_data: [],
                filters: {
                    find: '',
                    state: '',
                    system: '',
                    section: '',
                    category: '',
                },
            }
        },
        methods: {
            filter_list: function () {
                this.$refs.datatables.reload();
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


        }

    }

</script>
