<template>
    <div class="admin-catalog">

        <div class="mb-4">
            <h3 class="text-blue">Annonces à valider</h3>
        </div>


        <vue-good-table
            :columns="columns"
            :rows="allCatalog"
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
                <template v-if="props.column.field == 'id'">
                    <span v-if="props.row.deleted_at">
                        <del class="text-danger" title="Supprimé">{{ props.row.id }}</del>
                    </span>
                    <span v-else>
                        {{ props.row.id }}
                    </span>
                </template>
                <template v-else-if="props.column.label == 'Statut'">
                    <span v-if="!props.row.sent&&props.row.status.id==1&&!props.row.suggestion" class="badge badge-pill badge-warning">Attente validation</span>
                    <span v-else-if="!props.row.sent&&props.row.status.id==1" class="badge badge-pill badge-warning">Pièce suggérée</span>
                    <span v-else-if="props.row.sent||props.row.status.id==3" class="badge badge-pill" :class="props.row.status.class">{{ props.row.status.label }}</span>
                </template>
                <template v-else-if="props.column.field == 'action'">
                    <button class="btn btn-xs btn-primary px-4" @click="changeShowDetails(props.row.originalIndex,props.row)">Détails</button>

                </template>
            </template>
            <div slot="emptystate">
                <div class="bg-white text-blue px-4 py-4 mt-3">
                    Aucune annonce ne correspond aux filtres
                </div>
            </div>
        </vue-good-table>
        <div class="bg-white text-blue px-4 py-4 mt-3" v-if="allCatalog.length == 0">
            Aucune annonce à valider pour l'instant !
        </div>
<!--        <div v-else>-->
<!--            <table class="table table-striped" v-if="isCatalogLoaded">-->
<!--                <thead>-->
<!--                <tr>-->
<!--                    &lt;!&ndash;                    <th scope="col"><input type="checkbox"></th>&ndash;&gt;-->
<!--                    <th scope="col">ID</th>-->
<!--                    <th scope="col">Nom de l'annonce</th>-->
<!--                    <th scope="col">Pièce</th>-->
<!--                    <th scope="col">Marque / Modèle</th>-->
<!--                    <th scope="col">Référence</th>-->
<!--                    <th scope="col">Prix</th>-->
<!--                    <th scope="col">Stock</th>-->
<!--                    <th scope="col">Etat</th>-->
<!--                    <th scope="col">Statut</th>-->
<!--                    <th scope="col"></th>-->
<!--                </tr>-->
<!--                </thead>-->
<!--                <tbody>-->
<!--                <tr v-for="(product,i) in allCatalog">-->
<!--                    &lt;!&ndash;                    <th scope="row"><input type="checkbox"></th>&ndash;&gt;-->
<!--                    <td>{{ product.id }}</td>-->
<!--                    <td scope="row">{{ product.name }}</td>-->
<!--                    <td scope="row" v-if="product.part||product.suggestion">-->
<!--                        {{ product.part ? product.part.translation : 'Pièce suggérée:' + product.suggestion }}-->
<!--                    </td>-->
<!--                    <th scope="row" v-else>-</th>-->
<!--                    <td v-if="product.modele[0]">{{ product.modele[0].brand.name }} {{-->
<!--                            product.modele[0].name-->
<!--                        }}-->
<!--                    </td>-->
<!--                    <td v-else>Pas de modèle</td>-->
<!--                    <td>{{ product.reference ? product.reference : 'Pas de ref' }}</td>-->
<!--                    <td v-if="product.price">{{ product.price }} {{ product.currency }}</td>-->
<!--                    <td v-else>-</td>-->
<!--                    <td>{{ product.stock }}</td>-->
<!--                    <td>{{ getStatusText(product.condition) }}</td>-->
<!--                    <td>-->
<!--                        <span v-if="!product.sent&&product.status.id==1&&!product.suggestion" class="badge badge-pill badge-warning">Attente validation</span>-->
<!--                        <span v-else-if="!product.sent&&product.status.id==1" class="badge badge-pill badge-warning">Pièce suggérée</span>-->
<!--                        <span v-else-if="product.sent||product.status.id==3" class="badge badge-pill" :class="product.status.class">{{ product.status.label }}</span>-->
<!--                    </td>-->
<!--                    <td>-->
<!--                        <button class="btn btn-xs btn-primary px-4" @click="changeShowDetails(i)">Détails</button>-->
<!--                    </td>-->
<!--                </tr>-->
<!--                </tbody>-->
<!--            </table>-->

<!--            <div v-else>-->
<!--                <span class="loader"></span>-->
<!--            </div>-->

            <AdminCatalogDetails  :class="{'active' : showDetails[indexOpened]}" :index="indexOpened"  @showDetails="changeShowDetails" :key="productOpened&&productOpened.id" :product="productOpened"></AdminCatalogDetails>

