<template>
    <div>
        <div class="box box-solid">
            <div class=" box-header with-border">
                <h3 class="box-title">References</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Reference</th>

                            <th>Added</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr v-for="(reference,key) in references" :key="key">

                            <td v-if="validURL(reference.reference)===true" class="fit">
                                <a target="_blank" :href="reference.reference" v-text="reference.reference"></a>
                            </td>
                            <td v-else class="fit" v-text="reference.reference"></td>

                            <td>
                                <span>{{ reference.created_by }}</span><br>
                                <small>{{ reference.created_at }}</small>
                            </td>
                            <td>
                                <a href="#" @click.prevent="remove_reference(reference.id)"><i
                                        class="fa fa-trash-o text-red" aria-hidden="true"></i> Remove</a>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <div class="box box-solid" v-if="can_initiate===true">
            <div class=" box-header with-border">
                <h3 class="box-title">Add reference to the current document</h3>
            </div>
            <form method="post" @submit.prevent="add_reference">
                <div class="box-body">
                    <div class="form-group">
                        <label for="">Document</label>
                        <input type="text" v-model="reference" placeholder="Text | Links"
                            class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-default pull-right"> <i class="fa fa-save"></i> Save
                        Reference</button>
                </div>
            </form>
        </div>

    </div>
</template>

<script>
    export default {
        props: {
            can_initiate: {
                type: [Boolean],
                default: function () {
                    return false;
                }
            },
            references: {
                type: [Object, Array],
                default: function () {
                    return [];
                }
            },
            document: {
                type: [Object, Array],
                required: true,
            }
        },
        data: function () {
            return {
                submitted: false,
                reference: '',
                is_public: false,
            }
        },
        methods: {

            validURL(str) {
                var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
                    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
                    '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
                    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
                    '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
                    '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator
                return !!pattern.test(str);
            },

            add_reference: function () {


                var par = this;
                if (par.submitted === false && par.can_initiate === true) {
                    par.submitted = true;
                    axios.put('/manage/documents/add_reference/' + par.document.id, {
                        reference: par.reference,
                    }).then(function (response) {
                        par.alert_success(response);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }).catch(function (error) {
                        par.submitted = false;
                        par.alert_failed(error);
                    });
                }


            },
            remove_reference: function (id) {

                var par = this;
                if (par.submitted === false && par.can_initiate === true) {
                    if (confirm('Do you want to remove the selected reference?') === true) {
                        par.submitted = true;
                        axios.put('/manage/documents/remove_reference/' + id).then(function (response) {
                            par.alert_success(response);
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }).catch(function (error) {
                            par.submitted = false;
                            par.alert_failed(error);
                        });
                    }
                }


            }
        }
    }

</script>
