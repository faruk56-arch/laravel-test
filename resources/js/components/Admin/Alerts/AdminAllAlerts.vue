<template>
    <div>
        <div>
            <h3 class="text-blue mb-4">Liste des annonces simplifiées</h3>
        </div>
        <vue-good-table
            :columns="columns"
            :rows="allAlertsList"
            styleClass="vgt-table striped"
            :pagination-options="{
                    enabled: true,
                    mode:'pages',
                    perPage:10,
                    perPageDropdownEnabled: true,
                    rowsPerPageLabel: 'Annonces simplifiées par page',
                    ofLabel:'sur',
                    allLabel:'Tous',
                    nextLabel:'Suivant',
                    prevLabel:'Précédent',
                }">
        </vue-good-table>
    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";

export default {
    name: "AdminAllAlerts",
    data(){
        return {
            columns:[
                {
                    label:'ID',
                    field:'id',
                    type:'number',
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Filtrer par ID',
                    },
                    thClass: 'text-center',
                    tdClass: 'text-center id'
                },
                {
                    label:'Nom du vendeur',
                    field:'merchant.merchant_name',
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Filtrer par vendeur',
                    },
                },
                {
                    label:'Marque',
                    field:'brand.name',
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Filtrer par marque',
                    },
                },
                {
                    label:'Modèle',
                    field:'modele.name',
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Filtrer par modèle',
                    },
                },
                {
                    label:'Catégorie',
                    field: this.categoryField,
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Filtrer par catégorie',
                    },
                },
                {
                    label:'Pièce',
                    field: this.partField,
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Filtrer par pièce',
                    },
                },
            ]
        }
    },
    created(){
        this.$store.dispatch('admin/fetchAllAlerts')
    },
    methods: {
        categoryField(row) {
            if (row.category) {
                return this.translation('name', row.category)
            }
            return null
        },
        partField(row) {
            if (row.part) {
                return this.translation('name', row.part)
            }
            return null
        }
    },
    computed:{
        ...mapGetters('admin', ['allAlertsList', 'isAlertsLoaded']),
    },
}
</script>

<style scoped>

</style>
