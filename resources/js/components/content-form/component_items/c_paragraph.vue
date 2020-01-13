<template>
    <div>
        <div class="form-group">
            <textarea-autosize class="form-control" placeholder="Type something here..." ref="textarea"
                v-model="paragraph.value" :min-height="50" :max-height="350"></textarea-autosize>
            <div style="padding-top:5px">
                <a href="#" class="btn btn-xs btn-default text-primary" title="Make it bold" @click.prevent="bold"><i
                        class="fa fa-bold" aria-hidden="true"></i></a>
                <a href="#" class="btn btn-xs btn-default text-primary" title="Make it italic"
                    @click.prevent="italic"><i class="fa fa-italic" aria-hidden="true"></i></a>
                <label class="btn btn-default btn-xs">
                    <input type="checkbox" v-model="isChunk"> Chunk by break lines
                </label>
            </div>
        </div>
    </div>
</template>
<script>
    import VueTextareaAutosize from 'vue-textarea-autosize'
    export default {
        components: {
            VueTextareaAutosize
        },
        props: {
            rows: {
                type: [String, Number],
                default: function () {
                    return 2;
                }
            },
            value: {
                type: [Object, Array],
                default: function () {
                    return new Paragraph;
                }
            }
        },
        data: function () {
            return {
                isChunk: false,
                paragraph: new Paragraph,
            }
        },
        methods: {
            replace_highlight: function (toReplace, element) {

                element = element.$el;
                // obtain the index of the first selected character
                var start = element.selectionStart;
                // obtain the index of the last selected character
                var finish = element.selectionEnd;

                //obtain all Text
                var allText = element.value;

                // obtain the selected text
                var sel = allText.substring(start, finish);
                //append te text;
                var newText = allText.substring(0, start) + "<" + toReplace + ">" + sel + "</" + toReplace + ">" +
                    allText.substring(
                        finish, allText.length);

                return newText;
            },
            bold: function () {
                this.paragraph.value = this.replace_highlight("bold", this.$refs.textarea);
            },
            italic: function () {
                this.paragraph.value = this.replace_highlight("italic", this.$refs.textarea);
            },
            reset: function () {
                this.paragraph = new Paragraph;
                this.$emit('input', this.paragraph);
                this.isChunk = false;
            },


            chunk: function () {
                if (this.isChunk === true) {
                    var par_array = new Array;
                    var split_value = this.paragraph.value.split('\n');


                    split_value.forEach(function (val) {
                        par_array.push(new Paragraph(val));
                    });
                    return par_array;
                }
                return new Array;

            },


        },
        watch: {
            paragraph: {
                deep: true,
                handler: function (val) {

                    this.$emit('input', val);

                }
            },
            value: {
                deep: true,
                handler: function (val) {
                    this.paragraph = val;


                }
            }
        },
    }

</script>
