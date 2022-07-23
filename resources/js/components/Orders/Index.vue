<template>
    <div class="research-alert">
        <div class="bg-white shadow container-fluid py-lg-4 py-3 px-4 mb-4">

            <div class="d-flex align-items-center justify-content-between">
                <h1 class="h2 text-blue mb-0 pt-lg-3 pt-2">All orders</h1>
                <top-nav></top-nav>
            </div>

        </div>

        <div class="container-fluid" v-if="allOrders.length !== 0&&isOrdersLoaded">
            <div class="table-wrapper" :class="'table-base'">


                <div class="filters pb-4 pt-2">
                    <div class="research w-50">
                        <i class="fal fa-search"></i>
                        <input type="text" class="form-control w-100" :placeholder="$t('rechercher_par_marque_modle')"
                               v-model="search">
                    </div>
                </div>

                <vue-good-table
                    :columns="columns"
                    :rows="allOrders"
                    styleClass="vgt-table striped"
                    :pagination-options="{
                    enabled: allOrders.length>15,
                    mode:'pages',
                    perPage:15,
                    perPageDropdownEnabled: true,
                    rowsPerPageLabel: $t('recherches_par_page'),
                    ofLabel:'sur',
                    allLabel:$t('tous'),
                    nextLabel: $t('next'),
                    prevLabel:$t('prcdent'),
                    }"
                    :row-style-class="isActived"
                    :search-options="{
                        enabled: true,
                        externalQuery: search
                        }">

                    <template slot="table-row" slot-scope="props">
                        <template v-if="props.column.field == 'field_researches'">
                            <div v-if="props.row.active==1" class="w-100">
                                <router-link :to="{name:'ResearchAlertProposalList',params:{id:props.row.id}}"
                                             v-if="props.row.researches.length>0"
                                             class="btn bg-gold btn-full btn-center px-3">{{
                                        $t('voir_les_recherches')
                                    }}
                                </router-link>
                                <span class="mb-0 d-flex justify-content-center align-items-center py-2" v-else>
                                        <i class="fal fa-hourglass-half mr-2"></i>
                                        {{ $t('en_attente_dune_recherche') }}
                                    </span>
                            </div>
                            <div v-else class="w-100">
                                    <span class="py-2 d-flex justify-content-center align-items-center text-danger ">

                                        <i class="fal fa-ban pr-1"></i>
                                        {{ $t('annonce_dsactive') }}
                                    </span>

                                <div class="reactived" v-if="$auth.user().alerts_view == 'table-grid'">
                                    <button class="btn bg-gold btn-full btn-center mr-2"
                                            @click="updateActiveState([props.row.id, 1])">
                                        {{ $t('activer') }}
                                    </button>
                                    <button class="btn bg-dark-grey btn-del" @click="removal(props.row)">
                                        <i class="fal fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>

                            <b-dropdown right size="xs" class="ml-2" v-if="$auth.user().alerts_view == 'table-banner'">
                                <template v-slot:button-content>
                                    <i class="far fa-ellipsis-h fa-lg"></i>
                                </template>
                                <b-dropdown-item v-if="props.row.active===1"
                                                 @click="updateActiveState([props.row.id, 0])">
                                    <i class="fal fa-ban pr-1"></i>{{ $t('dsactiver') }}
                                </b-dropdown-item>
                                <b-dropdown-item v-else @click="updateActiveState([props.row.id, 1])">
                                    <i class="fal fa-check"></i> {{ $t('activer') }}
                                </b-dropdown-item>
                                <b-dropdown-divider></b-dropdown-divider>
                                <b-dropdown-item @click="removal(props.row)"><i class="fal fa-trash"></i>
                                    {{ $t('remove') }}
                                </b-dropdown-item>
                            </b-dropdown>

                        </template>

                        <template v-else-if="props.column.field == 'order'">

                            <router-link class="btn bg-gold" :to="{ name: 'showProduct', params:{id:props.row.id} }">
                                {{ props.row.id }}
                            </router-link>

                        </template>

                        <template v-else-if="props.column.field == 'product'">
                            {{ props.row.product.original_name }}
                        </template>

                        <template v-else-if="props.column.field == 'firstname'">
                            {{ props.row.firstname }}
                        </template>

                        <template v-else-if="props.column.field == 'lastname'">
                            {{ props.row.lastname }}
                        </template>

                        <template v-else-if="props.column.field == 'country'">
                            {{ props.row.country.name }}
                        </template>

                        <template v-else-if="props.column.field == 'region'">
                            {{ props.row.region.name }}
                        </template>

                        <template v-else-if="props.column.field == 'city'">
                            {{ props.row.city }}
                        </template>

                        <template v-else-if="props.column.field == 'phone'">
                            {{ props.row.phone }}
                        </template>

                        <template v-else-if="props.column.field == 'zip'">
                            {{ props.row.zip }}
                        </template>

                    </template>
                    <template slot="selected-row-actions">
                        <button class="btn btn-danger" @click="batchRemove">{{ $t('supprimer_la_slection') }}</button>
                    </template>
                    <div slot="emptystate">
                        {{ $t('aucune_annonce_simplifie_ne_correspond_aux_filtres') }}
                    </div>
                </vue-good-table>
            </div>
        </div>

        <div class="container" v-else-if="isOrdersLoaded">
            <div class="row">
                <div
                    class="col-md-6 offset-md-3 d-flex align-items-center justify-content-center flex-column empty-zone">
                    <img class="w-100 mb-5" :src="'/images/illustrations/alertes.svg'">
                    <p class="lead text-blue text-center">No orders</p>
                    <p class="text-center px-5">There are no orders currently, the list of orders will appear here when available</p>
                </div>
            </div>
        </div>
        <div v-else><span class="loader"></span></div>

    </div>
