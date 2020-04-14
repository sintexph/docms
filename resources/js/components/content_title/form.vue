<template>

    <div>
        <div class="form-group">
            <label class="label-control">Name</label>
            <input class="form-control" v-model="content_title.name">
        </div>

        <div class="form-group">
            <label class="label-control">Code</label>
            <input class="form-control" v-model="content_title.code">
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
                content_title: new ContentTitle ,
            }
        },
        watch: {
            value: {
                deep: true,
                handler: function (val) {
                    this.content_title = val;
                }
            },
            content_title: {
                deep: true,
                handler: function (val) {
                    this.$emit('input', val);
                }
            }
        } 
    }

</script>
