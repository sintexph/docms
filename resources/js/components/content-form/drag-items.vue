<template>
    <draggable v-model="myArray" group="people" @start="drag=true" @end="drag=false">
        <div v-for="element in myArray" :key="element.id">{{element.name}}</div>
    </draggable>
</template>

<script>
    import draggable from 'vuedraggable'

    export default {
        components: {
            draggable,
        },
        data() {
            return {
                myArray: [
                    {
                        name: "vue.draggable",
                        order: 1,
                        fixed: false
                    },
                    {
                        name: "draggable",
                        order: 2,
                        fixed: false
                    },
                    {
                        name: "for",
                        order: 4,
                        fixed: false
                    },
                ]
            }
        }
    }

</script>
