<template>
    <div class="box box-solid">

        <div class="box-header">
            <h3 class="box-title">Change Document Owner</h3>
        </div>
        <div class="box-body">



            <form @submit.prevent="change_owner" method="post">

                <div class="form-group">
                    <label><i class="fa fa-user-circle" aria-hidden="true"></i> Document Owner</label>
                    <select2 :options="creator_options" v-model="document.created_by" style="width:100%;"
                        :required="true">
                    </select2>
                </div>

                <button type="submit" class="btn btn-sm btn-danger">
                    Update Owner
                </button>
            </form>

        </div>
    </div>
</template>
<script>
    export default {
        props: {
            created_by: {
                type: [Number, String],
                required: true,
            },
              document_id: {
                type: [Number, String],
                required: true,
            },

        },
        data() {
            return {
                creator_options: [],
                document: new Document,
                submitted:false,
            }
        },
        methods: {
            change_owner() {

                let parent = this;
                if (parent.submitted === false) {
                    parent.submitted = true;
                    axios.patch('/manage/documents/change-owner/' + parent.document.id, {
                        created_by: parent.document.created_by
                    }).then(function (response) {
                        parent.alert_success(response);
                        location.reload();
                    }).catch(function (error) {
                        parent.submitted = false;
                        parent.alert_failed(error);
                    });

                }

            }
        },
        mounted() {

            this.document.created_by = this.created_by;
            this.document.id=this.document_id;

            // Load the creator list
            axios.post('/util/users-select2').then(response => {
                this.creator_options = response.data.results;
            });

        }
    }

</script>
