<template>
    <div v-if="document.locked===false" class="box box-solid">
        <div class=" box-header with-border">
            <h3 class="box-title">Actions</h3>
        </div>
        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                <li><a :href="'/content/download/version/'+selected_version.id">
                        <i class="fa fa-download" aria-hidden="true"></i>
                        <span>Download Document</span></a></li>
                <li><a :href="'/home/view/'+selected_version.id" target="_blank"><i class="fa fa-star"
                            aria-hidden="true"></i>
                        <span>View Online</span></a></li>
                <li>
                    <a :href="'/manage/documents/edit-version/'+document.id" v-if="selected_version.for_review==false">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                        <span>Modify Version</span>
                    </a>
                    <a v-else class="disabled">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                        <span>Modify Version</span>
                    </a>
                </li>
                <li>
                    <a href="#" @click.prevent="submit_version" class="text-green"
                        v-if="selected_version.for_review==false">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                        <span>Submit Version</span>
                    </a>
                    <a href="#" @click.prevent="cancel_submission" class="text-red"
                        v-if="selected_version.for_review==true && selected_version.approved===false">
                        <i class="fa fa-ban" aria-hidden="true"></i>
                        <span>Cancel Submission</span>
                    </a>
                </li>
                <li>
                    <a href="#" @click.prevent="roll_back"><i class="fa fa-undo" aria-hidden="true"></i>
                        <span>Roll Back</span>
                    </a>
                </li>
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
            cancel_submission() {
                var par = this;
                if (par.submitted === false) {
                    if (confirm('Are you sure you want to cancel the submission of the document?') === true) {
                        par.submitted = true;
                        par.show_wait("Please wait while the system is processing your request...");
                        axios.patch('/manage/documents/cancel-submission/' + par.selected_version.id).then(function (
                            response) {
                            par.hide_wait();
                            par.alert_success(response);

                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }).catch(function (error) {
                            par.submitted = false;
                            par.hide_wait();
                            par.alert_failed(error);
                        });
                    }
                }
            },

            submit_version() {
                var par = this;
                if (par.submitted === false) {
                    if (confirm('Do you want to submit now the document?') === true) {
                        par.submitted = true;
                        par.show_wait("Please wait while the system is processing your request...");
                        axios.patch('/manage/documents/submit-version/' + par.selected_version.id).then(function (
                            response) {
                            par.hide_wait();
                            par.alert_success(response);

                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }).catch(function (error) {
                            par.submitted = false;
                            par.hide_wait();
                            par.alert_failed(error);
                        });
                    }
                }
            },
            release_version: function () {
                var par = this;
                if (par.submitted === false) {
                    if (confirm('Are you sure you want to release the version?') === true) {
                        par.submitted = true;
                        par.show_wait("Please wait while the system is processing your request...");
                        axios.patch('/manage/documents/release/' + par.selected_version.id).then(function (
                            response) {
                            par.hide_wait();
                            par.alert_success(response);

                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }).catch(function (error) {
                            par.submitted = false;
                            par.hide_wait();
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
                        par.show_wait("Please wait while the system is processing your request...");
                        axios.delete('/manage/documents/roll_back/' + par.document.id).then(function (response) {
                            par.hide_wait();
                            par.alert_success(response);
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

        }
    }

</script>
