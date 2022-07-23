<template>
    <div class="catalog">
        <div class="bg-white shadow container-fluid py-lg-4 py-3 px-4" :class="{'mb-4' : allProducts.length != 0}">

            <div class="d-flex align-items-center justify-content-between">
                <h1 class="h2 text-blue mb-0 pt-lg-3 pt-2">{{ $t('my_products') }}</h1>
                <top-nav></top-nav>
            </div>

        </div>
        <div v-if="!isProductLoaded" class="container-fluid">
            <span class="loader"></span>
        </div>
        <div class="container-fluid" v-else-if="isProductLoaded && allProducts.length != 0">

            <div class="table-wrapper" :class="$auth.user().products_view || 'table-base'">

                <div class="filters pb-4 pt-2">

                    <div class="research w-50">
                        <i class="fal fa-search"></i>
                        <input type="text" class="form-control w-100" :placeholder="$t('rechercher_par_type_de_produit_modle')"
                               v-model="search">
                    </div>
                    <div class="d-flex">
                        <button class="btn btn-sm px-3 bg-dark-grey mr-2" @click="exportCatalog"><i class="fal fa-file-export"></i> {{ $t('exporter_vos_produits') }}</button>
                        <div class="view-mode">
                            <div class="view-item">
                                <input @change="setView($event,'products_view')" type="radio" name="view-mode"
                                       id="table-grid" :value="'table-grid'"
                                       :checked="$auth.user().products_view == 'table-grid'" class="d-none">
                                <label for="table-grid" v-b-tooltip.hover :title="$t('grid_display')"><i
                                    class="fal fa-grip-horizontal"></i></label>
                            </div>
                            <div class="view-item">
                                <input @change="setView($event,'products_view')" type="radio" name="view-mode"
                                       id="table-banner" :value="'table-banner'"
                                       :checked="$auth.user().products_view == 'table-banner'" class="d-none">
                                <label for="table-banner" v-b-tooltip.hover :title="$t('line_display')"><i
                                    class="fal fa-bars"></i></label>
                            </div>
                            <div class="view-item">
                                <input @change="setView($event,'products_view')" type="radio" name="view-mode"
                                       id="table-base" :value="'table-base'"
                                       :checked="$auth.user().products_view == 'table-base' || $auth.user().products_view == null"
                                       class="d-none">
                                <label for="table-base" v-b-tooltip.hover :title="$t('table_display')"><i
                                    class="fal fa-table"></i></label>
                            </div>
                        </div>
                    </div>


                </div>

                <vue-good-table
                    :columns="columns"
                    :rows="allProducts"
                    ref="my-table"
                    styleClass="vgt-table striped"
                    :pagination-options="{
                    enabled: allProducts.length>15,
                    mode:'pages',
                    perPage:15,
                    perPageDropdownEnabled: true,
                    rowsPerPageLabel: $t('annonces_par_page'),
                    ofLabel:'sur',
                    allLabel:$t('tous'),
                    nextLabel:$t('next'),
                    prevLabel:$t('prcdent'),
                }"
                    :search-options="{
                        enabled: true,
                        externalQuery: search
                        }">
                    <!--                :select-options="{-->
                    <!--                    enabled: true,-->
                    <!--                    selectionText: 'lignes selectionnée(s)',-->
                    <!--                        clearSelectionText: 'Désélectionner',-->
                    <!--                            selectOnCheckboxOnly: true, // only select when checkbox is clicked instead of the row-->
                    <!--                   }"-->
                    <!--            >-->
                    <template slot="table-row" slot-scope="props">

                        <template v-if="props.column.field == 'name'">

                            <div class="img-wrapper base-none">
                                <div class="img" :style="'background-image: url('+(props.row.img ? props.row.img[0] : '')+');'"></div>
                            </div>

                            <div class="base-none banner-none float-right">
                                <span v-if="!props.row.sent&&props.row.status.id==1"
                                      class="badge badge-pill badge-warning w-100"
                                      v-b-tooltip.hover
                                      :title="$t('votre_produit_sera_diffus_ds_validation_par_un_adm')">{{ $t('moderation_in_progress') }}</span>
                                <span v-else-if="props.row.sent||props.row.status.id==3" class="badge badge-pill w-100"
                                      :class="props.row.status.class">{{ props.row.sent?$t('diffus'):$t('brouillon') }}</span>
                            </div>

                            <div>
                                {{ props.row.name }}

                                <div class="font-weight-normal" v-if="$auth.user().products_view == 'table-banner'">
                                    {{ partFn(props.row) }}
                                </div>
                            </div>

                        </template>

                        <template v-else-if="props.column.field == 'status.id'">
                            <div class="w-100 mr-2">
                                <span v-if="!props.row.sent&&props.row.status.id==1"
                                      class="badge badge-pill badge-warning w-100"
                                      v-b-tooltip.hover
                                      :title="$t('votre_produit_sera_diffus_ds_validation_par_un_adm')">{{ $t('moderation_in_progress') }}</span>
                                <span v-else-if="props.row.sent||props.row.status.id==3" class="badge badge-pill w-100"
                                      :class="props.row.status.class">{{ props.row.sent?$t('diffus'):$t('brouillon') }}</span>
                            </div>


                            <b-dropdown right size="xs" v-if="$auth.user().products_view == 'table-banner'">
                                <template v-slot:button-content>
                                    <i class="far fa-ellipsis-h"></i>
                                </template>
                                <router-link tag="b-dropdown-item" :to="{name:'addProduct',params:{id:props.row.id}}">
                                    <i class="fal fa-edit"></i> {{ $t('modify') }}
                                </router-link>
                                <!-- <b-dropdown-item v-if="props.row.status.id==3&&props.row.part_id&&props.row.name&&
                                    props.row.description&&props.row.price>=2&&props.row.img&&
                                    props.row.weight >= 0.1&&props.row.img.length>0&&props.row.stock>=1"
                                                 @click="changeProductStatus(props.row)">
                                    <i class="fal fa-broadcast-tower"></i> Diffuser
                                </b-dropdown-item> -->
                                <b-dropdown-divider></b-dropdown-divider>
                                <b-dropdown-item @click="removal(props.row)"><i class="fal fa-trash"></i> {{ $t('remove') }}
                                </b-dropdown-item>
                            </b-dropdown>

                            <b-dropdown right v-if="props.row.sent&&props.row.status.id==1 && $auth.user().products_view == 'table-banner'" class="ml-2" size="lg" variant="link" toggle-class="text-decoration-none" no-caret >
                                <template #button-content>
                                    <div class="px-2 py-1">
                                        <i class="fas fa-share-alt"></i>
                                    </div>
                                </template>
                                <b-dropdown-text menu-class="px-0">
                                    <p class="mb-0 lead max-content text-blue">{{ $t('partager_le_produit') }}</p>
                                </b-dropdown-text>
                                <b-dropdown-item class="text-blue" @click="copyUrl(props.row.id)"><i class="fas fa-share-alt fa-fw mr-1"></i> {{ $t('copier_le_lien') }}</b-dropdown-item>
                                <b-dropdown-item class="text-blue" target="_blank" :href="'https://www.facebook.com/sharer.php?u='+returnAppUrl()+'/' + $i18n.locale + '/product/'+props.row.id"><i class="fab fa-facebook fa-fw mr-1"></i> {{ $t('facebook') }}</b-dropdown-item>
                            </b-dropdown>

                        </template>
                        <template v-else-if="props.column.field == 'edit'">

                            <div v-if="$auth.user().products_view != 'table-grid'">
                                <b-dropdown right size="xs" >
                                    <template v-slot:button-content>
                                        <i class="far fa-ellipsis-h fa-lg"></i>
                                    </template>
                                    <router-link tag="b-dropdown-item" :to="{name:'addProduct',params:{id:props.row.id}}">
                                        <i class="fal fa-edit"></i> {{ $t('modify') }}
                                    </router-link>
                                    <b-dropdown-item v-if="props.row.status.id==3&&props.row.part_id&&props.row.name&&
                                        props.row.description&&props.row.price>=2&&props.row.img&&
                                        props.row.weight >= 0.1&&props.row.img.length>0&&props.row.stock>=1"
                                                     @click="changeProductStatus(props.row)">
                                        <i class="fal fa-broadcast-tower"></i> {{ $t('diffuser') }}
                                    </b-dropdown-item>
                                    <b-dropdown-item @click="removal(props.row)"><i class="fal fa-trash"></i> {{ $t('remove') }}
                                    </b-dropdown-item>
                                    <b-dropdown-divider></b-dropdown-divider>
                                    <b-dropdown-item class="text-blue" @click="copyUrl(props.row.id)"><i class="fas fa-share-alt fa-fw mr-1"></i> {{ $t('copier_le_lien') }}</b-dropdown-item>
                                    <b-dropdown-item class="text-blue" target="_blank" :href="'https://www.facebook.com/sharer.php?u='+returnAppUrl()+'/' + $i18n.locale + '/product/'+props.row.id"><i class="fab fa-facebook fa-fw mr-1"></i> {{ $t('facebook') }}</b-dropdown-item>

                                </b-dropdown>

                                <!-- <b-dropdown right v-if="props.row.sent&&props.row.status.id==1" class="ml-2" size="lg" variant="link" toggle-class="text-decoration-none" no-caret >
                                    <template #button-content>
                                        <div class="px-2 py-1">
                                            <i class="fas fa-share-alt"></i>
                                        </div>
                                    </template>
                                    <b-dropdown-text menu-class="px-0">
                                        <p class="mb-0 lead max-content text-blue">Partager le produit</p>
                                    </b-dropdown-text>
                                    <b-dropdown-item class="text-blue" @click="copyUrl(props.row.id)"><i class="fas fa-share-alt fa-fw mr-1"></i> Copier le lien</b-dropdown-item>
                                    <b-dropdown-item class="text-blue" target="_blank" :href="'https://www.facebook.com/sharer.php?u='+returnAppUrl()+'/product/'+props.row.id"><i class="fab fa-facebook fa-fw mr-1"></i> Facebook</b-dropdown-item>
                                </b-dropdown> -->

                            </div>



                            <div v-else class="d-flex w-100">

                                <router-link tag="button" :to="{name:'addProduct',params:{id:props.row.id}}"
                                             class="btn bg-dark-grey btn-center btn-full">
                                    {{ $t('modify') }}
                                </router-link>
                                <button class="btn bg-grey btn-del ml-2" @click="removal(props.row)">
                                    <i class="fal fa-trash-alt"></i>
                                </button>

                                <b-dropdown right v-if="props.row.sent&&props.row.status.id==1" class="ml-2" size="lg" variant="link" toggle-class="text-decoration-none" no-caret>
                                    <template #button-content>
                                        <div class="px-2 py-1">
                                            <i class="fas fa-share-alt"></i>
                                        </div>
                                    </template>
                                    <b-dropdown-text menu-class="px-0">
                                        <p class="mb-0 lead max-content text-blue">{{ $t('partager_le_produit') }}</p>
                                    </b-dropdown-text>
                                    <b-dropdown-item class="text-blue" @click="copyUrl(props.row.id)"><i class="fas fa-share-alt fa-fw mr-1"></i> {{ $t('copier_le_lien') }}</b-dropdown-item>
                                    <b-dropdown-item class="text-blue" target="_blank" :href="'https://www.facebook.com/sharer.php?u='+returnAppUrl()+'/' + $i18n.locale + '/product/'+props.row.id"><i class="fab fa-facebook fa-fw mr-1"></i> {{ $t('facebook') }}</b-dropdown-item>
                                </b-dropdown>

                            </div>

                        </template>
                    </template>
                    <div slot="emptystate">
                        <div class="row">
                            <div
                                class="col-md-6 offset-md-3 d-flex align-items-center justify-content-center flex-column empty-zone">
                                <i class="fal fa-store text-gold fa-7x mb-3"></i>
                                <p class="lead text-blue text-center">{{ $t('aucun_produit_ne_correspond_votre_recherche') }}</p>
                            </div>
                        </div>
                    </div>
                    <div slot="selected-row-actions">
                        <button class="btn btn-primary" @click="deleteSelected">{{ $t('supprimer_la_selection') }}</button>
                    </div>
                </vue-good-table>
            </div>

        </div>
        <div class="container" v-else>
            <div class="row">
                <div
                    class="col-md-6 offset-md-3 d-flex align-items-center justify-content-center flex-column empty-zone">
                    <i class="fal fa-store text-gold fa-7x mb-3"></i>
                    <p class="lead text-blue text-center">{{ $t('aucun_produit') }}</p>
                    <p class="text-center px-5"> {{ $t('crez_et_diffusez_vos_annonces_sur_classic_parts_fi') }} </p>
                    <router-link class="btn bg-gold btn-sm" :to="{ name: 'addProduct' }">
                        {{ $t('ajouter_mon_premier_produit') }}
                    </router-link>
                </div>
            </div>
        </div>
        <b-modal id="alertConfirmation" hide-footer centered :title="$t('confirmation_dajout_du_produit')" content-class="rounded border-0 shadow-lg">
            <div>
                <p class="lead font-weight-normal">{{ $t('le_produit_sera_transmis_lacheteur_aprs_validation') }}</p>
                <div class="alert alert-info d-flex mb-0">
                    <i class="far fa-info-circle pt-1 mr-2"></i>
                    {{ $t('si_malgr_tout_la_vente_na_pas_lieu_vous_navez_pas') }}
                </div>
            </div>
        </b-modal>
    </div>
