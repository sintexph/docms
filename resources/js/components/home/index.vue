<template>
    <div class="row">
        <div class="col-xs-4">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Folder</h3>
                </div>
                <div class="box-body home-box-maxh">
                    <ul class="document-list tree" data-widget="tree">
                        <li class="treeview" v-for="(system,key) in systems" :key="key">
                            <a href="#">
                                <i class="fa fa-folder-o" aria-hidden="true"></i> <span>{{ system.name }}</span>
                            </a>
                            <ul class="treeview-menu" style="display: none;">
                                <li v-for="(section,key) in system.sections" :key="key"><a :href="'/?system='+system.id+'&sec='+section.id"><i class="fa fa-folder-o" aria-hidden="true"></i> {{ section.name }}</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xs-8">
            <div class="box box-solid ">
                <div class="box-body home-box-maxh">
                    <div class="form-group">
                        <label class="control-label">Search Document</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" @keydown.enter="search" v-model="filters.find">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"
                                    aria-expanded="false"> Filter
                                    <span class="fa fa-caret-down"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Action</a></li>
                                    <li><a href="#">Another action</a></li>
                                    <li><a href="#">Something else here</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#">Separated link</a></li>
                                </ul>
                            </div>

                        </div>
                    </div>

                    <datatable :buttons="false" :parameters="filters" ref="datatables" :columns="columns" url="/util/find_documents"></datatable>

                </div>
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
                systems: [],
                columns: [{
                    data: 'document_number',
                    label: '#',
                    export: true,
                    sortable: false,
                    searchable: false,
                    className: 'fit',
                },
                {
                    data: 'title',
                    label: 'Document',
                    export: true,
                    sortable: false,
                    searchable: false,
                    className: 'fit',
                    render:function(data){
                        return `<a href="#"><strong>`+data+`</strong></a>`;
                    }
                },
                
                ]
            }
        },
        methods: {
            search:function(){
                this.$refs.datatables.reload();

            },
            get_systems: function () {
                let parent = this;
                axios.post('/util/system_list_ws').then(function (response) {
                    parent.systems = response.data;
                });
            }
        },
        mounted() {
            this.$nextTick(function () {
                this.get_systems();
            });
        }
    }

</script>

<style>
    ul.document-list {
        padding: 0px;
        width: 100%;
        list-style-type: none;
    }


    ul.document-list li.treeview {
        padding: 5px;
    }


    .home-box-maxh {
        min-height: 410px;
    }

</style>
