<template>
    <div class="box box-solid">
        <div class=" box-header with-border">
            <h3 class="box-title">Lock Document</h3>
        </div>
        <div class="box-body">
            <p><strong>Note:</strong> Locking the document may caused inaccessibility.</p>
        </div>
        <div class="box-footer">
           
            <button v-if="lock===true" class="btn btn-sm btn-default" @click.prevent="unlock_document"><i class="fa fa-unlock-alt"
                    aria-hidden="true"></i>
                Unlock Document</button>
         
            <button v-else-if="lock===false" class="btn btn-sm btn-default" @click.prevent="lock_document"><i class="fa fa-lock"
                    aria-hidden="true"></i>
                Lock Document</button>
          
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            lock: {
                type: Boolean,
                default: function () {
                    return false;
                },
            },
            document_id: {
                type: [String, Number],
                required: true,
            }
        },
        data:function(){
            return {
                submitted:false,
            }
        },
        methods: {
            lock_document: function () {
                

                var par = this;
                if (par.submitted === false) {
                    if (confirm('Do you want to lock the document?') === true) {
                        par.submitted = true;
                        axios.patch('/manage/documents/lock_document/' + par.document_id).then(function (response) {
                            par.alert_success(response);
                            location.reload();
                        }).catch(function (error) {
                            par.submitted = false;
                            par.alert_failed(error);
                        });
                    }
                }

            },
            unlock_document: function () {

                var par = this;
                if (par.submitted === false) {
                    if (confirm('Do you want to unlock the document?') === true) {
                        par.submitted = true;
                        axios.patch('/manage/documents/unlock_document/' + par.document_id).then(function (response) {
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

    }

</script>
