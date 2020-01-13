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
                        <th class="fit">File Type</th>
                        <th class="fit">File Size</th>
                        <th class="fit">Uploaded By</th>
                        <th class="fit">Uploaded At</th>
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
                            <span>&nbsp;&nbsp;&nbsp;</span>
                            <a href="#" @click.prevent="delete_file(value.id)" class="text-red" title="Remove the file">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                        </td>
                        <td>{{ value.upload.file_name }}</td>
                        <td>{{ value.upload.file_type }}</td>
                        <td>{{ value.upload.file_size }}</td>
                        <td>{{ value.upload.uploaded_by }}</td>
                        <td>{{ value.upload.created_at }}</td>
                    </tr>

                </tbody>
            </table>

        </div>
    </div>


</template>
<script>
    export default {
        props: {
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
