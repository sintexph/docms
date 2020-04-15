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
                        <option value="rv">Approved</option>
                        <option value="al">All</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Documents to be approved</h3>
            </div>
            <div class="box-body">
                <datatable ref="datatables" :parameters="filters" :columns="columns" url="/for_approval/list"></datatable>
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
                    state:'',
                },
                submitted: false,
                columns: [
 {
                        label: 'Document No.',
                        name: 'document_number',
                        data: 'document_number',
                    },
                    {
                        label: 'Title',
                        name: 'title',
                        data: 'title',
                        className: 'fit',
                        render: function (data, meta, row) {

                            return `<a title="Click to view the `+data+`" href="/for_approval/view/` + row.id + `"><strong>` + data +
                                `</strong></a>`
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
                        className: 'fit',
                    },
                    {
                        label: 'Section',
                        name: 'section',
                        data: 'section',
                        className: 'fit',
                    },
                    {
                        label: 'Category',
                        name: 'category',
                        data: 'category',
                        className: 'fit',
                    },
                    {
                        label: 'Created',
                        name: 'version_creator',
                        data: 'version_creator',
                        className: 'fit',
                    }


                ]
            }
        },
        watch: {
            filter_state: function () {
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
