<template>
    <div class="box box-solid">
        <div class="box-header">
            <h3 class="box-title">Edit access of the document</h3>
        </div>
        <form @submit.prevent="submit_access">
            <div class="box-body">

                <access-form v-model="access"></access-form>



            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-sm btn-success">Update Access</button>
            </div>
        </form>
    </div>




</template>
<script>
    export default {
        props: {
            document_id: {
                required: true,
            },
            current_access: {
                required: true,
            },
        },
        data: function () {
            return {
                access: null,
                submitted: false,
            }
        },
        methods: {


            submit_access: function () {
                let parent = this;
                if (parent.submitted === false) {
                    parent.submitted = true;
                    axios.patch('/manage/documents/update_access/' + parent.document_id, {
                        access: parent.access,
                    }).then(function (response) {


                        parent.alert_success(response);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }).catch(function (error) {
                        parent.submitted = false;
                        parent.alert_failed(error);
                    });

                }

            }
        },
        mounted: function () {
            var vm = this;

            if (vm.current_access)
                vm.access = vm.current_access;
            else
                vm.access = this.DOC_ACCESS.PUBLIC;
                
        }
    }

</script>
