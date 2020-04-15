<template>
    <div>
        <form action="/manage/documents" method="get">
            <div class="box box-solid">
                <div class="box-body">
                    <div class="form-inline">
                        <div class="form-group">
                            <label class="control-label">Search Document </label>
                            <div class="input-group input-group-sm">
                                <input type="text" name="find" placeholder="Search" class="form-control"
                                    v-model="filters.find">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-default">
                                        <i aria-hidden="true" class="fa fa-search"></i>
                                        <span>Search</span>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">State</label>
                            <select class="form-control input-sm" name="state" v-model="filters.state">
                                <option value="">-- Filter State --</option>
                                <option value="archived">Archived</option>
                                <option value="obsolete">Obsolete</option>
                                <option value="active">Active</option>
                                <option value="1">Pending</option>
                                <option value="5">For Review</option>
                                <option value="4">For Approval</option>
                                <option value="3">Approved</option>
                                <option value="2">Released</option>
                            </select>
                        </div>
                    </div>
                    <a href="#"
                        @click.prevent="show_more_filters===true?show_more_filters=false:show_more_filters=true">
                        <i class="fa fa-filter" aria-hidden="true"></i> More Filters
                    </a>
                </div>
            </div>
            <transition name="fade">
                <div v-if="show_more_filters===true" class="box box-solid">
                    <div class="box-body">
                        <div class="form-inline">
                            <div class="form-group">
                                <label class="control-label">System</label>
                                <select class="form-control input-sm" v-model="filters.system" name="system">
                                    <option value="">-- Filter System --</option>
                                    <option v-for="(value,key) in systems" :key="key" :value="value.code">
                                        {{ value.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Section</label>
                                <select class="form-control input-sm" v-model="filters.section" name="section">
                                    <option value="">-- Filter Section --</option>
                                    <option v-for="(value,key) in sections" :key="key" :value="value.code">
                                        {{ value.name }}
                                    </option>
                                </select>

                            </div>
                            <div class="form-group">
                                <label class="control-label">Category</label>
                                <select class="form-control input-sm" v-model="filters.category" name="category">
                                    <option value="">-- Filter Category --</option>
                                    <option v-for="(value,key) in categories" :key="key" :value="value.code">
                                        {{ value.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </form>

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Document List</h3>
                <div class="pull-right">
                    <div class="btn-group btn-group-xs">

                        <a href="/manage/documents/download" class="btn btn-default"><i class="fa fa-download"
                                aria-hidden="true"></i> Download List</a>

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
            },
            filter_find: String,
            filter_state: String,
            filter_section: String,
            filter_system: String,
            filter_category: String,
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

                            return `<a title="Click to view the document" href="/manage/documents/view/` + row.id + `">` + text + `</a>`
                        }
                    },
                    {
                        label: 'State',
                        name: 'current_version.state',
                        data: 'current_version.state',
                        className: 'fit',
                        render: function (data, meta, row) {
                            if (row.current_version) {
                                return row.current_version.state;
                            } else
                                return '---';

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
                   
                ],
                system_data: [],
                filters: {
                    find: '',
                    state: '',
                    system: '',
                    section: '',
                    category: '',
                },
                show_more_filters: false
            }
        },
        methods: {
            filter_list: function () {
                this.$refs.datatables.reload();
            }
        },
        mounted() {
            let par = this;

            par.filters.find = par.filter_find;
            par.filters.state = par.filter_state;
            par.filters.system = par.filter_system;
            par.filters.section = par.filter_section;
            par.filters.category = par.filter_category;

            if (par.filters.system || par.filters.section || par.filters.category)
                par.show_more_filters = true;


            par.systems.forEach(function (system) {
                par.system_data.push({
                    id: system.code,
                    text: system.name
                });
            });


        }

    }

</script>
<style>
    .fade-enter-active,
    .fade-leave-active {
        transition: opacity .5s;
    }

    .fade-enter,
    .fade-leave-to

    /* .fade-leave-active below version 2.1.8 */
        {
        opacity: 0;
    }

</style>
