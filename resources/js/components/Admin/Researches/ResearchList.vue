<template>
    <div class="reasearch-list">
        <div v-if="isResearchesLoaded">
             <router-view></router-view>
        </div>

        <div v-else><span class="loader"></span></div>

    </div>
</template>

<script>
    import {mapActions, mapGetters} from "vuex";
    import mxResearches from "../../../mixins/Researches";

    export default {
        name: "ResearchList",
        data: function () {
            return {
                modalState:0,
                nameFR:'',
                nameDE:'',
                nameEN:'',
                carSelected:null,
                categorySelected:'',
                partSelected:0,
            }
        },
        mixins:[mxResearches],
        created(){
            this.$store.dispatch('admin/fetchAllResearches');
        },
        computed: {
            ...mapGetters('admin', ['allResearches', 'isResearchesLoaded']),
        },

    }
</script>

<style scoped>

</style>
