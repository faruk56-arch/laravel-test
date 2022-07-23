<template>
    <div class="researches-waiting">

        <div class="mb-4">
            <h3 class="text-blue">Recherches à valider</h3>
        </div>

        <div>

        </div>
        <vue-good-table
            :columns="columns"
            :rows="allResearches.filter(p=>!p.status&& p.user && p.user.firstname && !p.deleted_at)"
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
                <template v-if="props.column.field == 'status'">
                     <span v-if="!props.row.deleted_at"
                         class="badge badge-pill badge-warning">{{ props.row.status ? props.row.status : 'Attente validation' }}</span>
                    <span v-else
                         class="badge badge-pill badge-danger">Supprimée</span>
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
        <div class="bg-white text-blue px-4 py-4 mt-3"
             v-if="allResearches.filter(p=>!p.status&& p.user && p.user.firstname).length == 0">
            Aucune recherche à valider pour l'instant !
        </div>
<!--        <table class="table table-striped" v-else>-->
<!--            <thead>-->
<!--            <tr>-->
<!--                <th scope="col">ID</th>-->
<!--                <th scope="col">Véhicule</th>-->
<!--                <th scope="col">Pièce recherchée</th>-->
<!--                <th scope="col">Nom de l'utilisateur</th>-->
<!--                <th scope="col">Date de la recherche</th>-->
<!--                <th scope="col">Statut</th>-->
<!--                <th scope="col"></th>-->
<!--            </tr>-->
<!--            </thead>-->
<!--            <tbody>-->
<!--            <tr v-for="research in allResearches" v-if="!research.status&&research.user.firstname">-->
<!--                <td scope="row">{{ research.id }}</td>-->
<!--                <td scope="row">-->
<!--                    <img v-if="research.modele.modele.img" :src="'/images/Cars/'+research.modele.modele.img+'.png'"-->
<!--                         class="car-img pr-2" @error="research.modele.modele.img=null">-->
<!--                    <img v-else :src="'/images/Cars/emptycar.png'" class="car-img pr-2">-->
<!--                    {{-->
<!--                        research.modele.year ? research.modele.modele.brand.name + ' ' + research.modele.modele.name + ' (' + research.modele.year + ')' :-->
<!--                            research.modele.modele.brand.name + ' ' + research.modele.modele.name + ' (' + research.modele.modele.year_start + '-' + research.modele.modele.year_end + ')'-->
<!--                    }}-->
<!--                </td>-->
<!--                <td scope="row">{{ research.part && research.part.translation || 'Pièce suggérée' }}</td>-->
<!--                <td scope="row">{{ research.user.firstname + ' ' + research.user.lastname }}</td>-->
<!--                <td scope="row">{{ research.created_at }}</td>-->
<!--                <td scope="row">-->
<!--                   c-->
<!--                </td>-->
<!--                <td scope="row">-->
<!--                    <router-link tag="button" :to="{name:'adminResearchDetails',params:{id:research.id}}"-->
<!--                                 class="btn btn-xs px-4 btn-center btn-primary">Détails-->
<!--                    </router-link>-->
<!--                </td>-->
<!--            </tr>-->
<!--            </tbody>-->
<!--        </table>-->

    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";

export default {
    name: "ResearchesWaiting",
    data() {
        return {
            columns: [
                {
                    label:'ID',
                    field: 'id',
                    type:'number',
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par ID', // placeholder for filter input
                        filterFn: this.filterInt
                    },
                    thClass: 'text-center',
                    tdClass: 'text-center',
                },
                {
                    label:'Véhicule',
                    field: this.vehicleFn,
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par modèle', // placeholder for filter input
                        filterFn: this.filterString
                    },
                },
                {
                    label:'Pièce recherchée',
                    field: this.partFn,
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par pièce', // placeholder for filter input
                        filterFn: this.filterString
                    },
                },
                {
                    label:'Nom de l\'utilisateur',
                    field: this.nameFn,
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par utilisateur', // placeholder for filter input
                        filterFn: this.filterString
                    },
                },
                {
                    label:'Date de la recherche',
                    field: 'created_at',
                    type:'date',
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
                    label:'Statut',
                    field: 'status',
                    sortable:false
                },
                {
                    label:'Action',
                    field: 'action',
                    sortable:false
                },
            ]
        }
    },
    methods: {
        ...mapActions('admin', ['changeResearchStatus']),
        vehicleFn(research){
            if (!research.modele) {
                return ''
            }
            return research.modele.year ? research.modele.modele.brand.name + ' ' + research.modele.modele.name + ' (' + research.modele.year + ')' :
                research.modele.modele.brand.name + ' ' + research.modele.modele.name + ' (' + research.modele.modele.year_start + '-' + research.modele.modele.year_end + ')'
        },
        nameFn(research){
           return research.user ? research.user.firstname + ' ' + research.user.lastname : ''
        },
        partFn(research){
           return research.part && this.translation('name', research.part) || 'Pièce suggérée'
        },
        filterString(nom, filterString) {
            return nom.toLowerCase().includes(filterString.toLowerCase());
        },
        filterInt(nom, filterString) {
            return nom.toString().toLowerCase().includes(filterString.toString().toLowerCase());
        },

    }, computed: {
        ...mapGetters('admin', ['allResearches', 'isResearchesLoaded']),
    },
    created() {
        this.$emit('viewSubCatalog:update', false);
        this.$emit('viewSubResearch:update', true);
    }
}
</script>

<style scoped>

</style>
