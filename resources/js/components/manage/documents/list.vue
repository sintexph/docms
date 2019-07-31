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
                                <option v-for="(value,key) in systems" :key="key" :value="value.code">{{ value.name }}
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Section</label>
                            <select class="form-control input-sm" v-model="filters.section" @change="filter_list">
                                <option value="">-- Filter Section --</option>
                                <option v-for="(value,key) in sections" :key="key" :value="value.code">{{ value.name }}
                                </option>
                            </select>

                        </div>
                        <div class="form-group">
                            <label class="control-label">Category</label>
                            <select class="form-control input-sm" v-model="filters.category" @change="filter_list">
                                <option value="">-- Filter Category --</option>
                                <option v-for="(value,key) in categories" :key="key" :value="value.code">
                                    {{ value.name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Document List</h3>
            </div>
            <div class="box-body">
                <datatable ref="datatables" :columns="columns" url="/manage/documents/list"></datatable>
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
                        className: "nowrap",
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
                        className: "nowrap",
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
                        className: "nowrap"
                    },
                    {
                        label: 'Version',
                        name: 'version',
                        data: 'version',
                        className: "nowrap"
                    },
                    {
                        label: 'System',
                        name: 'system_code',
                        data: 'system_code',
                        className: 'nowrap',
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
                        className: "nowrap",
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
                        className: "nowrap",
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
                        className: "nowrap",
                        render: function (data) {
                            return data.name;
                        }
                    },
                    {
                        label: 'Created At',
                        name: 'created_at',
                        data: 'created_at',
                        className: "nowrap",
                    },
                    {
                        label: 'Action',
                        name: 'id',
                        data: 'id',
                        className: "nowrap",
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
                this.$refs.datatables.custom_search_multiple(this.filters);
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