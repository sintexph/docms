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




            </div>
            <div class="alert-custom alert-custom-warning">There will be default reviewers and approvers for each system
                but you still have an option to add if they were not added to the default list.</div>
            <div class="box-body">

                <div class="form-group" style="min-width: 150px;">
                    <label class="control-label">Reviewers</label>
                    <select2 v-model="version.reviewers" style_name="width:100%" :multiple="true"
                        :options="reviewers_data">
                    </select2>
                    <ul v-if="system" class="list-unstyled">
                        <li><strong>Default Reviewers</strong>
                            <ul>
                                <li v-for="(value,key) in system.reviewer_names" :key="key">{{ value.name }}</li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="form-group" style="min-width: 150px;">
                    <label class="control-label">Approvers</label>
                    <select2 v-model="version.approvers" style_name="width:100%" :multiple="true"
                        :options="approvers_data">
                    </select2>
                    <ul v-if="system" class="list-unstyled">
                        <li><strong>Default Approvers</strong>
                            <ul>
                                <li v-for="(value,key) in system.approver_names" :key="key">{{ value.name }}</li>
                            </ul>
                        </li>
                    </ul>
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
                type: Object,
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
                default_reviewers: [],
                default_approvers: [],
                reviewers_data: [],
                approvers_data: [],
                system: null,
            }
        },
        methods: {
            load_list: function () {
                let parent = this;
                axios.post('/util/reviewers').then((response) => {
                    response.data.forEach(function (data) {

                        if (parent.default_reviewers.indexOf(data.id) === -1) {
                            parent.reviewers_data.push({
                                text: data.name,
                                id: data.id
                            });
                        }

                    });
                });
                axios.post('/util/approvers').then((response) => {
                    response.data.forEach(function (data) {

                        if (parent.default_approvers.indexOf(data.id) === -1) {
                            parent.approvers_data.push({
                                text: data.name,
                                id: data.id
                            });
                        }
                    });
                });
            },
            load_authorizations() {
                if (this.system.reviewer_ids) {
                    this.system.reviewer_ids.forEach(element => {
                        this.default_reviewers.push(parseInt(element));
                    });
                }
                if (this.system.approver_ids) {
                    this.system.approver_ids.forEach(element => {
                        this.default_approvers.push(parseInt(element));
                    });
                }
                this.load_list();
            }


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

            var vm = this;
            if (vm.value instanceof DocumentVersion) {

                vm.version.number = vm.value.number;
                vm.version.content = vm.value.content;
                vm.version.description = vm.value.description;
                vm.version.reviewers = vm.value.reviewers;
                vm.version.approvers = vm.value.approvers;
                vm.version.effective_date = vm.value.effective_date;
                vm.version.expiry_date = vm.value.expiry_date;
            }

            EVENT_BUS.$on("SYSTEM_CHANGED", function (system) {
                vm.system = system;
                vm.version.reviewers = [];
                vm.version.approvers = [];
                vm.load_authorizations();
            });
            EVENT_BUS.$on("SYSTEM_LOADED", function (system) {
                vm.system = system;
                vm.load_authorizations();
            });

        },
    }

</script>
