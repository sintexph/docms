<template>
    <button type="button" @click.prevent="review" class="btn btn-primary btn-sm">Confirm as Reviewer</button>
</template>
<script>
    export default {
        props: ['version_reviewer_id'],
        data:function(){
            return {
                submitted:false,
            }
        },
        methods: {
            review: function () {

                var par = this;
                if (par.submitted == false) {
                    var r = confirm("Do you want to update the document version as reviewed?");
                    if (r == true) {
                        par.submitted = true;
                        // Show wait modal
                        par.show_wait("Please wait while the system is updating the document...");

                        axios.patch('/for_review/review/' + par.version_reviewer_id).then(function (response) {
                            par.alert_success(response);
                            par.submitted = false;
                            // Hide wait modal
                            par.hide_wait();
                            location.reload();

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
