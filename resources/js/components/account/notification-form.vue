<template>
    <div>

        <p>
            <icheck-checkbox v-model="account.notify_changes"></icheck-checkbox> Notify when there are changes of
            the document.
        </p>
        <p>
            <icheck-checkbox v-model="account.notify_followups"></icheck-checkbox> Send followups if there are documents that need for approval or review
        </p>
        <p>
            <icheck-checkbox v-model="account.notify_comments"></icheck-checkbox> Notify there are comments in
            the documents.
        </p>

        <p>
            <icheck-checkbox v-model="account.notify_reviewed"></icheck-checkbox> Notify when the documents were
            reviewed.
        </p>
        <p>
            <icheck-checkbox v-model="account.notify_approved"></icheck-checkbox> Notify when the documents were
            approved.
        </p>

        <p>
            <icheck-checkbox v-model="account.notify_to_review"></icheck-checkbox> Notify when there are documents
            that i needs review.
        </p>
        <p>
            <icheck-checkbox v-model="account.notify_to_approve"></icheck-checkbox> Notify when there are documents
            that needs to approve.
        </p>

    </div>
</template>

<script>
    export default {

        props: {
            value: {
                required: true,
            }
        },
        data() {
            return {
                account: new User,
            }
        },

        watch: {
            value: {
                deep: true,
                handler: function (val) {
                    this.account = val;
                }
            },
            account: {
                deep: true,
                handler: function (val) {
                    this.$emit('input', val);
                }
            }
        },

        mounted() {
            if (this.value)
                this.account = this.value;

        }
    }

</script>
