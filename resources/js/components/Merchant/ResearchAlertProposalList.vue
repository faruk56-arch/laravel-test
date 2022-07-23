<template>
    <div class="research-alert-proposal-list">

        <div class="bg-white shadow container-fluid py-lg-4 py-3 px-4 mb-4">

            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex pt-lg-3 pt-2">
                    <a class="cursor-p pl-1 pr-3" @click="$router.go(-1)">
                    <i class="fal fa-2x fa-arrow-left"></i></a>
                    <div>
                        <h1 class="h2 d-inline-block text-blue mb-0">{{ $t('rsultats_de_mon_annonce') }}</h1>
                        <p class="mb-0 text-blue" v-if="alert">
                            {{alert.category? translation('name', alert.category) :''}}
                                {{alert.part?translation('name', alert.part):''}}
                            {{ $t('pour') }} {{alert.brand.name}} {{alert.modele.name}}

                            <span v-b-modal.modal-information class="text-secondary">
                                <i class="fal text-secondary fa-info-circle ml-3"></i>
                                <u>
                                    {{ $t('dtails_de_votre_annonce_simplifie') }}
                                </u>
                            </span>
                        </p>
                    </div>
                </div>
                <top-nav></top-nav>
            </div>

        </div>

        <b-modal :id="'modal-information'" hide-footer centered :title="$t('dtails_de_lannonce_simplifie')" content-class="rounded border-0 shadow-lg" v-if="alert">

            <img v-if="alert.modele.img" class="little-car" :src="'/images/Cars/'+alert.modele.img+'.png'" @error="alert.modele.img=null">
            <img v-else :src="'/images/Cars/emptycar.png'" class="little-car">

            <table class="table text-blue table-striped py-3 mt-3">
                <tbody>
                    <tr>
                        <td class="border-0">
                            <div>
                                <strong>{{ $t('vehicle') }}</strong><br>
                                {{alert.brand.name}} {{alert.modele.name}}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-0" v-if="alert.part">
                            <strong>{{ $t('pice_cible') }}</strong><br>
                            {{translation('name', alert.part)}}
                        </td>
                        <td class="border-0" v-if="alert.category">
                            <strong>{{ $t('catgorie_cible') }}</strong><br>
                            {{ translation('name', alert.category) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border-0">
                            <strong>{{ $t('date_de_publication') }} </strong>{{alert.created_at}}
                        </td>
                    </tr>
                </tbody>
            </table>

        </b-modal>

        <div v-if="alert&&!errored" class="container-fluid position-relative overflow-hidden h-100 max-1000">

            <div class="d-flex justify-content-between align-items-center py-4 text-blue">
                <div>
                    <div class="col-12">
                        <h3 class="h3-serif mb-1">{{ $t('vos_acheteurs_potentiels') }}</h3>
                        {{alert.researches.length}} {{ $tc('acheteur', alert.researches.length) }}
                    </div>
                </div>
                <div class="filters">
                    <div class="view-mode mx-2">
                        <div class="view-item">
                            <input @change="setView($event,'alerts_match_view')" type="radio" name="view-mode" id="table-grid" :value="'table-grid'" :checked="$auth.user().alerts_match_view == 'table-grid' || $auth.user().alerts_match_view == null" class="d-none">
                            <label for="table-grid" v-b-tooltip.hover :title="$t('grid_display')"><i class="fal fa-grip-horizontal"></i></label>
                        </div>
                        <div class="view-item">
                            <input @change="setView($event,'alerts_match_view')" type="radio" name="view-mode" id="table-banner" :value="'table-banner'" :checked="$auth.user().alerts_match_view == 'table-banner'" class="d-none">
                            <label for="table-banner" v-b-tooltip.hover :title="$t('line_display')"><i class="fal fa-bars"></i></label>
                        </div>
                        <div class="view-item">
                            <input @change="setView($event,'alerts_match_view')" type="radio" name="view-mode" id="table-base" :value="'table-base'" :checked="$auth.user().alerts_match_view == 'table-base'" class="d-none">
                            <label for="table-base" v-b-tooltip.hover :title="$t('table_display')"><i class="fal fa-table"></i></label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-wrapper" :class="$auth.user().alerts_match_view || 'table-banner'">

                <vue-good-table
                    :columns="columns"
                    :rows="filtered"
                    styleClass="vgt-table striped"
                    :row-style-class="isActived"
                    :pagination-options="{
                        enabled: alert.researches.length>15,
                        mode:'pages',
                        perPage:15,
                        perPageDropdownEnabled: true,
                        rowsPerPageLabel: $t('recherches_par_page'),
                        ofLabel:'sur',
                        allLabel:$t('tous'),
                        nextLabel:$t('suivant'),
                        prevLabel:$t('prcdent'),
                    }"
                >
                    <template slot="table-row" slot-scope="props">
                        <template v-if="props.column.field == 'action'">
                                <button @click="openModal(props.row.originalIndex)" :class="{'mt-3' : $auth.user().alerts_match_view == 'table-grid', 'base-none': isDismissed(props.row.id) != null} " class="btn bg-gold btn-sm btn-full px-3 btn-center text-white hide-disabled action">{{ $t('dtails_de_la_recherche') }}</button>
                                <template v-if="isDismissed(props.row.id) === 1">
                                   <div class="bg-danger text-white px-3 py-2 lead refused">
                                        {{ $t('je_nai_pas_ce_produit2') }}
                                    </div>

                                    <div class="unfreeze">
                                        <p class="mb-1 text-blue">{{ $t('vous_avez_le_produit') }}</p>
                                        <button class="btn btn-sm btn-full btn-center btn-blue" @click="toggleResearchDismiss([alert.id, props.row.id,null])">
                                            {{ $t('jai_ce_produit') }}
                                        </button>
                                    </div>
                                </template>
                                <template v-else-if="isDismissed(props.row.id) == 0">
                                    <div class="bg-success text-white px-3 py-2 lead refused">
                                        {{ $t('jai_trait_ce_produit2') }}
                                    </div>

                                    <div class="unfreeze">
                                        <p class="mb-1 text-blue">{{ $t('vous_avez_chang_davis2') }}</p>
                                        <button class="btn btn-sm btn-full btn-center btn-blue" @click="toggleResearchDismiss([alert.id, props.row.id, 1])">
                                            {{ $t('je_nai_pas_le_produit') }}
                                        </button>
                                    </div>

                                </template>
                        </template>

                        <template v-else-if="props.column.field == 'created_at'">
                            <p class="mb-0 font-weight-bold" v-if="$auth.user().alerts_match_view != 'table-base'">{{ $t('date_de_la_recherche2') }}</p>
                            {{ props.row.created_at}}
                        </template>

                        <template v-else-if="props.column.field == 'part.category.translation'">
                            <p class="mb-0 font-weight-bold">{{ translation('name', props.row.part.category) }}</p>
                            <template v-if="$auth.user().alerts_match_view != 'table-base'">
                                {{ translation('name', props.row.part) }}
                            </template>
                        </template>
                        <template v-else-if="props.column.field === 'part.translation'">
                            <span>{{ translation('name', props.row.part) }}</span>
                        </template>

                        <template v-else-if="props.column.label == 'Status'">
                            <template v-if="isDismissed(props.row.id) == null">
                                <span class="badge badge-secondary">{{ $t('en_attente_de_traitement') }}</span>
                            </template>
                            <template v-if="isDismissed(props.row.id) == 0">
                                <span class="badge badge-success text-white">{{ $t('jai_trait_ce_produit') }}</span>
                            </template>
                            <template v-if="isDismissed(props.row.id) == 1">
                                <span class="badge badge-danger">{{ $t('je_nai_pas_ce_produit') }}</span>
                            </template>
                        </template>

                        <template v-else-if="props.column.field == 'user.country.name'">
                            <p class="mb-0 font-weight-bold" v-if="$auth.user().alerts_match_view != 'table-base'">{{ $t('a_propos_de_lacheteur') }}</p>
                            {{ props.row.user.region && props.row.user.region.name }} - {{ props.row.user.country && props.row.user.country.name }}
                            <span :class="'flag-icon flag-icon-'+props.row.user.country.code.toLowerCase()"></span>
                        </template>
                    </template>
                </vue-good-table>

                <template v-for="(research,i) in alert.researches">
                    <ResearchAlertProposalDetails v-click-outside="conf" :key="keyModal+i" :class="{'active' : showDetails[i]}" @showDetails="closeAllModals" @dismiss="toggleResearchDismiss" :alert="alert" :research="research"></ResearchAlertProposalDetails>
                    <b-modal :id="'modal-center'+research.id" centered hide-footer hide-header content-class='border-0 rounded px-3'>
                        <div class="py-4 text-center">
                            <div class="position-relative text-blue">
                                <i class="fal fa-car fa-5x pb-3"></i>
                                <h2 class="text-center">{{ $t('flicitations') }}</h2>
                                <div class="pyro">
                                    <div class="before"></div>
                                    <div class="after"></div>
                                </div>
                            </div>
                            <p class="h3">{{ $t('nous_avons_dsormais_besoin') }} <br>{{ $t('de_quelques_informations_complmentaires_sur_votre') }}
                            </p>

                            <div class="alert alert-info d-flex text-left mb-0 mt-3"><i class="far fa-info-circle mr-2 pt-1"></i> {{ $t('vrifier_que_vous_navez_pas_dj_cette_pice_dans_votr') }}</div>

                            <router-link tag="a" :to="{name:'addProduct',query:{modeleId:alert.modele.id,partId:research.part.id,reference:research.reference,alert:true}}" class="btn bg-gold btn-lg mt-3">{{ $t('complter_la_fiche_produit') }}</router-link>
                        </div>
                    </b-modal>
                </template>

            </div>
        </div>
        <div v-else-if="errored">{{ $t('lannonce_simplifie_nest_pas_disponible') }}</div>
        <div v-else><span class="loader"></span></div>
    </div>
</template>

<script>
    import {status} from "../../mixins/status";

    const ResearchAlertProposalDetails = () => import(/* webpackChunkName: "group-finder" */'./ResearchAlertProposalDetails');

    const TopNav = () => import(/* webpackChunkName: "group-dashboard" */"../Shared/TopNav");
    import {mapActions, mapGetters} from "vuex";
    import mxView from "../../mixins/View";

    export default {
        name: 'ResearchAlertProposalList',
        components: {ResearchAlertProposalDetails,TopNav},
        mixins: [status, mxView],
        data() {
            return {
                alert: null,
                errored:false,
                showDetails: [],
                keyModal:0,
                filtered:null,
                key:0,
                columns: [
                    {
                        label: this.$t('date_de_la_recherche2'),
                        field:'created_at',
                        tdClass: 'hide-disabled',
                    },
                    {
                        label: this.$t('catgorie'),
                        field:'part.category.translation',
                        tdClass: 'hide-disabled',
                    },
                    {
                        label: this.$t('part'),
                        field:'part.translation',
                        tdClass: 'grid-none banner-none hide-disabled',
                    },{
                        label: this.$t('rfrence_de_la_pice'),
                        field:'reference',
                        tdClass: 'grid-none banner-none',
                    },
                    {
                        label: this.$t('localisation_de_lacheteur'),
                        field:'user.country.name',
                        tdClass: 'hide-disabled',
                    },{
                        label: this.$t('status'),
                        field:this.status,
                        formatFn:(bool)=>{
                            switch(bool){
                                case null:{
                                    return 'En attente de traitement';
                                }
                                case 0:{
                                    return 'Produit créé';
                                }
                                case 1:{
                                    return 'Pas de produit';
                                }
                            }
                        },
                        type:'number',
                        tdClass: 'grid-none banner-none',
                        thClass: 'grid-none banner-none',
                    },
                    {
                        label:'',
                        field:'action',
                        sortable:false,
                    },
                ],
            }
        },
        created() {
            this.fetchAllAlerts();
            this.fetchAlert(this.$route.params.id).then(res => {
                this.alert = res.data
                this.alert.researches.forEach(res => {
                    this.showDetails.push(false);
                })
                this.filtered = this.alert.researches.sort((a,b)=>{
                    if(a.pivot.dismiss==1&&b.pivot.dismiss==1)return 0;
                    if(a.pivot.dismiss!=1&&b.pivot.dismiss==1)return -1;
                    if(a.pivot.dismiss==1&&b.pivot.dismiss!=1)return 1;
                })
            }).catch(err=>this.errored = true)
        },
        methods: {
            ...mapActions('alerts', ['toggleResearchDismiss']),
            ...mapActions('alerts', ['fetchAllAlerts', 'fetchAlert']),
            findUpTag(el, tag) {
                while (el.parentNode) {
                    el = el.parentNode;
                    if (el.className&&el.className.includes(tag))
                        return el;
                }
                return null;
            },
            middleware(event) {
                // const className = event.target.className
                // const tagName = event.target.tagName
                // return className !== 'btn btn-sm btn-full btn-center bg-gold' &&
                //     className !== 'modal fade' && className !== 'modal-title' && className !== 'modal-body' &&
                //     className !== 'cool-lightbox__inner' && className !== 'close' &&
                //     className !== 'cool-lightbox-toolbar__btn' && className !== 'disabled btn btn-sm bg-blue mt-3' &&
                //     className !== 'cool-lightbox__slide__img loaded' && className !== 'alert alert-success' &&
                //     className !== 'cool-lightbox__thumb active' && className !== 'modal-content rounded border-0 shadow-lg' &&
                //     tagName !== 'svg' && tagName !== 'HEADER' &&
                //     tagName !== 'path' && tagName !== 'FORM' &&
                //     tagName !== 'rect' && tagName !== 'INPUT' &&
                //     (tagName !== 'IMG' || (tagName == 'IMG' && className !== '')) &&
                //     (tagName !== 'LABEL' || (tagName == 'LABEL' && className !== ''))
                return ((this.findUpTag(event.target, 'research-proposal-details')==null
                    &&this.findUpTag(event.target, 'cool-lightbox')==null&&this.findUpTag(event.target, 'modal-content')==null)||
                    event.target.className.includes('far fa-chevron-right')||
                    event.target.className.includes('close-details'))&&!event.target.className.includes('btn btn-md btn-outline-danger d-flex align-items-center');
            },
            handler(event) {
                this.closeAllModals()
            },
            openModal(i){
                this.closeAllModals(i)
                this.$set(this.showDetails,i,true);
            },
            isActived(row){
               return this.isDismissed(row.id)===0||this.isDismissed(row.id)===1 ? 'disabled' : '';
            },
            isDismissed(id) {
                if(this.alert){
                    return this.isDismissedFunc(this.alert.id, id)
                }
            },
            closeAllModals(index) {
                let allModal = document.querySelectorAll('.research-proposal-details.active')
                for (var i = 0; i < allModal.length; i++) {
                    allModal[i].classList.remove('active');
                }
                for (let i = this.showDetails.length - 1; i >= 0; i--) {
                    this.$set(this.showDetails,i,false);
                }
            },
            status(obj){
                    return this.isDismissed(obj.id)
            }
        },
        computed: {
            ...mapGetters('alerts', ['allAlerts', 'isAlertsLoaded','isDismissedFunc', 'isAlertsLoaded']),
            conf(){
                let bool=null;
                if (this.showDetails.find(el=>el===true))bool = true;
                else bool = false
                return {
                    handler: this.handler,
                    middleware: this.middleware,
                    isActive: bool,
                }
            }
        }
    }

</script>
