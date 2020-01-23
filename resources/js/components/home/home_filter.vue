<template>

    <div class="box box-solid">
        <div class="box-header">
            <div class="form-group">
                <label class="control-label">Find Document</label>
                <div class="input-group">
                    <input ref="find" @keydown.enter="find()" data-step='1'
                        data-intro='E type ang imong document na gustong pangitaon' type="text" v-model="filters.find"
                        class="form-control" placeholder="Find Document...">
                    <div class="input-group-btn">
                        <a :href="url" data-step='2' data-disable-interaction="1"
                            data-intro='E click ni para pangitaon ni system ang imong gustong document'
                            class="btn btn-default"><i class="fa fa-search" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
            <ul class="list-unstyled" v-if="filters.status || filters.category || filters.section || filters.system">

                <li><strong>Filters</strong>
                    <ul>
                        <li v-if="filters.status">Status <a href="#" @click.prevent="true">{{ filters.status }}</a></li>
                        <li v-if="filters.category">Category <a href="#" @click.prevent="true">{{ category_name }}</a></li>
                        <li v-if="filters.system">System <a href="#" @click.prevent="true">{{ system_name }}</a></li>
                        <li v-if="filters.section">Section <a href="#" @click.prevent="true">{{ section_name }}</a></li>
                    </ul>
                </li>

                <li><a href="#" @click.prevent="clear_filter"><i class="fa fa-times" aria-hidden="true"></i> Clear</a>
                </li>
            </ul>

        </div>




        <div class="box-header with-border">
            <h3 class="box-title">Filter the list here</h3>
        </div>

        <div class="box-body" href="#" data-step='3'
            data-intro='O pwede nimo sala-on ang naka lista an documento basi sa systema o sekson'>
            <div class="form-group">
                <label class="control-label">Category</label>
                <select class="form-control" v-model="filters.category" required>
                    <option value=""> -- CHOOSE --</option>
                    <option v-for="(value,key) in categories" :key="key" :value="value.code">{{ value.name
                                }}</option>
                </select>
            </div>

            <div class="form-group">
                <label class="control-label">System</label>
                <select class="form-control" @change="system_changed" v-model="filters.system" required>
                    <option value=""> -- CHOOSE --</option>
                    <option v-for="(value,key) in systems" :key="key" :value="value.code">{{ value.name }}</option>
                </select>
            </div>
            <div class="form-group">
                <label class="control-label">Section</label>
                <select class="form-control" v-model="filters.section" required>
                    <option value=""> -- CHOOSE --</option>
                    <option v-for="(value,key) in sections" :key="key" :value="value.code">{{ value.name }}</option>
                </select>
            </div>
            <div class="form-group">
                <label class="control-label">Status</label>
                <select class="form-control" v-model="filters.status" required>
                    <option value=""> -- CHOOSE --</option>
                    <option value="active">Active</option>
                    <option value="obsolete">Obsolete</option>
                </select>
            </div>



            <div class=" pull-right">
                <a data-step='4'
                    data-intro='Pagkahoman nimo ug pili sa mga e pang sala nimo sa listahan, imo kining pindoton para makuha imo ang gusto nimong mga documento.'
                    :href="url" title="Batch upload the items" class="btn btn-sm btn-warning">
                    <i class="fa fa-filter" aria-hidden="true"></i>
                    Apply Filter
                </a>
            </div>

        </div>




    </div>


</template>
<script>
    export default {
        props: ['url_section', 'url_find', 'url_system', 'url_status', 'url_category'],
        data() {
            return {
                filters: {
                    system: '',
                    section: '',
                    category: '',
                    status: '',
                    find: '',
                },
                systems: [],
                sections: [],
                categories: [],
            }
        },
        methods: {
            clear_filter() {
                this.filters.system = '';
                this.filters.section = '';
                this.filters.category = '';
                this.filters.status = '';
                this.find();
            },
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
                return '/home?find=' + this.filters.find + '&cat=' + this.filters.category + '&system=' + this.filters
                    .system + '&sec=' + this.filters
                    .section + '&status=' + this.filters.status;
            },
            category_name() {
                let vm = this;
                let cat = vm.categories.find(category => {
                    return category.code === vm.filters.category;
                });


                if (cat)
                    return cat.name;
                else
                    return '';
            },
            section_name() {
                let vm = this;
                let sec = vm.sections.find(section => {
                    return section.code === vm.filters.section;
                });


                if (sec)
                    return sec.name;
                else
                    return '';
            },
            system_name() {
                let vm = this;
                let sys = vm.systems.find(system => {
                    return system.code === vm.filters.system;
                });


                if (sys)
                    return sys.name;
                else
                    return '';
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

            if (this.url_status)
                this.filters.status = this.url_status;

            if (this.url_category)
                this.filters.category = this.url_category;



        }
    }

</script>
