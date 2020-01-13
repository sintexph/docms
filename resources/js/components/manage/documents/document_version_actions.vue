<template>
    <div v-if="document.locked===false" class="box box-solid">
        <div class=" box-header with-border">
            <h3 class="box-title">Actions</h3>
        </div>
        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                <li>
                    <a href="#" v-if="selected_version.for_review===false && selected_version.reviewed===false"
                        @click.prevent="send_for_review">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                        <span>Send for Review</span>
                    </a>
                    <a href="#" v-if="selected_version.for_review===true && selected_version.reviewed===false"
                        @click.prevent="cancel_send_for_review">
                        <i class="fa fa-close" aria-hidden="true"></i>
                        <span>Cancel for Review</span>
                    </a>
                </li>

                <li>

                    <a href="#" v-if="selected_version.for_approval===false && selected_version.approved===false"
                        @click.prevent="send_for_approval">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                        <span>Send for Approval</span>
                    </a>
                    <a href="#" v-if="selected_version.for_approval===true && selected_version.approved===false"
                        @click.prevent="cancel_send_for_approval">
                        <i class="fa fa-close" aria-hidden="true"></i>
                        <span>Cancel for Approval</span>
                    </a>
                </li>

                <li><a :href="'/content/download/version/'+selected_version.id">
                        <i class="fa fa-download" aria-hidden="true"></i>
                        <span>Download Document</span></a></li>
                <li><a :href="'/home/view/'+selected_version.id" target="_blank"><i class="fa fa-star"
                            aria-hidden="true"></i>
                        <span>View Online</span></a></li>
       
                <li>
                    <a href="?latab=ev" v-if="selected_version.reviewed==false || selected_version.approved==false">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                        <span>Modify Version</span>
                    </a>
                    <a v-else class="disabled">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                        <span>Modify Version</span>
                    </a>
                </li>
                <li><a href="#" @click.prevent="roll_back"><i class="fa fa-undo" aria-hidden="true"></i>
                        <span>Roll Back</span>
                    </a></li>

                <li>
                    <a v-if="selected_version.approved===true && selected_version.released===false" href="#"
                        @click.prevent="release_version"><i class="fa fa-file-text-o" aria-hidden="true"></i>
                        <span>Release</span></a>
                    <a class="disabled" v-else><i class="fa fa-file-text-o" aria-hidden="true"></i>
                        <span>Release</span></a>
                </li>
                <li>
                    <a href="#" @click.prevent="$refs.viewVersion.show('/content/view/version/'+selected_version.id)">
                        <i class="fa fa-arrows-alt" aria-hidden="true"></i>
                        <span>Full View</span></a>
                </li>
            </ul>

            <view-version ref="viewVersion"></view-version>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['document', 'selected_version'],
        data: function () {
            return {
                submitted: false
            }
        },
        methods: {
            release_version: function () {
                var par = this;
                if (par.submitted === false) {
                    if (confirm('Are you sure you want to release the version?') === true) {
                        par.submitted = true;
                        axios.patch('/manage/documents/release/' + par.selected_version.id).then(function (
                            response) {
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
            roll_back: function () {
                var par = this;
                if (par.submitted === false) {
                    if (confirm('Are you sure you want to roll back the document version?') === true) {
                        par.submitted = true;
                        axios.delete('/manage/documents/roll_back/' + par.document.id).then(function (response) {
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
            send_for_review: function () {
                var par = this;
                if (par.submitted === false) {
                    if (confirm('Do you want to send the document version for review?') === true) {
                        par.submitted = true;
                        // Show wait modal
                        par.show_wait("Please wait while the system is sending the emails to the reviewers...");

                        axios.patch('/manage/documents/for_review/' + par.selected_version.id).then(function (
                            response) {
                            par.alert_success(response);
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

            },
            cancel_send_for_review: function () {
                var par = this;
                if (par.submitted === false) {
                    if (confirm('Do you want to cancel the document version for review?') === true) {
                        par.submitted = true;
                        axios.patch('/manage/documents/for_review/cancel/' + par.selected_version.id).then(
                            function (
                                response) {
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
            send_for_approval: function () {
                var par = this;
                if (par.submitted === false) {
                    if (confirm('Do you want to send the document version for approval?') === true) {
                        par.submitted = true;
                        // Show wait modal
                        par.show_wait("Please wait while the system is sending the emails to the approvers...");

                        axios.patch('/manage/documents/for_approval/' + par.selected_version.id).then(function (
                            response) {
                            par.hide_wait();
                            par.alert_success(response);
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

            },
            cancel_send_for_approval: function () {
                var par = this;
                if (par.submitted === false) {
                    if (confirm('Do you want to cancel the document version for approval?') === true) {
                        par.submitted = true;
                        axios.patch('/manage/documents/for_approval/cancel/' + par.selected_version.id).then(
                            function (
                                response) {
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

            }
        }
    }

</script>
