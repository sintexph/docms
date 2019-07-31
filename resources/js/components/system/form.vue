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
    </div>

</template>

<script>
    export default {
        props: ['value'],
        data: function () {
            return {
                system: {
                    code: '',
                    name: '',
                }
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
        }
    }

</script>
