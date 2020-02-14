<template>
    <div>

        <div class="form-group" style="min-width: 150px;">
            <label class="control-label"><i class="fa fa-lock" aria-hidden="true"></i> Access</label>
            <select2 :options="access_options" style_name="width:100%;" placeholder="Please choose a access"
                :required="true" v-model="access"></select2>
        </div>

    </div>
</template>
<script>
    export default {
        props: {
            value: {
                default: function () {
                    return null;
                }
            }
        },
        data: function () {
            return {
                access: null,
                access_options: [],


            }
        },



        watch: {
            value: {
                deep: true,
                handler: function (val) {
                    this.access = val;
                }
            },
            access: {
                deep: true,
                handler: function (val) {
                    this.$emit('input', val);
                }
            }
        },

        mounted: function () {
            var vm=this;
            if (vm.value !== null)
                vm.access = vm.value;
            else
                vm.access = vm.DOC_ACCESS.PUBLIC;
 
            vm.access_options.push({
                id: vm.DOC_ACCESS.PUBLIC,
                text: 'PUBLIC',
            });

            vm.access_options.push({
                id: vm.DOC_ACCESS.CONFIDENTIAL,
                text: 'CONFIDENTIAL',
            });
        }

    }

</script>
