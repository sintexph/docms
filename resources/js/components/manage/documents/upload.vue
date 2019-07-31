<template>
    <div class="box box-solid">
        <div class=" box-header with-border">
            <h3 class="box-title">Upload File</h3>
        </div>
        <form @submit.prevent="upload">
            <div class="box-body">
                <div class="form-group">
                    <label for="">Browse File</label>
                    <input-file accept=".pdf,  .xlsx,  .xls,  .docx, .txt" :multiple="true" v-model="files" :required="true"></input-file>
                </div>
                <button type="submit" class="btn btn-default pull-right"><i class="fa fa-save"></i> Upload File</button>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        props: ['document_id'],
        data: function () {
            return {
                files: null,
                submitted: false,
            }
        },
        methods: {
            upload: function () {
                let par = this;
                if (par.submitted === false) {
                    par.submitted = true;


                    // Show wait dialog
                    par.show_wait("Please wait while the system is uploading the files....");

                    // Set the form data
                    let form = new FormData();
                    for (var i = 0; i < par.files.length; i++) {
                        let file = par.files[i];
                        form.append('files[' + i + ']', file);
                    }
                    form.append('_method', 'PUT');


                    // Send request to server
                    axios.post('/manage/documents/file/upload/' + par.document_id, form, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(function (response) {
                        par.alert_success(response);
                        location.reload();
                    }).catch(function (error) {
                        //hide wait dialog
                        par.hide_wait();
                        par.submitted = false;
                        par.alert_failed(error);
                    });


                }


            }
        }
    }

</script>
