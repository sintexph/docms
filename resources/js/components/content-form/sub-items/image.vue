<template>
    <modal :name="'image'+_uid" ref="modal" :extended_width="true">
        <template slot="header">Add Image</template>
        <template slot="body">
            <c-image ref="cImage" v-model="image"></c-image>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-default" @click.prevent="save">Save</button>
        </template>
    </modal>
</template>
<script>
    export default {
        data: function () {
            return {
                id: '',
                image: new Image,

            }
        },
        methods: {
            show: function () {
                this.$refs.cImage.reset();
                this.$refs.modal.show();
            },
            edit: function (image, id) {
                this.$refs.cImage.reset();
                this.id = id;
                this.image.upload_id = image.upload_id;
                this.image.meta.width = image.meta.width;
                this.image.meta.height = image.meta.height;
                this.image.meta.position = image.meta.position;
                this.image.meta.class = image.meta.class;
                this.$refs.modal.show();
            },
            save: function () {
                this.image.meta.class = this.img_class;
                this.image.meta.style = this.style;

                if (this.id === '') {
                    // Save
                    this.$emit('saved', this.image);
                } else {
                    // update
                    this.$emit('updated', {
                        id: this.id,
                        updated_data: this.image
                    });
                    // Reset id
                    this.id = '';
                }
                this.$refs.modal.dismiss();
            },
        }
    }

</script>
