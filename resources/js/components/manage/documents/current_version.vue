<template>
    <div v-if="document!==null && selected_version!==null && versions!==null">
        <div class="box box-solid">
            <div class=" box-header with-border">
                <h3 class="box-title"><i class="fa fa-info-circle" aria-hidden="true"></i> Document Version Information
                </h3>
                <div class="box-tools pull-right">
                    <label>Version:&nbsp;&nbsp;</label>
                    <select v-model="selected_version_id" @change="selected_version_id_change">
                        <option v-for="(value,key) in versions" :value="value.id" :key="key+'-version'">
                            {{ value.version }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered">
                        <tbody>
                            <tr>
                                <th>Version</th>
                                <td>{{ selected_version.version }}</td>
                                <th>Description</th>
                                <td v-html="selected_version.description_html"></td>

                            </tr>
                            <tr>
                                <th>Effective Date</th>
                                <td>
                                    <span v-if="selected_version.effective_date!==null">
                                        {{selected_version.effective_date_formatted}}
                                    </span>
                                    <span v-else>---</span>
                                </td>

                                <th>Expiry Date</th>
                                <td>
                                    <span v-if="selected_version.expiry_date!==null">
                                        {{selected_version.expiry_date_formatted}}
                                    </span>
                                    <span v-else>---</span>
                                </td>
                            </tr>
                            <tr>
                                <th :rowspan="selected_version.reviewers.length+1">
                                    <span>Reviewers</span><br>


                                </th>
                            </tr>
                            <tr v-for="(value,key) in selected_version.reviewers" :key="key+'-reviewer'">
                                <td>
                                    <i v-if="value.reviewed===true" class="fa fa-check text-green"
                                        aria-hidden="true"></i>
                                    <u>{{ value.user.name }}</u>
                                </td>
                                <td colspan="2">
                                    {{ value.reviewed_at }}
                                </td>
                            </tr>
                            <tr v-if="selected_version.for_review===true && selected_version.reviewed===false">
                                <td colspan="4">
                                    <small>The document version is already
                                        sent out <strong>for review</strong> and if you wish to
                                        cancel it, you may access the <strong class="text-red">cancel</strong> button
                                        from the action area.</small>
                                </td>
                            </tr>
                            <tr>
                                <th :rowspan="selected_version.approvers.length+1">Approvers</th>
                            </tr>
                            <tr v-for="(value,key) in selected_version.approvers" :key="key+'-approver'">
                                <td>
                                    <i v-if="value.approved===true" class="fa fa-check text-green"
                                        aria-hidden="true"></i>
                                    <u>{{ value.user.name }}</u>
                                </td>
                                <td colspan="2">
                                    {{ value.approved_at }}
                                </td>
                            </tr>
                            <tr v-if="selected_version.for_approval===true && selected_version.approved===false">
                                <td colspan="4">
                                    <small>The document version is already
                                        sent out <strong>for approval</strong> and if you wish to
                                        cancel it, you may access the <strong class="text-red">cancel</strong> button
                                        from the action area.</small>
                                </td>
                            </tr>
                            <tr>
                                <th>Released</th>
                                <td colspan="3">
                                    <span v-if="selected_version.released===true">
                                        <i class="fa fa-check text-green" aria-hidden="true"></i>
                                        {{ selected_version.released_date }}
                                    </span>
                                    <span v-else>----</span>
                                </td>
                            </tr>
 
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box-body">
                <iframe v-if="selected_version!==null" :src="'/content/view/version/'+selected_version.id" width="100%"
                    height="600"></iframe>
            </div>
        </div>
 
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Comment Box</h3>
            </div>
            <div class="box-body">
                <comments :version_id="selected_version.id"></comments>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: {
            document: {
                type: [Array, Object],
                required: true,
                default: function () {
                    return null;
                }
            },
            selected_version: {
                type: [Array, Object],
                required: true,
                default: function () {
                    return null;
                }
            },
            versions: {
                type: [Array, Object],
                required: true,
                default: function () {
                    return null;
                }
            }

        },
        data: function () {
            return {
                selected_version_id: '',
                submitted: false,
            }
        },


        methods: {
            selected_version_id_change: function () {
                window.location.href = '/manage/documents/view/' + this.document.id + '/?ver=' + this
                    .selected_version_id;
            },

        },
        mounted() {
            this.selected_version_id = this.selected_version.id;


        }
    }

</script>
