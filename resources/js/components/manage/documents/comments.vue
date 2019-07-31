<template>
    <div class="clearfix">
        <ul class="commentList">
            <li v-for="(value,key) in comment_list" :key="key">
                <div class="commenterImage">
                    <p :data-letters="value.creator_initials"></p>
                </div>
                <div class="commentText">
                    <p class="sub-text user"><small v-text="value.creator.name"></small></p>
                    <p v-html="value.comment_html "></p> <span class="date sub-text">on {{ value.created_at }}</span>
                </div>
            </li>
        </ul>
        <form @submit.prevent="post_comment">
            <textarea class="form-control" rows="3" placeholder="Enter your comment here ..." v-model="comment"
                required></textarea>
            <br>
            <button type="submit" class="btn btn-primary pull-right">Post Comment</button>
        </form>
    </div>
</template>
<script>
    export default {
        props: {
            version_id: {
                type: [String, Number],
            }
        },
        data: function () {
            return {
                submitted: false,
                comment_list: [],
                comment: '',
            }
        },
        watch: {
            version_id: function () {
                this.fetch();
            }
        },
        methods: {
            fetch: function () {
                var par = this;
                if (par.version_id !== '') {
                    axios.post('/comments/fetch/' + par.version_id).then(function (response) {
                        par.comment_list = response.data;
                    });
                }
            },
            post_comment: function () {
                var par = this;
                if (par.submitted === false) {
                    par.submitted = true;
                    par.show_wait("Please wait while the system is posting your comment...");
                    axios.put('/comments/save/' + par.version_id, {
                        comment: par.comment,
                    }).then(function (response) {
                        par.submitted = false;
                        par.hide_wait();
                        par.fetch();
                        par.comment = '';

                    }).catch(function (error) {
                        par.submitted = false;
                        par.alert_failed(error);
                        par.hide_wait();
                    });
                }

            }
        },
        mounted() {
            if (this.version_id !== '')
                this.fetch();
        }
    }

</script>



<style>
    .commentList {
        padding: 0;
        list-style: none;
        max-height: 480px;
        overflow: auto;
    }

    .commentList li {
        margin: 0;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f1f1f1;
    }

    .commentList li:first-child {
        margin-top: 10px;
    }

    .commentList li:last-child {
        border-bottom: none;
    }

    .commentList li>div {
        display: table-cell;
    }

    .commenterImage {
        width: 30px;
        margin-right: 20px;
        height: 100%;
        float: left;
    }



    .commentText p {
        margin: 0;
    }

    .sub-text {
        color: #aaa;
        font-family: verdana;
        font-size: 11px;
    }




    .actionBox {
        border-top: 1px dotted #bbb;
        padding: 10px;
    }

    [data-letters]:before {
        content: attr(data-letters);
        display: inline-block;
        font-size: 1em;
        width: 2.5em;
        height: 2.5em;
        line-height: 2.5em;
        text-align: center;
        border-radius: 50%;
        background: rgb(121, 121, 121);
        vertical-align: middle;
        margin-right: 1em;
        color: white;

    }

</style>
