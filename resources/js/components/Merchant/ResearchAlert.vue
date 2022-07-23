<template>
    <div class="research-alert">
        <div class="bg-white shadow container-fluid py-lg-4 py-3 px-4 mb-4">

            <div class="d-flex align-items-center justify-content-between">
                <h1 class="h2 text-blue mb-0 pt-lg-3 pt-2">{{ $t('my_simplified_announces') }}</h1>
                <top-nav></top-nav>
            </div>

        </div>

        <div class="container-fluid" v-if="allAlerts.length !== 0&&isAlertsLoaded">

<!--            <div class="d-flex justify-content-between py-3 px-3">-->

<!--                 <span class="form-sm"><label for="filter"></label>-->
<!--                      <select id="filter">-->
<!--                            <option value="">Toutes les alertes</option>-->
<!--                      </select>-->
<!--                 </span>-->
<!--            </div>-->

                <!-- <div class="offset-md-6 col-md-6 offset-lg-8 col-lg-4 pb-4 px-4 form-sm">
                    <input placeholder="Rechercher" class="form-control">
                </div>
 -->
<!--                <div class="col-12 pb-4 px-4">-->
<!--                    <ResearchAlertItem v-for="alert in allAlertsFiltered" :alert="alert" :key="alert.id"></ResearchAlertItem>-->
<!--                </div>-->

                <div class="table-wrapper" :class="$auth.user().alerts_view || 'table-banner'">

                    <div class="filters pb-4 pt-2">
                        <div class="research w-50">
                            <i class="fal fa-search"></i>
                            <input type="text" class="form-control w-100" :placeholder="$t('rechercher_par_marque_modle')" v-model="search">
                        </div>
                        <div class="view-mode">
                            <div class="view-item">
                                <input @change="setView($event,'alerts_view')" type="radio" name="view-mode" id="table-grid" :value="'table-grid'" :checked="$auth.user().alerts_view == 'table-grid'" class="d-none">
                                <label for="table-grid" v-b-tooltip.hover :title="$t('grid_display')"><i class="fal fa-grip-horizontal"></i></label>
                            </div>
                            <div class="view-item">
                                <input @change="setView($event,'alerts_view')" type="radio" name="view-mode" id="table-banner" :value="'table-banner'" :checked="$auth.user().alerts_view == 'table-banner' || $auth.user().alerts_view == null" class="d-none">
                                <label for="table-banner" v-b-tooltip.hover :title="$t('line_display')"><i class="fal fa-bars"></i></label>
                            </div>
                            <div class="view-item">
                                <input @change="setView($event,'alerts_view')" type="radio" name="view-mode" id="table-base" :value="'table-base'" :checked="$auth.user().alerts_view == 'table-base'" class="d-none">
                                <label for="table-base" v-b-tooltip.hover :title="$t('table_display')"><i class="fal fa-table"></i></label>
                            </div>
                        </div>
                    </div>

                    <vue-good-table
                    :columns="columns"
                    :rows="allAlerts"
                    styleClass="vgt-table striped"
                    :pagination-options="{
                    enabled: allAlerts.length>15,
                    mode:'pages',
                    perPage:15,
                    perPageDropdownEnabled: true,
                    rowsPerPageLabel: $t('recherches_par_page'),
                    ofLabel:'sur',
                    allLabel:$t('tous'),
                    nextLabel: $t('next'),
                    prevLabel:$t('prcdent'),
                    }"
                    @on-selected-rows-change="selectionChanged"
                    :select-options="{
                        enabled: true,
                        selectOnCheckboxOnly: true,
                        selectionText: $t('lments_slectionns'),
                        clearSelectionText: $t('dslectionner_tout'),
                    }"
                    :row-style-class="isActived"
                    :search-options="{
                        enabled: true,
                        externalQuery: search
                        }">

                        <template slot="table-row" slot-scope="props">
                            <template v-if="props.column.field == 'field_researches'">
                                <div v-if="props.row.active==1" class="w-100">
                                    <router-link :to="{name:'ResearchAlertProposalList',params:{id:props.row.id}}" v-if="props.row.researches.length>0" class="btn bg-gold btn-full btn-center px-3">{{ $t('voir_les_recherches') }}</router-link>
                                    <span class="mb-0 d-flex justify-content-center align-items-center py-2" v-else>
                                        <i class="fal fa-hourglass-half mr-2"></i>
                                        {{ $t('en_attente_dune_recherche') }}
                                    </span>
                                </div>
                                <div v-else class="w-100">
                                    <span  class="py-2 d-flex justify-content-center align-items-center text-danger ">

                                        <i class="fal fa-ban pr-1"></i>
                                        {{ $t('annonce_dsactive') }}
                                    </span>

                                    <div class="reactived" v-if="$auth.user().alerts_view == 'table-grid'">
                                        <button class="btn bg-gold btn-full btn-center mr-2" @click="updateActiveState([props.row.id, 1])">
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
                                    <b-dropdown-item v-if="props.row.active===1" @click="updateActiveState([props.row.id, 0])">
                                        <i class="fal fa-ban pr-1"></i>{{ $t('dsactiver') }}
                                    </b-dropdown-item>
                                    <b-dropdown-item v-else @click="updateActiveState([props.row.id, 1])">
                                        <i class="fal fa-check"></i> {{ $t('activer') }}
                                    </b-dropdown-item>
                                    <b-dropdown-divider></b-dropdown-divider>
                                    <b-dropdown-item @click="removal(props.row)"><i class="fal fa-trash"></i> {{ $t('remove') }}
                                    </b-dropdown-item>
                                </b-dropdown>

                            </template>

                            <template v-else-if="props.column.field == 'modele.img'">
                                <img v-if="props.row.modele.img != null" class="little-car" :src="'/images/Cars/'+props.row.modele.img+'.png'">
                                <img v-else :src="'/images/Cars/emptycar.png'" class="little-car">
                            </template>

                            <template v-else-if="props.column.field == 'field_category'">
                                <div class="img-wrapper" v-if="$auth.user().alerts_view == 'table-banner'">
                                    <img v-if="props.row.modele.img != null" class="little-car" :src="'/images/Cars/'+props.row.modele.img+'.png'">
                                    <img v-else :src="'/images/Cars/emptycar.png'" class="little-car">
                                </div>
                                {{ props.row.part ? translation('name', props.row.part.category) : translation('name', props.row.category) }}<template> - {{ props.row.part ? translation('name', props.row.part) : $t('tous_les_produits_de_la_catgorie') }}</template>
                            </template>

                            <template v-else-if="props.column.field == 'brand.name'">
                                {{props.row.brand.name}} {{props.row.modele.name}}
                            </template>

                            <template v-else-if="props.column.field == 'field'">
                                <b-dropdown right size="xs">
                                    <template v-slot:button-content>
                                        <i class="far fa-ellipsis-h fa-lg"></i>
                                    </template>
                                    <b-dropdown-item v-if="props.row.active===1" @click="updateActiveState([props.row.id, 0])">
                                        <i class="fal fa-ban pr-1"></i>{{ $t('dsactiver') }}
                                    </b-dropdown-item>
                                    <b-dropdown-item v-else @click="updateActiveState([props.row.id, 1])">
                                        <i class="fal fa-check"></i> {{ $t('activer') }}
                                    </b-dropdown-item>
                                    <b-dropdown-divider></b-dropdown-divider>
                                    <b-dropdown-item @click="removal(props.row)"><i class="fal fa-trash"></i> {{ $t('remove') }}
                                    </b-dropdown-item>
                                </b-dropdown>
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

        <div class="container" v-else-if="isAlertsLoaded">
            <div class="row">
                <div class="col-md-6 offset-md-3 d-flex align-items-center justify-content-center flex-column empty-zone">
                    <img class="w-100 mb-5" :src="'/images/illustrations/alertes.svg'">
                    <p class="lead text-blue text-center">{{ $t('aucune_annonces_simplifies') }}</p>
                    <p class="text-center px-5">{{ $t('vous_souhaitez_vendre_des_produits_indiqueznous_si') }} </p>
                    <router-link class="btn bg-gold btn-sm mt-3" :to="{ name: 'addAlert' }">
                        {{ $t('crer_mes_annonces_simplifies') }}
                    </router-link>
                </div>
            </div>
        </div>
        <div v-else><span class="loader"></span></div>

    </div>
