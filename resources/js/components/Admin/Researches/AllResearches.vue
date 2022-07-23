<template>
    <div>

        <div class="mb-4">
            <h3 class="text-blue">Toutes les recherches</h3>
        </div>
        <vue-good-table
            :columns="columns"
            :rows="allResearches.filter(p=> p.user && p.user.firstname)"
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
                }">
            <template slot="table-row" slot-scope="props">
                <template v-if="props.column.label == 'Statut'">
                    <template v-if="!props.row.deleted_at">
                        <span v-if="!props.row.status"
                              class="badge badge-pill badge-warning w-100">Attente validation</span>
                        <span v-else-if="props.row.status == 'in_progress'"
                              class="badge badge-pill badge-success w-100">En cours</span>
                        <span v-else-if="props.row.status == 'archived'" class="badge badge-pill badge-secondary w-100">Archivée</span>
                        <span v-else-if="props.row.status == 'finished'" class="badge badge-pill badge-secondary w-100">Terminée</span>
                    </template>
                    <span v-else
                          class="badge badge-pill badge-danger w-100">Supprimée</span>
                </template>
                <template v-else-if="props.column.field == 'action'">
                    <router-link tag="button" :to="{name:'adminResearchDetails',params:{id:props.row.id}}"
                                 class="btn btn-xs px-4 btn-center btn-primary">Détails
                    </router-link>

                </template>
            </template>
            <div slot="emptystate">
                <div class="bg-white text-blue px-4 py-4 mt-3">
                    Aucune recherche ne correspond aux filtres
                </div>
            </div>
        </vue-good-table>
        <p class="text-blue" v-if="allResearches.filter(r=> r.user && r.user.firstname).length == 0">Aucune recherche
            !</p>
    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";

export default {
    name: "AllResearches",
    data() {
        return {
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
                    tdClass: 'text-center',
                },
                {
                    label: 'Véhicule',
                    field: this.vehicleFn,
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par modèle', // placeholder for filter input
                        filterFn: this.filterString
                    },
                },
                {
                    label: 'Pièce recherchée',
                    field: this.partFn,
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par pièce', // placeholder for filter input
                        filterFn: this.filterString
                    },
                },
                {
                    label: 'Nom de l\'utilisateur',
                    field: this.nameFn,
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par utilisateur', // placeholder for filter input
                        filterFn: this.filterString
                    },
                },
                {
                    label: 'Date de la recherche',
                    field: 'created_at',
                    type: 'date',
                    dateInputFormat: 'd MMM yyyy',
                    dateOutputFormat: 'd MMM yyyy',
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par date', // placeholder for filter input
                    },
                    thClass: 'text-left',
                    tdClass: 'text-left',
                },
                {
                    label: 'Date de suppression',
                    field: 'deleted_at',
                    type: 'date',
                    dateInputFormat: 'yyyy-MM-dd HH:mm:ss',
                    dateOutputFormat: 'd MMM yyyy',
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par date', // placeholder for filter input
                    },
                    thClass: 'text-left',
                    tdClass: 'text-left',
                },
                {
                    label: 'Statut',
                    field: function (obj) {
                        return obj;
                    },
                    sortFn: (x, y, col, rowX, rowY) => {
                        if (rowX.deleted_at && !rowY.deleted_at) {
                            return 1;
                        } else if (!rowX.deleted_at && !rowY.deleted_at) {
                            if (rowX.status == rowY.status) {
                                return 0;
                            } else {
                                return 1
                            }
                        } else {
                            return -1;
                        }
                    },
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Tous', // placeholder for filter input
                        filterFn: function (data, filterString) {
                            if (filterString === 'deleted') {
                                return data.deleted_at !== null;
                            } else {
                                return data.status === filterString && !data.deleted_at;
                            }
                        },
                        filterDropdownItems: [
                            {value: 'in_progress', text: 'En cours'},
                            {value: 'archived', text: 'Archivé'},
                            {value: 'deleted', text: 'Supprimée'},
                        ]
                    }
                },
                {
                    label: 'Action',
                    field: 'action',
                    sortable: false
                },
            ]
        }
    },
    methods: {
        ...
            mapActions('admin', ['changeResearchStatus']),
        vehicleFn(research) {
            if (!research.modele) {
                return '-';
            }
            return research.modele.year ? research.modele.modele.brand.name + ' ' + research.modele.modele.name + ' (' + research.modele.year + ')' :
                research.modele.modele.brand.name + ' ' + research.modele.modele.name + ' (' + research.modele.modele.year_start + '-' + research.modele.modele.year_end + ')'
        }
        ,
        nameFn(research) {
            return research.user ? research.user.firstname + ' ' + research.user.lastname : ''
        }
        ,
        partFn(research) {
            return research.part && this.translation('name', research.part) || 'Pièce suggérée'
        }
        ,
        filterString(nom, filterString) {
            return nom.toLowerCase().includes(filterString.toLowerCase());
        }
        ,
        filterInt(nom, filterString) {
            return nom.toString().toLowerCase().includes(filterString.toString().toLowerCase());
        }
        ,

    }
    ,

    computed: {
        ...
            mapGetters('admin', ['allResearches', 'isResearchesLoaded']),
    }
    ,
}
</script>

<style scoped>

</style>
