<template>
    <div class="all-products">

        <div class="mb-4">
            <h3 class="text-blue">Toutes les annonces</h3>
        </div>

        <vue-good-table v-if="isProductsLoaded"
            :columns="columns"
            :rows="allProducts"
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
                <template v-if="props.column.label == 'Statut'&&props.row.status">
                    <template v-if="!props.row.deleted_at">
                        <span v-if="!props.row.sent&&props.row.status.id==1&&!props.row.suggestion"
                              class="badge badge-pill badge-warning">Attente validation</span>
                        <span v-else-if="!props.row.sent&&props.row.status.id==1" class="badge badge-pill badge-warning">Pièce suggérée</span>
                        <span v-else-if="props.row.sent||props.row.status.id==3" class="badge badge-pill"
                              :class="props.row.status.class">{{ props.row.status.label }}</span>
                        <span v-else class="badge badge-pill" :class="props.row.status.class">{{ props.row.status.label }}</span>
                    </template>
                    <span v-else
                          class="badge badge-pill badge-danger w-100">Supprimé</span>
                </template>
                <template v-else-if="props.column.label == 'Statut'">
                    -
                </template>
                <template v-else-if="props.column.field == 'action'">
                    <button class="btn btn-xs btn-primary px-4" @click="changeShowDetails(props.row.originalIndex, props.row)">
                        Détails
                    </button>

                </template>
            </template>
            <div slot="emptystate">
                <div class="bg-white text-blue px-4 py-4 mt-3">
                    Aucune annonce ne correspond aux filtres
                </div>
            </div>
        </vue-good-table>
        <div v-else><span class="loader"></span></div>
        <AdminCatalogDetails :class="{'active' : showDetails[indexOpened]}" :index="indexOpened"
                             @showDetails="changeShowDetails" :key="productOpened&&productOpened.id"
                             :product="productOpened"></AdminCatalogDetails>
    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";
import {status} from "../../../mixins/status";
import AdminCatalogDetails from "./AdminCatalogDetails";

export default {
    name: "AllProducts",
    mixins: [status],
    components: {AdminCatalogDetails},
    data() {
        return {
            showDetails: [],
            indexOpened:null,
            productOpened:null,
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

                },
                {
                    label:'Date de l\'annonce',
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
                    label:'Date de suppression',
                    field: 'deleted_at',
                    type:'date',
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
                    field: function (obj) {
                        return obj;
                    },
                    sortFn: (x, y, col, rowX, rowY) => {//TODO: A fixer, le tri ne marche pas dans les deux sens
                        if (rowX.deleted_at && !rowY.deleted_at) {
                            return 1;
                        }else if(!rowX.deleted_at && !rowY.deleted_at){
                            if (rowX.suggestion && !rowY.suggestion) return 1;
                            else if (rowX.suggestion && rowY.suggestion) return 0;
                        }
                        else return -1;
                    },
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Tous', // placeholder for filter input
                        filterDropdownItems: [
                            {value: 4, text: 'Pièce suggérée'},
                            {value: 2, text: 'Attente validation'},
                            {value: 3, text: 'Brouillon'},
                            {value: 1, text: 'Diffusée'},
                            {value: 5, text: 'Supprimé'},
                        ],
                        filterFn: function (data, filterString) {
                            if(data.status){
                                switch (filterString) {
                                    case '1':
                                        return data.status.id == filterString && !data.suggestion && data.sent == 1&&!data.deleted_at;
                                    case '2':
                                        return data.status.id == 1 && data.sent == 0 && !data.suggestion&&!data.deleted_at
                                    case '3':
                                        return data.status.id == filterString&&!data.deleted_at
                                    case '4':
                                        return data.suggestion !== null&&!data.deleted_at;
                                    case '5':
                                        return data.deleted_at !== null;
                                }
                            }

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
    created() {
        this.fetchAllProducts().then(() => {
            this.allProducts.forEach(res => {
                this.showDetails.push(false);
            })
        })
        this.$store.dispatch('categories/fetchAllCategories');
    },
    methods: {
        ...mapActions('admin', ['changeResearchStatus', 'fetchAllProducts']),
        closeAllModals(index) {
            let product = this.allProducts[index]
            let allModal = document.querySelectorAll('.research-proposal-details.active')
            for (var i = 0; i < allModal.length; i++) {
                allModal[i].classList.remove('active');
            }
            for (let i = this.showDetails.length - 1; i >= 0; i--) {
                if (this.allProducts[i].id !== product.id) this.showDetails[i] = false
            }
        },
        changeShowDetails(i,product) {
            this.productOpened = product;
            if(this.indexOpened!=i)this.indexOpened = i
            else this.indexOpened=null
            this.$set(this.showDetails, i, !this.showDetails[i]);
        },
        filterInt(nom, filterString) {
            return nom.toString().toLowerCase().includes(filterString.toString().toLowerCase());
        },
        partFn(product) {
            if (product.part || product.suggestion) {
                if (product.part) return this.translation('name', product.part)
                else return 'Pièce suggérée:' + product.suggestion
            } else {
                return '-';
            }
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
            if (nom)return nom.toLowerCase().includes(filterString.toLowerCase());
            else return false;
        },
        nameField(row) {
            return row.original_name
        }
    },
    computed: {
        ...mapGetters('admin', ['allProducts', 'isProductsLoaded']),
        productLength() {
            return this.allProducts.length
        }
    },
}
</script>

<style scoped>

</style>