</template>
<script>
import mxView from "../../mixins/View";

//const OrdersItem = () => import('./OrdersItem');
const TopNav = () => import(/* webpackChunkName: "group-dashboard" */"../Shared/TopNav");
import {mapActions, mapGetters} from "vuex";

export default {
    //components: {OrdersItem, TopNav},
    components: {TopNav},
    mixins: [mxView],

    data() {
        return {
            selectedRows: [],
            name: 'catalog',
            brandSelected: '0',
            categorySelected: '0',
            modelSelected: '0',
            partSelected: '0',
            parts: [],
            models: [],
            categories: [],
            configs: [],
            search: '',
            columns: [
                {
                    label: 'Order ID',
                    field: 'order',
                    tdClass: 'font-weight-bold title',
                },
                {
                    label: 'Product',
                    field: 'product',
                    tdClass: 'font-weight-bold title',
                },
                {
                    label: 'First name',
                    field: 'firstname',
                    tdClass: 'font-weight-bold title',
                },
                {
                    label: 'Last name',
                    field: 'lastname',
                    tdClass: 'font-weight-bold title',
                },

                {
                    label: 'Country',
                    field: 'country',
                    tdClass: 'font-weight-bold title',
                },
                {
                    label: 'Region',
                    field: 'region',
                    tdClass: 'font-weight-bold title',
                },
                {
                    label: 'city',
                    field: 'city',
                    tdClass: 'font-weight-bold title',
                },
                {
                    label: 'phone',
                    field: 'phone',
                    tdClass: 'font-weight-bold title',
                }
                ,
                {
                    label: 'zip',
                    field: 'zip',
                    tdClass: 'font-weight-bold title',
                }
            ],
        }
    },
    created() {
        this.fetchAllOrders();
    },
    methods: {
        ...mapActions('orders', ['fetchAllOrders', 'updateActiveState', 'batchRemoveOrders']),

        deleteOrder: function (order) {
            this.$store.dispatch('orders/removeOrder', order)
        },
        removal(order) {
            const h = this.$createElement;
            const body = [h('div', {class: ['position-relative', 'text-blue']}, [
                h('h3', {class: 'text-center'}, [
                    this.$t('sure_supprimer_cette_annonce_simplifie')
                ])
            ]), h('p', {class: 'h3'}, [])];
            this.$removalConfirmation(body, null).then(removal => removal ? this.deleteOrder(order) : null)
        },
        isActived(row) {
            return row.active ? '' : 'disabled';
        },
        selectionChanged(e) {
            const selectedRows = e.selectedRows
            this.selectedRows = selectedRows.map(el => el.id)
        },
        batchRemove() {
            const h = this.$createElement;
            let body = [];
            if (this.selectedRows.length > 1) {
                body = [h('div', {class: ['position-relative', 'text-blue']}, [
                    h('h3', {class: 'text-center'}, [
                        this.$t('sure_supprimer_ces_annonces_simplifies')
                    ])
                ]), h('p', {class: 'h3'}, [])];
            } else {
                body = [h('div', {class: ['position-relative', 'text-blue']}, [
                    h('h3', {class: 'text-center'}, [
                        this.$t('sure_supprimer_cette_annonce_simplifie')
                    ])
                ]), h('p', {class: 'h3'}, [])];
            }
            this.$removalConfirmation(body, null).then(removal => removal ? this.batchRemoveOrders(this.selectedRows) : null)
        }


    },
    computed: {
        ...mapGetters('orders', ['allOrders', 'isOrdersLoaded']),
    }
}
</script>