</template>
<script>
    import mxView from "../../mixins/View";

    const ResearchAlertItem = () => import('./ResearchAlertItem');
    const TopNav = () => import(/* webpackChunkName: "group-dashboard" */"../Shared/TopNav");
    import {mapActions, mapGetters} from "vuex";

    export default {
        components: {ResearchAlertItem, TopNav},
        mixins:[mxView],

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
                search:'',
                columns:[
                    {
                        label:this.$t('image'),
                        field:'modele.img',
                        thClass: 'img-wrapper',
                        tdClass: 'img-wrapper banner-none',
                    },
                    {
                        label:this.$t('catgorie'),
                        field: 'field_category',
                        tdClass: 'font-weight-bold title',
                    },
                    {
                        label:this.$t('product'),
                        field:(obj)=>{
                            if (obj.part)return this.translation('name', obj.part)
                            else return this.$t('tous_les_produits_de_la_catgorie');
                        },
                        tdClass: 'grid-none banner-none',
                    },
                    {
                        label:this.$t('marque'),
                        field:'brand.name',
                        tdClass: 'marque',
                    },
                    {
                        label:this.$t('modle'),
                        field:'modele.name',
                        tdClass: 'grid-none banner-none',
                    },
                    {
                        label:this.$t('recherches'),
                        field:'field_researches',
                        sortable:false,
                        tdClass: 'recherches d-flex',
                    },
                    {
                        label:this.$t('dition'),
                        field:'field',
                        sortable:false,
                        tdClass: 'action banner-none text-center',
                    }
                ],
            }
        },
        created() {
            this.fetchAllAlerts();
        },
        methods: {
            ...mapActions('alerts', ['fetchAllAlerts','updateActiveState', 'batchRemoveAlerts']),
            deleteAlert: function (alert) {
                this.$store.dispatch('alerts/removeAlert', alert)
            },
            removal(alert) {
                const h = this.$createElement;
                const body = [h('div', {class: ['position-relative', 'text-blue']}, [
                    h('h3', {class: 'text-center'}, [
                        this.$t('sure_supprimer_cette_annonce_simplifie')
                    ])
                ]), h('p', {class: 'h3'}, [])];
                this.$removalConfirmation(body, null).then(removal => removal ? this.deleteAlert(alert) : null)
            },
            isActived(row){
               return row.active ? '' : 'disabled';
            },
            selectionChanged(e) {
                const selectedRows = e.selectedRows
                this.selectedRows = selectedRows.map(el => el.id)
            },
            batchRemove() {
                const h = this.$createElement;
                let body=[];
                if(this.selectedRows.length>1){
                    body = [h('div', {class: ['position-relative', 'text-blue']}, [
                        h('h3', {class: 'text-center'}, [
                            this.$t('sure_supprimer_ces_annonces_simplifies')
                        ])
                    ]), h('p', {class: 'h3'}, [])];
                }else{
                     body = [h('div', {class: ['position-relative', 'text-blue']}, [
                        h('h3', {class: 'text-center'}, [
                            this.$t('sure_supprimer_cette_annonce_simplifie')
                        ])
                    ]), h('p', {class: 'h3'}, [])];
                }
                this.$removalConfirmation(body, null).then(removal => removal ? this.batchRemoveAlerts(this.selectedRows) : null)
            }
        },
        computed: {
            ...mapGetters('alerts', ['allAlerts', 'isAlertsLoaded']),
            allAlertsFiltered(){
                return this.allAlerts.filter(a=>{
                    return (
                        a.brand.name.toLowerCase().includes(this.search.toLowerCase())||
                        a.modele.name.toLowerCase().includes(this.search.toLowerCase())||
                        (a.category&& this.translation('name', a.category).toLowerCase().includes(this.search.toLowerCase()))||
                        (a.part&&this.translation('name',a.part.category).toLowerCase().includes(this.search.toLowerCase()))||
                        (a.part&&this.translation('name', a.part).toLowerCase().includes(this.search.toLowerCase()))
                    )
                })
            }
        }
    }
</script>
