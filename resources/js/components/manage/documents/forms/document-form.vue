<template>
    <div class="box box-solid">
        <div class="box-header">
            <h3 class="box-title">
                Document Information
            </h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label">Document Title <span class="text-red">*</span></label>
                        <input type="text" class="form-control" v-model="document.title" autofocus required>
                    </div>
                </div>

                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label">System <span class="text-red">*</span></label>
                        <select class="form-control" @change="system_changed" v-model="document.system" required>
                            <option value=""> -- CHOOSE --</option>
                            <option v-for="(value,key) in systems" :key="key" :value="value.code">{{ value.name }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label">Section <span class="text-red">*</span></label>
                        <select class="form-control" v-model="document.section" required>
                            <option value=""> -- CHOOSE --</option>
                            <option v-for="(value,key) in sections" :key="key" :value="value.code">{{ value.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label">Category <span class="text-red">*</span></label>
                        <select class="form-control" v-model="document.category" required>
                            <option value=""> -- CHOOSE --</option>
                            <option v-for="(value,key) in categories" :key="key" :value="value.code">{{ value.name
                                }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label">Comment</label>
                <textarea class="form-control" rows="5" placeholder="Comment here..." v-model="document.comment"></textarea>
            </div>

            <div class="form-group">
                <label class="control-label">Keywords e.g (document, important)</label>
                <input-tag v-model="document.keywords"></input-tag>
            </div>
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            value: {
                type: [Object, Array],
            }
        },
        data: function () {
            return {
                document:new Document,
                systems: [],
                categories: [],
                sections: [],
            }
        },
        watch: {
            document: {
                deep: true,
                handler: function (newval) {
                    this.$emit("input", newval);
                }
            }
        },
        methods: {
            load_list: function () {
                let parent = this;
                axios.post('/util/category_list').then(function (response) {
                    parent.categories = response.data;
                });
                axios.post('/util/system_list').then(function (response) {
                    parent.systems = response.data;
                });
            },
            system_changed: function () {
                let parent = this;
                parent.document.section='';
                axios.post('/util/section_list', {
                    system_code: parent.document.system
                }).then(function (response) {
                    parent.sections = response.data;
                });
            }
        },
        mounted() {
            this.load_list();
            if (this.value !== undefined && this.value !== null) {
                this.document = this.value;
            }

        }
    }

</script>
