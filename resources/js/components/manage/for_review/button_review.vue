<template>
    <div>
        <button type="button" @click.prevent="review" class="btn btn-primary btn-sm"><i class="fa fa-check"
                aria-hidden="true"></i> Reviewed</button>
        <button type="button" @click.prevent="$refs.modal.show()" class="btn btn-danger btn-sm"><i
                class="fa fa-thumbs-down" aria-hidden="true"></i> Reject</button>

        <modal name="reject-modal" ref="modal">
            <template slot="header">Reject Document</template>
            <template slot="body">
                <textarea rows="3" v-model="comment" placeholder="Please put a reason why you need to reject the document...."
                    class="form-control" required></textarea>
            </template>
            <template slot="footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" @click.prevent="reject">Reject</button>
            </template>
        </modal>
    </div>
</template>
<script>
    export default {
        props: ['version_reviewer_id'],
        data: function () {
            return {
                submitted: false,
                comment:'',
            }
        },
        methods: {
            reject() {
                var par = this;
                if (par.submitted == false) {
                    var r = confirm("Please confirm the document rejection");
                    if (r == true) {
                        par.submitted = true;
                        
                        par.show_wait("Please wait while the system is updating the document...");

                        axios.post('/for_review/reject/' + par.version_reviewer_id,{
                            comment:par.comment,
                        }).then(function (response) {
                            
                            par.hide_wait();
                            par.alert_success(response);
                            par.submitted = false;
                            setTimeout(() => {
                                location.reload();
                            }, 1000);


                        }).catch(function (error) {
                          
                            par.hide_wait();

                            par.submitted = false;
                            par.alert_failed(error);
                        });


                    }
                }

            },
            review: function () {

                var par = this;
                if (par.submitted == false) {
                    var r = confirm("Do you want to update the document version as reviewed?");
                    if (r == true) {
                        par.submitted = true;
                        // Show wait modal
                        par.show_wait("Please wait while the system is updating the document...");

                        axios.patch('/for_review/review/' + par.version_reviewer_id).then(function (response) {
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
