<template>

    <div>
        <div class="form-group">
            <label class="label-control">Name</label>
            <input class="form-control" v-model="section.name">
        </div>

        <div class="form-group">
            <label class="label-control">Code</label>
            <input class="form-control text-uppercase" v-model="section.code">
        </div>

        <div class="form-group">
            <label class="label-control">System</label>

            <select class="form-control" v-model="section.system_code">
                <option value="">-- Please Select --</option>
                <option v-for="(value,key) in system_list" :key="key" :value=" value.code">{{ value.name }}</option>
            </select>
        </div>

    </div>

</template>

<script>
    export default {
        props: ['value'],
        data: function () {
            return {
                section: {
                    code: '',
                    name: '',
                    system_code: '',
                },
                system_list: [],
            }
        },
        watch: {
            value: {
                deep: true,
                handler: function (val) {
                    this.section = val;
                }
            },
            section: {
                deep: true,
                handler: function (val) {
                    this.$emit('input', val);
                }
            }
        },
        methods: {
            fetch_systems: function () {
                var par = this;
                axios.post('/util/system_list').then(function (response) {
                    par.system_list = response.data;
                });
            }
        },
        mounted() {
            this.fetch_systems();
        },
    }

</script>
