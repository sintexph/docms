<template>
    <div>
        <div class="box box-solid">
            <div class="box-body">
                <div class="form-inline">
                    <div class="form-group"><label class="control-label">Search Draft </label>
                        <div class="input-group input-group-sm">
                            <input type="text" placeholder="Search" class="form-control" @keydown.enter="find_document"
                                v-model="find">
                            <span class="input-group-btn">
                                <button type="button" class="btn" @click.prevent="find_document"><i aria-hidden="true"
                                        class="fa fa-search"></i>
                                    Search</button></span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Draft List</h3>
            </div>
            <div class="box-body">
                <datatable ref="datatables" :columns="columns" url="/drafts/list"></datatable>
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
                find: '',
                submitted:false,
                columns: [{
                        label: '#',
                        name: 'id',
                        data: 'id',

                    },
                    {
                        label: 'Title',
                        name: 'document_title',
                        data: 'document_title',
                        className: "nowrap"
                    },


                    {
                        label: 'Created At',
                        name: 'created_at',
                        data: 'created_at',

                    },
                    {
                        label: 'Action',
                        name: 'id',
                        data: 'id',
                        className: "nowrap",
                        render: function (data) {
                            var btn_modify = `<a href="/manage/documents/create?draft=` + data +
                                `" target="_blank"><i class="fa fa-pencil" aria-hidden="true"></i> Modify</a>`;
                            var btn_delete = `<a href="#" data-id="` + data +
                                `" class="btn-delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>`;
                            return btn_modify + '<br>' + btn_delete;
                        }
                    }


                ],
                
            }
        },
        methods: {
            find_document: function () {

                this.$refs.datatables.custom_search("find", this.find);
            },
            delete: function (id) {
                var par = this;
                if (par.submitted == false) {
                    var r = confirm("Are you sure you want to delete the draft?");
                    if (r == true) {
                        par.submitted = true;
                        axios.delete('/drafts/delete/' + id).then(function (response) {
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
            let par = this;
       
       

            par.$nextTick(function () {
                 $(document).on('click', '.btn-delete', function (e) {
                    e.preventDefault();
                    var id = $(this).data("id");
                    par.delete(id);
                });
            });
        }

    }

</script>
