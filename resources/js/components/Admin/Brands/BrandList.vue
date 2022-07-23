<template>

    <div class="brand-list">

        <div class="d-flex justify-content-between mb-4" v-if="$route.name == 'admin-brand'">
            <h3 class="text-blue">Liste des marques</h3>
            <router-link :to="{name:'addBrand'}" class="btn btn-sm bg-gold" tag="a">Ajouter une marque</router-link>
        </div>
        <vue-good-table
            :columns="columns"
            :rows="allBrands"
            ref="my-table"
            styleClass="vgt-table striped condensed"
            :pagination-options="{
                    enabled: true,
                    mode:'pages',
                    perPage:10,
                    perPageDropdownEnabled: true,
                    rowsPerPageLabel: 'Recherches par page',
                    ofLabel:'sur',
                    allLabel:'Tous',
                    nextLabel:'Suivant',
                    prevLabel:'Précédent',
                }" v-if="$route.name==='admin-brand'">
            <template slot="table-row" slot-scope="props">
                <template v-if="props.column.field == 'action'">
                    <router-link tag="button" class="btn btn-primary btn-xs px-3 mr-2"
                                 :to="{name: 'editBrand', params: {id: props.row.id}}">
                        Éditer
                    </router-link>
                    <button class="btn bg-dark-grey btn-xs" @click="selectedMerge = props.row" v-b-modal.merge>Fusionner</button>
                </template>

            </template>
            <div slot="emptystate">
                <div class="bg-white text-blue px-4 py-4 mt-3">
                    Aucune marque ne correspond aux filtres
                </div>
            </div>
        </vue-good-table>
        <router-view v-else></router-view>
        <MergeBrands v-if="selectedMerge" :selected-merge="selectedMerge" @submit="selectedMerge = null"></MergeBrands>
    </div>
</template>
<script>

import {mapGetters} from "vuex";
import MergeBrands from "./MergeBrands";

export default {
    name: 'brand-list',
    components: {MergeBrands},
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
                },
                {
                    label: 'Nom',
                    field: 'name',
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Filtrer par nom',
                        filterFn: this.filterString
                    },
                },
                {
                    label: 'Action',
                    field: 'action',
                    sortable: false,
                }
            ]
        }
    },
    methods: {
        merge() {
            axios.delete('brand/' + this.selectedMerge.id + '/' + this.mergeDestinationId).then(res => {
                this.$store.commit('brands/REMOVE_BRAND', this.selectedMerge.id)
                this.selectedMerge = null;
                this.mergeDestinationId = null;
            })
        },
        filterString(nom, filterString) {
            return nom.toLowerCase().includes(filterString.toLowerCase());
        },
        filterInt(nom, filterString) {
            return nom.toString().toLowerCase().includes(filterString.toString().toLowerCase());
        },
    },
    created() {
        this.$store.dispatch('brands/fetchAllBrands');

    },
    computed: {
        ...mapGetters('brands', ['allBrands']),
    }
}
</script>
