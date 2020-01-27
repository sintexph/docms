<template>
    <div class="box box-solid">
        <div class="box-header">
            <h3 class="box-title">Edit access of the document</h3>
        </div>
        <form @submit.prevent="submit_access">
            <div class="box-body">

                <access-form v-model="doc_access"></access-form>

           

            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-sm btn-success">Update Access</button>
            </div>
        </form>
    </div>




</template>
<script>
    export default {
        props: {
            document_id: {
                required: true,
            },
            current_access: {
                required: true,
                default: function () {
                    return '';
                }
            },
            current_accessors: {
                required: true,
                type: [Array, Object],
                default: function () {
                    return [];
                }
            },
            
        },
        data: function () {
            return {
                doc_access: {
                    access: '',
                    accessors: [],
                },
                users_data: [],
                access_data: [{
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
                submitted: false, 
            }
        },
        methods: {
            load_list: function (val) {
                let parent = this;

                axios.post('/util/users').then((response) => {
                    response.data.forEach(function (data) {
                        parent.users_data.push({
                            text: data.name,
                            id: data.id
                        })
                    });

                });
            },

            submit_access: function () {
                let parent = this;
                if (parent.submitted === false) {
                    parent.submitted = true;
                    axios.patch('/manage/documents/update_access/' + parent.document_id, { 
                        access: parent.doc_access.access,
                        accessors: parent.doc_access.accessors
                    }).then(function (response) {


                        parent.alert_success(response);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }).catch(function (error) {
                        parent.submitted = false;
                        parent.alert_failed(error);
                    });

                }

            }
        },
        mounted: function () {
            var vm = this;
            vm.load_list();

            vm.doc_access.access = vm.current_access;
            vm.doc_access.accessors = vm.current_accessors;

            /*Push data to accessors*/
            if (vm.current_accessors.length !== 0) {
                vm.current_accessors.forEach(function (accessor) {
                    vm.doc_access.accessors.push(accessor.user.id);
                });
            }


       

        }
    }

</script>
