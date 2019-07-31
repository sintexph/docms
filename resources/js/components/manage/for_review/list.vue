<template>
    <div>

        <div class="box box-solid">
            <div class="box-body form-inline">
                <div class="form-group">
                    <label class="control-label">Find Document</label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" v-model="filter_find" @keydown.enter="find_document">
                        <span class="input-group-btn">
                            <button type="button" @click.prevent="find_document" class="btn btn-default btn-flat"><i
                                    class="fa fa-search" aria-hidden="true"></i>
                                Find</button>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">State</label>
                    <select class="form-control input-sm" v-model="filter_state">
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
                <datatable ref="datatables" :columns="columns" url="/for_review/list"></datatable>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data: function () {
            return {
                filter_find: '',
                filter_state: '',
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
                        className: 'nowrap',
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
                        className: 'nowrap',
                        export: false,
                        render: function (data, meta, row) {

                            var btn_view = `<a href="/for_review/view/` + data +
                                `" target="_blank"><i aria-hidden="true" class="fa fa-star"></i> View Content</a>`;
                            var btn_review = `<a href="#" data-id="` +
                                data +
                                `" class="btn-review"><i class="fa fa-eye" aria-hidden="true"></i> Update Reviewed</a>`;

                            if (row.reviewed === true)
                                return btn_view;
                            else
                                return btn_view + '<br>' + btn_review;
                        }
                    }


                ]
            }
        },
        watch: {
            filter_state: function (val) {
                this.$refs.datatables.custom_search('state', val);

            }
        },
        methods: {
            find_document: function () {
                this.$refs.datatables.custom_search('find', this.filter_find);
                this.$refs.datatables.custom_search('state', this.filter_state);
            },
            review: function (id) {
                var par = this;
                if (par.submitted == false) {
                    var r = confirm("Do you want to update the document version as reviewed?");
                    if (r == true) {
                        par.submitted = true;
                        // Show wait modal
                        par.show_wait("Please wait while the system is updating the document...");

                        axios.patch('/for_review/review/' + id).then(function (response) {
                            par.alert_success(response);
                            par.submitted = false;
                            // Hide wait modal
                            par.hide_wait();

                            par.$refs.datatables.reload();

                        }).catch(function (error) {
                            // Hide wait modal
                            par.hide_wait();

                            par.submitted = false;
                            par.alert_failed(error);
                        });


                    }
                }


            }
        },
        mounted() {
            let parent = this;
            parent.$nextTick(function () {

                $(document).on('click', '.btn-review', function () {
                    var id = $(this).data("id");
                    parent.review(id);
                });





            })
        }
    }

</script>
