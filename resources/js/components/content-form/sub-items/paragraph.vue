<template>
    <modal :name="'paragraph'+_uid" ref="modal" :extended_width="true">
        <template slot="header">Add Paragraph</template>
        <template slot="body">
            <c-par ref="cParagraph" v-model="paragraph"></c-par>
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
                paragraph: new Paragraph
            }
        },
        methods: {
            show: function () {
                this.$refs.cParagraph.reset();
                this.$refs.modal.show();
            },
            edit: function (paragraph, id) {

                this.$refs.cParagraph.reset();
                this.id = id;
                this.paragraph.value = paragraph.value;
                if (paragraph.meta !== undefined)
                    this.paragraph.meta = paragraph.meta;

                this.$refs.modal.show();
            },

            emit_save: function (varpar) {
                this.$emit('saved', varpar);

            },
            emit_update: function (varpar) {
                this.$emit('updated', {
                    id: this.id,
                    updated_data: varpar
                });
                this.id = '';

            },

            save: function () {
                var vm = this;

                // Check if the paragraph is chunk by break line
                if (vm.$refs.cParagraph.isChunk === true) {
                    var chunk_value = vm.$refs.cParagraph                                                                                                                                                                                        .chunk();

                    // Tell the parent that there are multiple values to be saved
                    chunk_value.forEach(function (parChunked) {
                        if (vm.id === '') {
                            vm.emit_save(parChunked);
                        } else {
                            vm.emit_update(parChunked);
                        }
                    });
                } else {
                    if (vm.id === '') {
                        vm.emit_save(vm.paragraph);
                    } else {
                        vm.emit_update(vm.paragraph);
                    }
                }
                vm.$refs.modal.dismiss();
            },
        },
    }

</script>
