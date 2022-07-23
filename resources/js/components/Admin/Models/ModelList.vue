<template>

    <div class="modele-list">

        <div class="d-flex justify-content-between mb-4" v-if="$route.name == 'admin-modele'">
            <h3 class="text-blue">Liste des modèles</h3>
            <router-link :to="{name:'addModel'}" class="btn btn-sm bg-gold" tag="a">Ajouter un modèle</router-link>
        </div>
        <vue-good-table
            :columns="columns"
            :rows="allCars"
            ref="my-table"
            styleClass="vgt-table striped condensed"
            :pagination-options="{
                    enabled: true,
                    mode:'pages',
                    perPage:10,
                    perPageDropdownEnabled: true,
                    rowsPerPageLabel: 'Pièces par page',
                    ofLabel:'sur',
                    allLabel:'Tous',
                    nextLabel:'Suivant',
                    prevLabel:'Précédent',
                }" v-if="$route.name=='admin-modele'">
            <template slot="table-row" slot-scope="props">
                <template v-if="props.column.field == 'compatible'">
                    <ul>
                        <li v-for="car in props.row.compatible_modeles">{{ car.brand.name }} {{ car.name }}({{ car.year_start }}-{{ car.year_end }})</li>
                    </ul>
                </template>
                <template v-else-if="props.column.field == 'action'">
                    <div class="d-flex">
                        <router-link tag="button" :to="{name: 'editModel', params: {id: props.row.id}}" class="btn btn-primary btn-xs px-3 mr-2">
                            Éditer
                        </router-link>
                        <button class="btn bg-dark-grey btn-xs pl-2" @click="selectedMerge = props.row" v-b-modal.merge>Fusionner</button>
                    </div>

                </template>
            </template>
            <div slot="emptystate">
                <div class="bg-white text-blue px-4 py-4 mt-3">
                    Aucun modèle ne correspond aux filtres
                </div>
            </div>
        </vue-good-table>
        <router-view v-else></router-view>
        <MergeModels v-if="selectedMerge" :selectedMerge="selectedMerge" @submit="selectedMerge = null"></MergeModels>
    </div>
</template>
<script>

import {mapGetters} from "vuex";
import MergeModels from "./MergeModels";

export default {
    name: 'model-list',
    components: {MergeModels},
    data() {
        return {
            selectedMerge: null,
            mergeDestinationId: null,
            columns: [
                {
                    label: 'ID',
                    field: 'id',
                    type: 'number',
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par ID', // placeholder for filter input
                        filterFn: this.filterInt
                    },
                    thClass: 'text-center',
                    tdClass: 'text-center id'
                }, {
                    label: 'Marque',
                    field: 'brand.name',
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par marque', // placeholder for filter input
                        filterFn: this.filterString
                    },
                }, {
                    label: 'Modèle',
                    field: 'name',
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par modèle', // placeholder for filter input
                        filterFn: this.filterString
                    },
                }, {
                    label: 'Années de production',
                    field: function (obj) {
                        return obj.year_start + '-' + obj.year_end;
                    },
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par date', // placeholder for filter input
                        filterFn: this.filterString
                    },
                }, {
                    label: 'Modèles compatibles',
                    field: 'compatible',
                    sortable: false
                }, {
                    label: 'Action',
                    field: 'action',
                    sortable: false
                },
            ]
        }
    },
    methods: {
        filterString(nom, filterString) {
            return nom.toLowerCase().includes(filterString.toLowerCase());
        },
        filterInt(nom, filterString) {
            return nom.toString().toLowerCase().includes(filterString.toString().toLowerCase());
        },
    },
    created() {
        this.$store.dispatch('cars/fetchAllCars');
    },
    computed: {
        ...mapGetters('cars', ['allCars']),
    }
}
</script>