<!--        </div>-->

    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";
import {status} from "../../../mixins/status";
const AdminCatalogDetails = () => import(/* webpackChunkName: "group-finder" */'./AdminCatalogDetails');

export default {
    name: "AdminCatalog",
    mixins: [status],
    components: {AdminCatalogDetails},
    data(){
        return {
            showDetails: [],
            indexOpened:null,
            productOpened:null,
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
                    label: 'Titre de l\'annonce',
                    field: this.nameField,
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Filtrer par nom',
                        filterFn: this.filterString
                    },
                }, {
                    label: 'Pièce',
                    field: this.partFn,
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par pièce', // placeholder for filter input
                        filterFn: this.filterString
                    },
                }, {
                    label: 'Marque / Modèle',
                    field: this.brandFn,
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par modèle', // placeholder for filter input
                        filterFn: this.filterString
                    },
                }, {
                    label: 'Référence interne',
                    tdClass: 'text-center',
                    field: this.referenceFn,
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par référence', // placeholder for filter input
                        filterFn: this.filterString
                    },
                    sortable: false,

                }, {
                    label: 'Prix',
                    field: 'price',
                    formatFn: this.productFn,
                    type: 'decimal',
                    tdClass: 'text-center'
                }, {
                    label: 'Stock',
                    field: 'stock',
                    sortable: false,
                    tdClass: 'text-center',

                }, {
                    label: 'Etat',
                    field: this.statusFn,
                    tdClass: 'text-center',
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Tous', // placeholder for filter input
                        filterDropdownItems: ['Neuf', 'Très bon', 'Bon', 'Satisfaisant', 'A rénover'],
                    },
                }, {
                    label: 'Statut',
                    field: function(obj){
                        return obj;
                    },
                    sortFn: (x, y, col, rowX, rowY) => {//TODO: A fixer, le tri ne marche pas dans les deux sens
                        if (rowX.suggestion && !rowY.suggestion )return 1;
                        else if(rowX.suggestion && rowY.suggestion)return 0;
                        else return -1;
                    },
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Tous', // placeholder for filter input
                        filterDropdownItems: [
                            {value: 1, text: 'Pièce suggérée'},
                            {value: 2, text: 'Attente validation'},
                        ],
                        filterFn: function(data, filterString) {
                            if (filterString==1)return data.suggestion!==null;
                            else return data.suggestion==null
                        },
                    }
                }, {
                    label: 'Action',
                    field: 'action',
                    sortable: false,
                    tdClass: 'text-center',

                },
            ]

        }
    },
    computed: {
        ...mapGetters('admin', ['allCatalog', 'isCatalogLoaded']),
    },
    methods:{
        ...mapActions('admin',['fetchCatalog']),
        closeAllModals(index) {
            let product = this.allCatalog[index]
            let allModal = document.querySelectorAll('.research-proposal-details.active')
            for (var i = 0; i < allModal.length; i++) {
                allModal[i].classList.remove('active');
            }
            for (let i = this.showDetails.length - 1; i >= 0; i--) {
                if (this.allCatalog[i].id !== product.id) this.showDetails[i] = false
            }
        },
        changeShowDetails(i,product){
            this.productOpened = product;
            if(this.indexOpened!=i)this.indexOpened = i
            else this.indexOpened=null
            this.$set(this.showDetails,i,!this.showDetails[i]);
        },
        partFn(product) {
            if (product.part || product.suggestion) {
                if (product.part) return this.translation('name', product.part)
                else return 'Pièce suggérée:' + product.suggestion
            } else {
                return '-';
            }
        },
        filterInt(nom, filterString) {
            return nom.toString().toLowerCase().includes(filterString.toString().toLowerCase());
        },
        brandFn(product) {
            if (product.modele[0]) {
                return product.modele[0].brand.name + ' ' + product.modele[0].name;
            } else {
                return 'Pas de modèle';
            }
        },
        referenceFn(product) {
            if (product.intern) return product.intern
            else return 'Pas de ref interne'
        },
        productFn(price) {
            if (price) return price + ' €'
            else return '-';
        },
        statusFn(product) {
            return this.getStatusText(product.condition)
        },
        filterString(nom, filterString) {
            return nom.toLowerCase().includes(filterString.toLowerCase());
        },
        nameField(product) {
            return product.original_name
        }
    },
    created(){
        this.fetchCatalog().then(res=>{
            this.allCatalog.forEach(res => {
                this.showDetails.push(false);
            })
        });
        this.$store.dispatch('categories/fetchAllCategories');
        this.$emit('viewSubResearch:update',false);
        this.$emit('viewSubCatalog:update',true);

    }
}
</script>

<style scoped>

</style>
