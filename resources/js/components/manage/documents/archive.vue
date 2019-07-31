<template>
    <div class="box box-solid">
        <div class=" box-header with-border">
            <h3 class="box-title"><i class="fa fa-archive" aria-hidden="true"></i> Archive Document</h3>
        </div>
        <div class="box-body">
            <p v-if="archived===true">This document has been archived</p>
            <p v-else-if="archived===false">Noted: Archiving the document will cause inaccessibilty to users.</p>
        </div>

        <div class="box-footer">
            <button v-if="archived===true" @click.prevent="remove_archive" type="button" class="btn btn-sm btn-default">Remove
                from Archived</button>
            <button v-if="archived===true" @click.prevent="trash" type="button" class="btn btn-sm btn-danger"><i class="fa fa-trash" aria-hidden="true"></i> Trash
                Document</button>


            <button v-if="archived===false" @click.prevent="put_archive" type="button" class="btn btn-sm btn-default">Put
                to Archive</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            archived: {
                type: [Boolean],
                default: function () {
                    return false;
                }
            },
            document_id: {
                type: [Number, String],
            }
        },
        methods: {
            put_archive: function () {

                var par = this;
                var r = confirm("Are you sure you want to put the document to archive?");
                if (r == true) {
                    axios.patch('/manage/documents/archive_document/' + par.document_id).then(function (response) {
                        par.alert_success(response);
                        location.reload();
                    }).catch(function (error) {
                        par.submitted = false;
                        par.alert_failed(error);
                    });
                }

            },
             trash: function () {
                var par = this;
                var r = confirm("Are you sure you want to put to trash this document?");
                if (r == true) {
                    axios.patch('/manage/documents/put_trash/' + par.document_id).then(function (
                        response) {
                        par.alert_success(response);
                        window.location.assign("/manage/documents");
                    }).catch(function (error) {
                        par.submitted = false;
                        par.alert_failed(error);
                    });
                }
            },
            remove_archive: function () {
                var par = this;
                var r = confirm("Are you sure you want to remove the document to archive?");
                if (r == true) {
                    axios.patch('/manage/documents/remove_archive_document/' + par.document_id).then(function (
                        response) {
                        par.alert_success(response);
                        location.reload();
                    }).catch(function (error) {
                        par.submitted = false;
                        par.alert_failed(error);
                    });
                }
            }
        }
    }

</script>
