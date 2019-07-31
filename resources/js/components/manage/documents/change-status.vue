<template>
    <form @submit.prevent="submit">
        <div class="box box-solid">
            <div class="box-header">
                <h3 class="box-title">Change the status of the current version</h3>
            </div>
            <div class="box-body">
                <div class="form-group" style="min-width: 150px;">
                    <label class="control-label">Status</label>
                    <select2 :select_data="status_data" placeholder="Please choose a status" :required="true" @selected="status_selected"></select2>
                </div>
            </div>
            <div class="box-footer">
                <a href="" class="btn btn-sm btn-default">Cancel</a>
                <button type="submit" class="btn btn-sm btn-success">Change Status</button>
            </div>
        </div>
    </form>

</template>
<script>
    export default {
        props: ['document_id'],
        data: function () {
            return {
                status_data: [

                    {
                        value: 'obsolete',
                        text: 'OBSOLETE',
                    },
                    {
                        value: 'active',
                        text: 'ACTIVE',
                    },
                ],
                submitted: false,
                status: '',
            }
        },
        methods: {
            status_selected: function (val) {
                this.status = val[0].id;
            },
            submit: function () {

                let parent = this;
                if (parent.submitted === false) {
                    parent.submitted = true;
                    axios.patch('/manage/documents/change_status/' + parent.document_id, {
                        status: parent.status
                    }).then(function (response) {
                        parent.alert_success(response);
                        location.reload();
                    }).catch(function (error) {
                        parent.submitted = false;
                        parent.alert_failed(error);
                    });

                }

            }
        }
    }

</script>
