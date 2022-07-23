<template>
    <div class="research-list">

        <div class="bg-white shadow container-fluid py-lg-4 py-3 px-4 mb-4">

            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="h2 text-blue mb-0 pt-lg-3 pt-2">{{ $t('my_researches') }}</h1>
                    <span v-if="isResearchLoaded&&total>0">{{ $t('recherches_avec_produits_trouvs', {"done": done, "total": total}) }}</span>
                </div>

                <top-nav></top-nav>
            </div>



        </div>

        <div class="container-fluid" v-if="allResearches.length !== 0 && isResearchLoaded">
            <!--            <div class="d-flex justify-content-between pb-4 px-3">-->

            <!--                <autocomplete :search="search" :get-result-value="getResultValue" placeholder="Nom, modèle ou pièce" aria-label="Nom, modèle ou pièce" @submit="handleSubmit"></autocomplete>-->

            <!--                <span class="form-sm"><label for="filter"></label>-->
            <!--					  <select id="filter" v-model="filter">-->
            <!--							<option value="">Toutes les recherches</option>-->
            <!--							<option value="finished">Recherches terminées</option>-->
            <!--							<option value="in_progress">Recherches en cours</option>-->
            <!--							<option value="archived">Recherches archivées</option>-->
            <!--					  </select>-->
            <!--				 </span>-->
            <!--            </div>-->

            <section v-if="errored">
                <p>{{ $t('nous_sommes_dsols_nous_ne_sommes_pas_en_mesure_de') }}</p>
            </section>
            <section v-else class="container-fluid">
                <div class="row">
                    <!--                    <div class="col-12 col-md-6 col-lg-4 col-xl-4 col-xxl-3 pb-4" v-for="research in allResearches" v-if="filter===''||research.status===filter">-->
                    <!--                        <ResearchItem :research="research"></ResearchItem>-->
                    <!--                    </div>-->

                    <div class="table-wrapper" :class="$auth.user().researches_view || 'table-grid'">

                        <div class="filters pb-4 pt-2">
                            <div class="research w-50">
                                <i class="fal fa-search"></i>
                                <input type="text" class="form-control w-100" :placeholder="$t('rechercher_par_modle_marque_produit_recherch')" v-model="search">
                            </div>
                            <div class="view-mode">
                                <div class="view-item">
                                    <input @change="setView($event,'researches_view')" type="radio" name="view-mode" id="table-grid" :value="'table-grid'" :checked="$auth.user().researches_view == 'table-grid' || $auth.user().researches_view == null" class="d-none">
                                    <label for="table-grid" v-b-tooltip.hover :title="$t('grid_display')"><i class="fal fa-grip-horizontal"></i></label>
                                </div>
                                <div class="view-item">
                                    <input @change="setView($event,'researches_view')" type="radio" name="view-mode" id="table-banner" :value="'table-banner'" :checked="$auth.user().researches_view == 'table-banner'" class="d-none">
                                    <label for="table-banner" v-b-tooltip.hover :title="$t('line_display')"><i class="fal fa-bars"></i></label>
                                </div>
                                <div class="view-item">
                                    <input @change="setView($event,'researches_view')" type="radio" name="view-mode" id="table-base" :value="'table-base'" :checked="$auth.user().researches_view == 'table-base'" class="d-none">
                                    <label for="table-base" v-b-tooltip.hover :title="$t('table_display')"><i class="fal fa-table"></i></label>
                                </div>
                            </div>
                        </div>

                        <vue-good-table
                            :columns="columns"
                            :rows="allResearches"
                            @on-row-click="details"
                            styleClass="vgt-table striped"
                            :pagination-options="{
                            enabled: allResearches.length>15,
                            mode:'pages',
                            perPage:15,
                            perPageDropdownEnabled: true,
                            rowsPerPageLabel: $t('recherches_par_page'),
                            ofLabel:'sur',
                            allLabel:$t('tous'),
                            nextLabel:$t('suivant'),
                            prevLabel:$t('prcdent'),
                        }"
                            :search-options="{
                        enabled: true,
                        externalQuery: search
                        }">
                            <template slot="table-row" slot-scope="props">

                                <template v-if="props.column.field == 'modele.modele.img'">
                                    <img v-if="props.row.modele.modele.img" class="little-car" :src="'/images/Cars/'+props.row.modele.modele.img+'.png'">
                                    <img v-else :src="'/images/Cars/emptycar.png'" class="little-car">
                                </template>

                                <template v-else-if="props.column.label == 'Produit recherché'">

                                    <div class="img-wrapper" v-if="$auth.user().researches_view == 'table-banner'">
                                        <img v-if="props.row.modele.modele.img" class="little-car" :src="'/images/Cars/'+props.row.modele.modele.img+'.png'">
                                        <img v-else :src="'/images/Cars/emptycar.png'" class="little-car">
                                    </div>

                                    {{props.row.part ? translation('name', props.row.part) : props.row.unknown_part ? $t('suggested_product') + ' : ' + props.row.unknown_part.message : ''}}

                                </template>

                                <template v-else-if="props.column.field == 'modele.modele.brand.name'">
                                    {{ props.row.modele.modele.brand.name }}
                                    <span class="base-none">
                                        {{ props.row.modele.modele.name }}
                                    </span>

                                </template>
                                <template v-else-if="props.column.field == 'action'">
                                    <i class="fal fa-trash-alt text-danger pt-1" @click.prevent="remove(props.row)"></i>
                                </template>

                                <template v-else-if="props.column.field == 'status'">
                                    <div v-if="!props.row.status" class="item">
                                        <p class="mb-2">{{ $t('research_awaiting_validation') }}</p>
                                        <div class="progress">
                                            <div class="progress-bar w-25" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div v-if="props.row.status === 'in_progress'" class="item">
                                        <p class="mb-2">{{ $t('nos_experts_sont_en_cours_de_recherche') }}</p>
                                        <div class="progress">
                                            <div class="progress-bar" :class="props.row.products.length!==0 ? 'w-75' : 'w-50' " role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div v-if="props.row.status === 'archived'" class="item">
                                        <p class="mb-2">{{ $t('la_recherche_a_t_archive') }}</p>
                                        <div class="progress">
                                            <div class="progress-bar w-0" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div v-if="props.row.status === 'finished'" class="item">
                                        <p class="mb-2">{{ $t('recherche_termine') }}</p>
                                        <div class="progress">
                                            <div class="progress-bar w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div v-if="props.row.products.length==0" class="item">
                                        <span class="btn btn-sm btn-full btn-center bg-dark-grey mt-3">
                                            {{ $t('dtails_de_la_recherche') }}
                                        </span>
                                    </div>
                                    <div v-if="props.row.status === 'in_progress'&&props.row.products.length!==0" class="item">
                                        <span class="btn btn-sm btn-full btn-center bg-gold mt-3">
                                            {{ $tc('product_found', props.row.products.length, {"length": props.row.products.length}) }} !
                                        </span>
                                    </div>
                                </template>

                            </template>
                            <div slot="emptystate">
                                {{ $t('pas_de_recherche') }}
                            </div>
                        </vue-good-table>
                    </div>


                    <!--                    <div class="col-12 mb-5">-->
                    <!--                        <new-research :select="true"/>-->
                    <!--                    </div>-->
                </div>
            </section>
        </div>

        <div class="container" v-else-if="isResearchLoaded">
            <div class="row">
                <div class="col-md-6 offset-md-3 d-flex align-items-center justify-content-center flex-column empty-zone">
                    <i class="fal fa-search text-gold fa-7x mb-3"></i>
                    <p class="lead text-blue text-center">{{ $t('aucune_recherche') }}</p>
                    <p class="text-center px-5">{{ $t('vous_souhaitez_acheter_des_pices_pour_votre_ancien') }}</p>
                    <router-link class="btn bg-gold btn-sm mt-3" :to="{ name: 'searchCarModel' }">
                        {{ $t('faire_une_nouvelle_recherche') }}
                    </router-link>
                </div>
            </div>
        </div>

        <div v-else><span class="loader"></span></div>

    </div>
