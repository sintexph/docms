<template>
    <div>

        <div class="box box-solid">
            <div class="box-body form-inline">
                <div class="form-group">
                    <label class="control-label">Find Document</label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" v-model="filters.find" @keydown.enter="find_document">
                        <span class="input-group-btn">
                            <button type="button" @click.prevent="find_document" class="btn btn-default btn-flat"><i
                                    class="fa fa-search" aria-hidden="true"></i>
                                Find</button>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">State</label>
                    <select class="form-control input-sm" v-model="filters.state">
                        <option value="">-- Filter State --</option>
                        <option value="rv">Reviewed</option>
                        <option value="al">All</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Documents to be reviewed</h3>
            </div>
            <div class="box-body">
                <datatable ref="datatables" :parameters="filters" :columns="columns" url="/for_review/list"></datatable>
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
                    state: '',
                },
                submitted: false,
                columns: [{
                        label: 'Document No.',
                        name: 'document_number',
                        data: 'document_number',
                    },
                    {
                        label: 'Title',
                        name: 'title',
                        data: 'title',
                        className: 'fit',
                        render: function (data) {
                            return `<strong>` + data + `</strong>`;
                        }
                    },
                    {
                        label: 'Version',
                        name: 'version',
                        data: 'version',
                    },
                    {
                        label: 'System',
                        name: 'system',
                        data: 'system',
                    },
                    {
                        label: 'Section',
                        name: 'section',
                        data: 'section',
                    },
                    {
                        label: 'Category',
                        name: 'category',
                        data: 'category',
                    },
                    {
                        label: 'Created',
                        name: 'version_creator',
                        data: 'version_creator',
                    },
                    {
                        label: 'Actions',
                        name: 'id',
                        data: 'id',
                        className: 'fit',
                        export: false,
                        render: function (data, meta, row) {

                            var btn_view = `<a href="/for_review/view/` + data +
                                `" target="_blank"><i aria-hidden="true" class="fa fa-star"></i> View Content</a>`;
                         
                                return  btn_view;
                        }
                    }


                ]
            }
        },
        watch: {
            filter_state: function (val) {
                this.find_document();

            }
        },
        methods: {
            find_document: function () {
                this.$refs.datatables.reload();
            },
         
        },
       
    }

</script>
