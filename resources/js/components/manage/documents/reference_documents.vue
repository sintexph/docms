<template>
    <div>
        <div class="box box-solid">
            <div class=" box-header with-border">
                <h3 class="box-title">Reference Documents</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Document</th>
                            <th>State</th>
                            <th>Added</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr v-for="(reference,key) in references" :key="key">
                            <td>
                                <span>
                                    <strong>Doc #:</strong>
                                    <strong><a :href="'/manage/documents/view/'+reference.referred_document.id" target="_blank">
                                            {{ reference.referred_document.document_number }}
                                        </a>
                                    </strong>
                                </span><br>
                                <span><strong>Title: </strong> {{ reference.referred_document.title }}</span><br>
                                <span><strong>Version:</strong> {{ reference.referred_document.version }}</span><br>
                            </td>
                            <td>
                                <span v-if="reference.public===true">PUBLIC</span>
                                <span v-else-if="reference.public===false">PRIVATE</span>
                            </td>
                            <td>
                                <span>{{ reference.created_by }}</span><br>
                                <small>{{ reference.created_at }}</small>
                            </td>
                            <td>
                                <a :href="'/home/view/'+reference.referred_document.id" target="_blank"><i
                                        aria-hidden="true" class="fa fa-star"></i> View Online</a><br>
                                <a v-if="reference.public===true" href="#">
                                    <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                    Private</a>
                                <a v-else-if="reference.public===false" href="#">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                    Public</a>
                                <br>


                                <a href="#" @click.prevent="remove_reference(reference.id)"><i class="fa fa-trash-o text-red"
                                        aria-hidden="true"></i> Remove</a>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <div class="box box-solid" v-if="can_initiate===true">
            <div class=" box-header with-border">
                <h3 class="box-title">Add reference to the current document</h3>
            </div>
            <form method="post" @submit.prevent="add_reference">
                <div class="box-body">
                    <div class="form-group">
                        <label for="">Document</label>
                        <input type="text" v-model="document_number" placeholder="Provide document number..." class="form-control"
                            required>
                        <div class="checkbox">
                            <label><input type="checkbox" v-model="is_public">Set Public</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-default pull-right"> <i class="fa fa-save"></i> Save Reference</button>
                </div>
            </form>
        </div>

    </div>
</template>

<script>
    export default {
        props: {
            can_initiate:{
                type:[Boolean],
                default:function(){
                    return false;
                }
            },
            references: {
                type: [Object, Array],
                default: function () {
                    return [];
                }
            },
            document: {
                type: [Object, Array],
                required: true,
            }
        },
        data: function () {
            return {
                submitted: false,
                document_number: '',
                is_public: false,
            }
        },
        methods: {
            add_reference: function () {


                var par = this;
                if (par.submitted === false && par.can_initiate===true) {
                    par.submitted = true;
                    axios.put('/manage/documents/add_reference/' + par.document.id, {
                        document_number: par.document_number,
                        public: par.is_public,
                    }).then(function (response) {
                        par.alert_success(response);
                        location.reload();
                    }).catch(function (error) {
                        par.submitted = false;
                        par.alert_failed(error);
                    });
                }


            },
            remove_reference: function (id) {

                var par = this;
                if (par.submitted === false && par.can_initiate===true) {
                    if (confirm('Do you want to remove the selected reference document?') === true) {
                        par.submitted = true;
                        axios.put('/manage/documents/remove_reference/' + id).then(function (response) {
                            par.alert_success(response);
                            location.reload();
                        }).catch(function (error) {
                            par.submitted = false;
                            par.alert_failed(error);
                        });
                    }
                }


            }
        }
    }

</script>