</template>

<script>
import mxView from "../../../mixins/View";

const ResearchItem = () => import(/* webpackChunkName: "group-finder" */'./ResearchItem');
const NewResearch = () => import(/* webpackChunkName: "group-dashboard" */"../Dashboard/NewResearch");
const TopNav = () => import(/* webpackChunkName: "group-dashboard" */"../../Shared/TopNav");
import {mapActions, mapGetters} from 'vuex';

export default {
    name: 'ResearchList',
    components: {ResearchItem, NewResearch,TopNav},
    mixins: [mxView],
    data() {
        return {
            errored: false,
            page: 0,
            filter: '',
            search: '',
            columns: [
                {
                    label: this.$t('image'),
                    field: 'modele.modele.img',
                    thClass: 'img-wrapper',
                    tdClass: 'img-wrapper banner-none',
                    filterOptions: {
                        styleClass: 'img-wrapper'
                    },
                },
                {
                    label: this.$t('produit_recherch'),
                    field: (research) => {
                        return research.part ? this.translation('name', research.part) : research.unknown_part ? this.$t('pice_suggre') +' : '+ research.unknown_part.message : '';
                    },
                    tdClass: 'font-weight-bold title',
                },
                {
                    label: this.$t('marque'),
                    field: 'modele.modele.brand.name',
                    tdClass: 'marque',
                },
                {
                    label: this.$t('modle'),
                    field: 'modele.modele.name',
                    tdClass: 'modele grid-none banner-none',
                },
                {
                    label: this.$t('year'),
                    field: 'modele.year',
                    type: 'number',
                    thClass: 'text-left',
                    tdClass: 'text-left year grid-none banner-none',
                },
                {
                    label: this.$t('action'),
                    field: 'status',
                    tdClass: 'status',
                }, {
                    label: '',
                    field: 'action',
                    sortable:false,
                    tdClass: 'deletion text-center',
                }
            ],
        }
    },
    created() {
        this.fetchAllResearches();
    },
    methods: {
        details(row) {
            if(!row.event.target.classList.contains('fa-trash-alt'))this.$router.push({name: 'researchProposalList', params: {id: row.row.id}}).catch(() => {})
        },
        // async search(input) {
        //     if (input.length < 1) {
        //         return []
        //     }
        //     return this.allResearches.filter(research => {
        //         return research.part.translation.toLowerCase()
        //             .startsWith(input.toLowerCase()) || research.modele.car_name.toLowerCase()
        //             .startsWith(input.toLowerCase()) || research.modele.modele.brand.name.toLowerCase()
        //             .startsWith(input.toLowerCase())
        //     })
        // },
        getResultValue(result) {
            if (result.modele.car_name) return this.translation('name', result.part) + ': ' + result.modele.car_name + '(' + result.modele.modele.brand.name + ' ' + result.modele.modele.name + ')';
            else return result.modele.modele.brand.name + ' ' + result.modele.modele.name;
        },
        handleSubmit(result) {
            this.$router.push({name: 'researchProposalList', params: {id: result.id}}).catch(() => {});
        },
        remove(research) {
            const h = this.$createElement;
            const body = [h('div', {class: ['position-relative', 'text-blue']}, [
                h('h3', {class: 'text-center'}, [
                    this.$t('sure_supprimer_research')
                ])
            ]), h('p', {class: 'h3'}, [
            ])];
            this.$removalConfirmation(body,null).then((removal) => removal ? this.removeResearch(research) : null)
        },
        ...mapActions('researches', ['fetchAllResearches','removeResearch']),
    },
    computed: {
        ...mapGetters('researches', ['allResearches', 'total', 'done', 'isResearchLoaded']),
    }
}
</script>
