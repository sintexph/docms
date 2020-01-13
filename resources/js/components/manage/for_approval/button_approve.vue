<template>
    <button type="button" @click.prevent="approve" class="btn btn-primary btn-sm">Approved Document Version</button>
</template>
<script>
    export default {
        props: ['version_approver_id'],
        data: function () {
            return {
                submitted: false,
            }
        },
        methods: {
            approve: function () {

                var par = this;
                if (par.submitted == false) {
                    var r = confirm("Do you want to update the document version as approved?");
                    if (r == true) {
                        par.submitted = true;
                        // Show wait modal
                        par.show_wait("Please wait while the system is updating the document...");

                        axios.patch('/for_approval/approve/' + par.version_approver_id).then(function (response) {
                            // Hide wait modal
                            par.hide_wait();
                            par.alert_success(response);
                            par.submitted = false;

                            setTimeout(() => {
                                location.reload();
                            }, 1000);

                        }).catch(function (error) {
                            // Hide wait modal
                            par.hide_wait();

                            par.submitted = false;
                            par.alert_failed(error);
                        });


                    }
                }


            }
        }
    }

</script>
