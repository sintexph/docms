<template>
    <div>

        <div class="form-group" style="min-width: 150px;">
            <label class="control-label">Access</label>
            <select3 :options="access_options" style_name="width:100%;" placeholder="Please choose a access" :required="true"
                v-model="access_data.access"></select3>
        </div>
        <div class="form-group" v-if="show_accessors">
            <label class="control-label">Accessors</label>
            <select3 v-model="access_data.accessors" style_name="width:100%;" :multiple="true" :required="true"
                :options="users_options">
            </select3>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            value: {
                type: [Object, Array],
                default: function () {
                    return {
                        access: '1',
                        accessors: [],
                    }
                }
            }
        },
        data: function () {
            return {
                users_options: [],
                access_options: [{
                        id: '1',
                        text: 'CONFIDENTIAL',
                    },
                    {
                        id: '2',
                        text: 'CUSTOM',
                    },
                    {
                        id: '3',
                        text: 'PUBLIC',
                    },
                    {
                        id: '4',
                        text: 'ONLY ME',
                    },
                ],

                access_data: {
                    access: '1',
                    accessors: [],
                }
            }
        },
        computed: {
            show_accessors: function () {
                if (this.access_data.access === "2")
                    return true;
                else
                    return false;
            },

        },

        methods: {
            load_list: function (val) {
                let parent = this;

                axios.post('/util/users').then((response) => {
                    response.data.forEach(function (data) {
                        parent.users_options.push({
                            text: data.name,
                            id: data.id
                        })
                    });

                });
            },

        },
        watch: {
            value: {
                deep: true,
                handler: function (val) {
                    this.access_data = val;
                }
            },
            access_data: {
                deep: true,
                handler: function (val) {
                    this.$emit('input', val);
                }
            }
        },

        mounted: function () {
            this.load_list();
            if (this.value !== null)
                this.access_data = this.value;

        }

    }

</script>
