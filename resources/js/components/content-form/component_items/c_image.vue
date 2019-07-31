<template>
    <div>
        <div class="box box-solid">
            <div class="box-body">
                <form @submit.prevent="upload" method="post">
                    <div class="form-group">
                        <label for="">Browse File</label>
                        <input-file accept=".jpeg,  .png, .jpg" :multiple="false" v-model="file" :required="true">
                        </input-file>
                    </div>
                    <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-upload" aria-hidden="true"></i>
                        Upload</button>

                </form>
            </div>
        </div>
        <div class="box box-solid">
            <div class="box-header">
                <h3 class="box-title">Image Controls</h3>
            </div>

            <div class="box-body">
                <div class="form-inline">

                    <div class="form-group">
                        <label>Width</label>
                        <input type="number" v-model="image.meta.width" class="form-control input-sm">
                    </div>
                    <div class="form-group">
                        <label>Height</label>
                        <input type="number" v-model="image.meta.height" class="form-control input-sm">
                    </div>


                    <div class="form-group">
                        <label>Position</label>
                        <select v-model="image.meta.position" class="form-control input-sm">
                            <option value="">-- Select --</option>
                            <option value="center">Center</option>
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                        </select>
                    </div>

                </div>
            </div>
        </div>
        <div class="box box-solid">
            <div class="box-header">
                <h3 class="box-title">Image Preview</h3>
            </div>
            <div class="box-body">
                <div v-if="image.upload_id!==null" v-html="image.toString()"></div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</template>
<script>
    export default {
        props: {
            value: {
                type: [Object, Array],
                default: function () {
                    return new Image;
                }
            }
        },
        data: function () {
            return {

                image: new Image,
                file: null,
                submitted: false,
            }
        },
        methods: {
            done: function (response) {
                this.image.upload_id = response.data.upload_id;
            },
            upload: function () {
                let par = this;
                if (par.submitted === false) {
                    par.submitted = true;
                    // Show wait dialog
                    par.show_wait("Please wait while the system is uploading the files....");

                    // Set the form data
                    let form = new FormData();

                    form.append('file', par.file);

                    form.append('_method', 'PUT');
                    // Send request to server
                    axios.post('/upload/image', form, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(function (response) {
                        //hide wait dialog
                        par.hide_wait();
                        par.submitted = false;
                        par.done(response);
                    }).catch(function (error) {
                        //hide wait dialog
                        par.hide_wait();
                        par.submitted = false;
                        par.alert_failed(error);
                    });


                }
            },
            reset: function () {
                this.file = null;
                this.image = new Image;
                this.$emit('input', this.image);
            }
        },
        mounted() {
            this.$nextTick(function () {
                this.image = this.value;
            });
        },
        watch: {
            image: {
                deep: true,
                handler: function (image) {
                    this.$emit('input', image);
                }
            },
            value: {
                deep: true,
                handler: function (value) {
                    this.image = value;
                }
            }
        }
    }

</script>
