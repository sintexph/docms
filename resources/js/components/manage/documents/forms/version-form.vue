<template>
    <div>

         
        <div class="box box-solid">
            <div class="box-header">
                <h3 class="box-title">
                    <slot name="header">Version Information</slot>
                </h3>
            </div>
            <div class="box-body">
                <div class="form-group" v-if="show_version===true">
                    <label class="control-label">Version <span class="text-red">*</span></label>
                    <input type="number" class="form-control" v-model="version.number" required>
                </div>
                <div class="form-group">
                    <label class="control-label">Effective Date <span class="text-red">*</span></label>
                    <date-mask :required="true" v-model="version.effective_date"></date-mask>
                </div>
                <div class="form-group">
                    <label class="control-label">Expiry Date</label>
                    <date-mask v-model="version.expiry_date"></date-mask>
                </div>
                <div class="form-group" style="min-width: 150px;">
                    <label class="control-label">Reviewers</label>
                    <select2 v-model="version.reviewers" style_name="width:100%" :multiple="true"
                        :options="reviewers_data">
                    </select2>
                </div>
                <div class="form-group" style="min-width: 150px;">
                    <label class="control-label">Approvers</label>
                    <select2 v-model="version.approvers" style_name="width:100%" :multiple="true"
                        :options="approvers_data">
                    </select2>
                </div>
            </div>
        </div>
        <label class="text-uppercase">Revision Logs</label>
        <main-item v-model="version.description"></main-item>
        <label class="text-uppercase">Content</label>
        <main-item v-model="version.content"></main-item>

    </div>
</template>
<script>


    export default {
      
        props: {
            value: {
                type: [Object, Array],
                required: true,
            },
            show_version: {
                default: function () {
                    return true;
                },
                type: Boolean
            },
            data_required: {
                default: function () {
                    return true;
                },
                type: Boolean
            },
        },
        data: function () {
            return {
                file_word_convert: null,
                read_file_word: false,
                file_converting: false,
                test: null,
                version: new DocumentVersion,
                reviewers_data: [],
                approvers_data: [],
            }
        },
        methods: {
            load_list: function () {
                let parent = this;
                axios.post('/util/reviewers').then((response) => {
                    response.data.forEach(function (data) {
                        parent.reviewers_data.push({
                            text: data.name,
                            id: data.id
                        })
                    });
                });
                axios.post('/util/approvers').then((response) => {
                    response.data.forEach(function (data) {
                        parent.approvers_data.push({
                            text: data.name,
                            id: data.id
                        })
                    });
                });
            },


        },
        watch: {
            version: {
                deep: true,
                handler: function (val) {
                    this.$emit('input', val);
                }
            },
            value: {
                deep: true,
                handler: function (val) {
                    this.version = val;
                },

            }
        },
        mounted: function () {
            if (this.value !== null) {

                this.version.number = this.value.number;
                this.version.content = this.value.content;
                this.version.description = this.value.description;
                this.version.reviewers = this.value.reviewers;
                this.version.approvers = this.value.approvers;
                this.version.effective_date = this.value.effective_date;
                this.version.expiry_date = this.value.expiry_date;
            }



            this.load_list();


        },
    }

</script>
