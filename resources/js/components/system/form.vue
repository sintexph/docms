<template>

    <div>
        <div class="form-group">
            <label class="label-control">Name</label>
            <input class="form-control" v-model="system.name">
        </div>

        <div class="form-group">
            <label class="label-control">Code</label>
            <input class="form-control text-uppercase" v-model="system.code">
        </div>

        <div class="form-group">
            <label class="label-control">Reviewers</label>
            <select2 :multiple="true" v-model="system.reviewer_ids" style="width:100%;" :options="reviewers"></select2>
        </div>

        <div class="form-group">
            <label class="label-control">Approvers</label>
            <select2 :multiple="true" v-model="system.approver_ids" style="width:100%;" :options="approvers"></select2>
        </div>




    </div>

</template>

<script>
    export default {
        props: ['value'],
        data: function () {
            return {
                reviewers: [],
                approvers: [],
                system: new SystemClass ,
            }
        },
        watch: {
            value: {
                deep: true,
                handler: function (val) {
                    this.system = val;
                }
            },
            system: {
                deep: true,
                handler: function (val) {
                    this.$emit('input', val);
                }
            }
        },
        methods: {
            load_list: function () {
                let parent = this;
                axios.post('/util/reviewers').then((response) => {
                    response.data.forEach(function (data) {
                        parent.reviewers.push({
                            text: data.name,
                            id: data.id
                        })
                    });
                });
                axios.post('/util/approvers').then((response) => {
                    response.data.forEach(function (data) {
                        parent.approvers.push({
                            text: data.name,
                            id: data.id
                        })
                    });
                });
            },
        },
        mounted() {
            this.load_list();
        }
    }

</script>