</template>
<script>
import {mapActions, mapGetters} from "vuex";
import {status} from "../../mixins/status";
import mxView from "../../mixins/View";
import profitCalculation from "../../mixins/profitCalculation";

const TopNav = () => import(/* webpackChunkName: "group-dashboard" */"../Shared/TopNav");

export default {
    components: {TopNav},
    data() {
        return {
            name: 'catalog',
            models: [],
            categories: [],
            search: '',
            link_url: process.env.MIX_APP_URL+'/'+ this.$i18n.locale + '/product/',
            columns: [
                {
                    label: this.$t('titre_de_lannonce'),
                    field: 'original_name',
                    filterOptions: {
                        enabled: true,
                        placeholder: this.$t('filtrer_par_nom'),
                        filterFn: this.filterString
                    },
                    tdClass: 'font-weight-bold title',
                }, {
                    label: this.$t('type_de_produit'),
                    field: this.partFn,
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: this.$t('filtrer_par_type_de_produit'), // placeholder for filter input
                        filterFn: this.filterString
                    },
                    tdClass: 'banner-none',
                }, {
                    label: this.$t('marque_modle'),
                    field: this.brandFn,
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: this.$t('filtrer_par_modle'), // placeholder for filter input
                        filterFn: this.filterString
                    },
                    tdClass: 'marque',
                }, {
                    label: this.$t('rfrence_interne'),
                    tdClass: 'text-center ref-interne banner-none grid-none',
                    field: this.referenceFn,
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: this.$t('filtrer_par_rfrence'), // placeholder for filter input
                        filterFn: this.filterString
                    },
                    sortable: false,

                }, {
                    label: this.$t('prix'),
                    field: 'price',
                    formatFn: this.productFn,
                    type: 'decimal',
                    tdClass: 'text-center price'
                }, {
                    label: this.$t('stock'),
                    field: 'stock',
                    sortable: false,
                    tdClass: 'text-center stock banner-none grid-none',

                }, {
                    label: this.$t('etat'),
                    field: this.statusFn,
                    tdClass: 'text-center etat banner-none grid-none',
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: this.$t('tous'), // placeholder for filter input
                        filterDropdownItems: [
                            this.$t('brand_new'),
                            this.$t('very_good'),
                            this.$t('good'),
                            this.$t('satisfying'),
                            this.$t('renovate')
                        ],
                    },
                }, {
                    label: this.$t('status'),
                    field: 'status.id',
                    sortFn: (x, y, col, rowX, rowY) => {//TODO: A fixer, le tri ne marche pas dans les deux sens
                        if (rowX.sent && rowX.status.id == 1 || rowX.status.id !== 1) {//si e1 est vert ou bleu
                            if (rowX.status.id < rowY.status.id) return -1;
                            else if (rowX.status.id > rowY.status.id) return 1;
                            else return 0;
                        } else {//si e1 est jaune
                            if (rowY.status.id == 1 && !rowY.sent) return 0;
                            else return 1;
                        }
                    },
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: this.$t('tous'), // placeholder for filter input
                        filterDropdownItems: [
                            {value: 1, text: this.$t('moderation_diffus')},
                            {value: 3, text: this.$t('brouillon')},
                        ]
                    },
                    tdClass: 'statut grid-none',
                }, {
                    label: this.$t('dition'),
                    field: 'edit',
                    sortable: false,
                    thClass: 'edit',
                    tdClass: 'edit text-center action banner-none',
                },
            ]

        }
    },
    mixins: [status, mxView, profitCalculation],

    created() {
        this.fetchAllProducts();
    },
    mounted(){
        if (this.$route.params.alert){
            this.$bvModal.show('alertConfirmation');
        }
    },
    methods: {
        returnAppUrl(){
            return process.env.MIX_APP_URL;
        },
        copyUrl(id) {
            const el = document.createElement('textarea');
            el.value = this.link_url+id;
            el.setAttribute('readonly', '');
            el.style.position = 'absolute';
            el.style.left = '-9999px';
            document.body.appendChild(el);
            const selected =  document.getSelection().rangeCount > 0  ? document.getSelection().getRangeAt(0) : false;
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            if (selected) {
              document.getSelection().removeAllRanges();
              document.getSelection().addRange(selected);
            }
        },
        deleteSelected() {
            let products = this.$refs['my-table'].selectedRows

        },
        partFn(product) {
            if (product.part || product.suggestion) {
                if (product.part) return this.translation('name', product.part)
                else return this.$t('produit_suggr') + ' : ' + product.suggestion
            } else {
                return '-';
            }
        },
        brandFn(product) {
            if (product.modele[0]) {
                return product.modele[0].brand.name + ' ' + product.modele[0].name;
            } else {
                return this.$t('pas_de_modle');
            }
        },
        referenceFn(product) {
            if (product.intern) return product.intern
            else return this.$t('pas_de_ref_interne')
        },
        productFn(price) {
            if (price) {
                return this.sellerPrice(price).profit + ' €'
            }
            else return '-';
        },
        statusFn(product) {
            return this.getStatusText(product.condition)
        },
        filterString(nom, filterString) {
            return nom.toLowerCase().includes(filterString.toLowerCase());
        },
        changeProductStatus: function (product) {
            let app = this;
            axios.put('merchant/' + this.$auth.user().merchant_id + '/product/' + product.id, {
                status: 1
            }).then(function (res) {
                app.$store.commit('products/SET_STATUS', {product: product, status: res.data[0].status})
            })
        },
        exportCatalog() {
            axios.get('merchant/' + this.$auth.user().merchant_id + '/exportCatalog',
                {responseType: 'arraybuffer'}).then((response) => {
                var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                var fileLink = document.createElement('a');
                fileLink.href = fileURL;
                fileLink.setAttribute('download', 'catalog.xlsx');
                document.body.appendChild(fileLink);
                fileLink.click();
            })
        },
        removal(product) {
            const h = this.$createElement;
            const body = [h('div', {class: ['position-relative', 'text-blue']}, [
                h('h3', {class: 'text-center'}, [
                    this.$t('sure_supprimer_produit')
                ])
            ]), h('p', {class: 'h3'}, [])];
            this.$removalConfirmation(body, null).then(removal => removal ? this.deleteProduct(product) : null)
        },
        deleteProduct(product) {
            this.$store.dispatch('products/removeProduct', product)
        },
        ...mapActions('products', ['fetchAllProducts']),
    },
    computed: {
        ...mapGetters('products', ['allProducts', 'isProductLoaded']),
        ...mapGetters('merchant', ['merchant'])
    },
}
</script>
