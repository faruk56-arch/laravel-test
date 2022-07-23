<template>

    <div >
        <h3>Liste des catégories</h3>
        <router-link :to="{name:'addCategory'}" class="btn btn-primary" tag="a">Ajouter une catégorie</router-link>
        <ul>
            <li v-for="category in allCategories">{{ translation('name', category) }}</li>
        </ul>
    </div>
</template>
<script>

    import {mapGetters} from "vuex";

    export default {
        name: 'category-list',
        methods: {

        },
        created() {
            this.$store.dispatch('categories/fetchAllCategories');
        },
        computed:{
            ...mapGetters('categories', ['allCategories']),
        }
    }
</script>
