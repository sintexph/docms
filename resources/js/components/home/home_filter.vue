<template>

    <div class="box box-solid">
        <div class="box-header">
            <div class="form-group">
                <label class="control-label">Find Document</label>
                <div class="input-group">
                    <input ref="find" @keydown.enter="find()" data-step='1' data-intro='E type ang imong document na gustong pangitaon' type="text" v-model="filters.find" class="form-control"
                        placeholder="Find Document...">
                    <div class="input-group-btn">
                        <a :href="url" data-step='2' data-disable-interaction="1" data-intro='E click ni para pangitaon ni system ang imong gustong document' class="btn btn-default"><i class="fa fa-search" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>


        </div>

        <div class="box-header with-border">
            <h3 class="box-title">Filter the list here</h3>
        </div>

        <div class="box-body" href="#" data-step='3' data-intro='O pwede nimo sala-on ang naka lista an documento basi sa systema o sekson'>

            <div class="form-group">
                <label class="control-label">System <span class="text-red">*</span></label>
                <select class="form-control" @change="system_changed" v-model="filters.system" required>
                    <option value=""> -- CHOOSE --</option>
                    <option v-for="(value,key) in systems" :key="key" :value="value.code">{{ value.name }}</option>
                </select>
            </div>
            <div class="form-group">
                <label class="control-label">Section <span class="text-red">*</span></label>
                <select class="form-control" v-model="filters.section" required>
                    <option value=""> -- CHOOSE --</option>
                    <option v-for="(value,key) in sections" :key="key" :value="value.code">{{ value.name }}</option>
                </select>
            </div>


        </div>


        <div class="box-header">
            <h3 class="box-title"></h3>
            <div class="box-tools pull-right">
                <a data-step='4' data-intro='Pagkahoman nimo ug pili sa mga e pang sala nimo sa listahan, imo kining pindoton para makuha imo ang gusto nimong mga documento.' :href="url" title="Batch upload the items" class="btn btn-box-tool">
                    <i class="fa fa-filter" aria-hidden="true"></i>
                    Generate Filter
                </a>
            </div>
        </div>



    </div>


</template>
<script>
    export default {
        props: ['url_section', 'url_find', 'url_system'],
        data() {
            return {
                filters: {
                    system: '',
                    section: '',
                    find: '',
                },
                systems: [],
                sections: [],
            }
        },
        methods: {
            load_list: function () {
                let parent = this;

                axios.post('/util/system_list').then(function (response) {
                    parent.systems = response.data;
                });
            },
            system_changed: function () {
                let parent = this;
                parent.filters.section = '';
                axios.post('/util/section_list', {
                    system_code: parent.filters.system
                }).then(function (response) {
                    parent.sections = response.data;
                });
            },
            find() {
                window.location = this.url;
            }
        },
        computed: {
            url() {
                return '/home?find=' + this.filters.find + '&system=' + this.filters.system + '&sec=' + this.filters
                    .section;
            }
        },
        mounted() {
            this.load_list();

            if (this.url_system) {
                this.filters.system = this.url_system;
                this.system_changed();
            }

            if (this.url_section)
                this.filters.section = this.url_section;
            if (this.url_find) {
                this.filters.find = this.url_find;
                this.$refs.find.focus();
            }


        }
    }

</script>
