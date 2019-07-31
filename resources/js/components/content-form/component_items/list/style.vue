<template>
    <div :class="class_name">

        <div class="list-style">

            <input type="radio" v-model="list_style" :name="'list_style_'+_uid" :id="'none_'+_uid">
            <label :for="'none_'+_uid">None</label>

            <input type="radio" v-model="list_style" :name="'list_style_'+_uid" :id="'_number'+_uid" value="number">
            <label :for="'_number'+_uid">Number</label>

            <input type="radio" v-model="list_style" :name="'list_style_'+_uid" :id="'lower_alpha_'+_uid"
                value="lower_alpha">
            <label :for="'lower_alpha_'+_uid">Lower Alpha</label>

            <input type="radio" v-model="list_style" :name="'list_style_'+_uid" :id="'upper_alpha_'+_uid"
                value="upper_alpha">
            <label :for="'upper_alpha_'+_uid">Upper Alpha</label>

            <input type="radio" v-model="list_style" :name="'list_style_'+_uid" :id="'circle_'+_uid" value="circle">
            <label :for="'circle_'+_uid">Circle</label>

            <input type="radio" v-model="list_style" :name="'list_style_'+_uid" :id="'square_'+_uid" value="square">
            <label :for="'square_'+_uid">Square</label>

            <input type="radio" v-model="list_style" :name="'list_style_'+_uid" :id="'upper_roman_'+_uid"
                value="upper_roman">
            <label :for="'upper_roman_'+_uid">Upper Roman</label>

            <input type="radio" v-model="list_style" :name="'list_style_'+_uid" :id="'lower_roman_'+_uid"
                value="lower_roman">
            <label :for="'lower_roman_'+_uid">Lower Roman</label>
        </div>
        <slot></slot>
    </div>
</template>
<script>
    export default {
        props: {
            class_name: {
                type: String,
                default: function () {
                    return '';
                }
            },
            value: {
                type: String,
                default: function () {
                    return null;
                }
            }
        },
        data: function () {
            return {
                list_style: 'number',
            }
        },
        mounted() {
            if (this.value === null || this.value === '')
                this.list_style = null;
            else
                this.list_style = this.value;
        },
        watch: {
            list_style: function (val) {
                this.$emit('input', val);
            },
            value: function (val) {
                this.list_style = val;
            }
        }
    }

</script>


<style>
    .list-style>input[type=radio] {
        position: absolute;
        visibility: hidden;
        display: none;
    }

    .list-style>label {
        display: inline-block;
        cursor: pointer;
        padding: 0px 5px 0px 5px;
        font-weight: 500;
        color: #202020;
    }

    .list-style>input[type=radio]:checked+label {
        border-bottom: 2px solid #e6b4b4c5;
    }



    .list-style {
        display: inline-block;
        overflow: hidden;
    }

</style>
