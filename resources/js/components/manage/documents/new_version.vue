<template>
    <form @submit.prevent="submit">
        <version-form :show_version="false" v-model="version"></version-form>
        <button class="btn btn-sm btn-success" type="submit">Save new Version</button>
    </form>
</template>
<script>
    export default {
        props: {
            document_id: [String, Object],
            document_system:String,
            current_version_content: [Array, Object],
        },
        data: function () {
            return {
                version: new DocumentVersion,
                submitted: false,
            }
        },
        methods: {
            submit: function () {

                let parent = this;
                if (parent.submitted === false) {
                    parent.submitted = true;

                    // Show wait modal
                    parent.show_wait("Please wait while the system is saving the document...");


                    axios.patch('/manage/documents/new_version/' + parent.document_id, {
                        version: this.version.toObject()
                    }).then(function (response) {
                        parent.hide_wait();
                        parent.alert_success(response);
                        setTimeout(() => {
                            window.location.replace('/manage/documents/view/' + parent.document_id);
                        }, 1000);

                    }).catch(function (error) {

                        // Hide wait modal
                        parent.hide_wait();

                        parent.submitted = false;
                        parent.alert_failed(error);
                    });
                }

            },
            get_system(code) {
                axios.post('/util/get-system/' + code).then(response => {
                    EVENT_BUS.$emit("SYSTEM_LOADED", response.data);
                });
            }
        },
        mounted() {
            this.$nextTick(function () {
                this.version.content = this.cast_to_content(this.current_version_content);

                if (this.document_system)
                    this.get_system(this.document_system);

            });


        }
    }

</script>
