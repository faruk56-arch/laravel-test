<template>

    <div class="product-list">

        <div class="d-flex justify-content-between mb-4" v-if="$route.name == 'admin-part'">
            <h3 class="text-blue">Liste des pièces</h3>
            <router-link :to="{name:'addPart'}" class="btn btn-sm bg-gold" tag="a">Ajouter une pièce</router-link>
        </div>
        <vue-good-table
            :columns="columns"
            :rows="allParts"
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
                }" v-if="$route.name == 'admin-part'">
            <template slot="table-row" slot-scope="props">
                <template v-if="props.column.field == 'action'">
                    <div class="d-flex">
                        <router-link tag="button" class="btn btn-primary btn-xs px-3  mr-2"
                                     :to="{name: 'editPart', params: {id: props.row.id}}">
                            Éditer
                        </router-link>
                        <button class="btn bg-dark-grey btn-xs pl-2" @click="selectedMerge = props.row" v-b-modal.merge>Fusionner</button>
                    </div>
                </template>
            </template>
            <div slot="emptystate">
                <div class="bg-white text-blue px-4 py-4 mt-3">
                    Aucune pièce ne correspond aux filtres
                </div>
            </div>
        </vue-good-table>
        <router-view v-else></router-view>

        <MergeParts v-if="selectedMerge" @submit="selectedMerge = null" :selected-merge="selectedMerge"></MergeParts>
    </div>

</template>
<script>

import {mapGetters} from "vuex";
import MergeParts from "./MergeParts";

export default {
    name: 'part-list',
    components: {MergeParts},
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
                    label: 'Nom de la pièce',
                    field: this.nameField,
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par nom/synonyme', // placeholder for filter input
                        filterFn: this.filterByword
                    },
                }, {
                    label: 'Synonymes',
                    field: this.bywordsFn,
                }, {
                    label: 'Catégorie',
                    field: this.categoryField,
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Toutes', // placeholder for filter input
                        filterDropdownItems: ['Carrosserie', 'Intérieur', 'Moteur', 'Transmission', 'Liaison au sol', 'Documentation'],
                    },
                }, {
                    label: 'Action',
                    field: 'action',
                    sortable: false
                },
            ]
        }
    },
    methods: {
        merge() {
            axios.delete('part/' + this.selectedMerge.id + '/' + this.mergeDestinationId).then(res => {
                this.$store.commit('parts/REMOVE_PART', this.selectedMerge.id)
                this.selectedMerge = null;
                this.mergeDestinationId = null;
            })
        },
        bywordsFn(data) {
            return data.bywords ? data.bywords.join(', ') : ''
        },
        filterInt(nom, filterString) {
            return nom.toString().toLowerCase().includes(filterString.toString().toLowerCase());
        },
        filterString(nom, filterString) {
            return nom.toLowerCase().includes(filterString.toLowerCase());
        },
        filterByword(obj, filterString) {
            if (obj.bywords) return (obj.toLowerCase() + obj.bywords.join(', ').toLowerCase()).includes(filterString.toLowerCase());
            else return obj.toLowerCase().includes(filterString.toLowerCase());
            // TODO: Translations are not supported. Please check the code below ;)
            //if (obj.bywords) return (obj.translation.toLowerCase() + obj.bywords.join(', ').toLowerCase()).includes(filterString.toLowerCase());
            //else return obj.translation.toLowerCase().includes(filterString.toLowerCase());
        },
        categoryField(row) {
            return row.category.translation['name']['fr_FR']
        },
        nameField(row) {
            return this.translation('name', row)
        }
    },
    created() {
        this.$store.dispatch('parts/fetchAllParts')
    },
    computed: {
        ...mapGetters('parts', ['allParts'])
    }
}
</script>
