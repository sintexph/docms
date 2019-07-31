<template>

    <div>
        <div class="form-group">
            <label class="label-control">Name</label>
            <input class="form-control" v-model="category.name">
        </div>

        <div class="form-group">
            <label class="label-control">Code</label>
            <input class="form-control text-uppercase" v-model="category.code">
        </div>
    </div>

</template>

<script>
    export default {
        props: ['value'],
        data: function () {
            return {
                category: {
                    code: '',
                    name: '',
                }
            }
        },
        watch: {
            value: {
                deep: true,
                handler: function (val) {
                    this.category = val;
                }
            },
            category: {
                deep: true,
                handler: function (val) {
                    this.$emit('input', val);
                }
            }
        }
    }

</script>
