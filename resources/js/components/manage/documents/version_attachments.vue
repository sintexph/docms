<template>

    <div class="box box-solid">
        <div class=" box-header with-border">
            <h3 class="box-title">Attachments</h3>
        </div>
        <div class="box-body table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>File</th>
                        <th class="fit">Type</th>
                        <th class="fit">Uploaded By</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="attachments.length===0">
                        <td colspan="6">
                            <center>No uploaded files..</center>
                        </td>
                    </tr>
                    <tr v-for="(value,key) in attachments" :key="key">

                        <td class="fit">
                            <a :href="value.upload.download_link" title="Download the file">
                                <i class="fa fa-download" aria-hidden="true"></i>
                            </a>
                            <template v-if="can_delete===true">
                                <span>&nbsp;&nbsp;&nbsp;</span>
                                <a href="#" @click.prevent="delete_file(value.id)" class="text-red"
                                    title="Remove the file">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                            </template>

                        </td>
                        <td>
                            <div>{{ value.upload.file_name }}</div>
                            <div><small>{{ value.upload.file_size }}</small></div>
                        </td>
                        <td>{{ value.upload.file_type }}</td>
                        <td  class="fit">
                            <div>{{ value.upload.uploaded_by }}</div>
                            <div><small>{{ value.upload.created_at }}</small></div>
                        </td> 
                    </tr>

                </tbody>
            </table>

        </div>
    </div>


</template>
<script>
    export default {
        props: {
            can_delete: {
                type: Boolean,
                default () {
                    return false;
                }
            },
            attachments: {
                type: [Object, Array],
                default: function () {
                    return [];
                }
            }
        },
        methods: {
            delete_file: function (id) {
                var par = this;
                var r = confirm("Are you sure you want to remove the file?");
                if (r == true) {
                    axios.delete('/manage/documents/file/remove/' + id).then(function (response) {
                        par.alert_success(response);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }).catch(function (error) {
                        par.submitted = false;
                        par.alert_failed(error);
                    });
                }
            }

        },

    }

</script>
