<template>
    <div class="research-proposal-list">

        <div class="bg-white shadow container-fluid py-lg-4 py-3 px-4 mb-4">

            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex pt-lg-3 pt-2">
                    <a class="cursor-p pl-1 pr-3" @click="$router.go(-1)">
                    <i class="fal fa-2x fa-arrow-left"></i></a>
                    <div>
                        <h1 class="h2 d-inline-block text-blue mb-0">{{ $t('my_researches') }}</h1>
                        <p class="mb-0 text-blue" v-if="research">
                            {{ research&&research.part && translation('name', research.part) || $t('product_suggested')+research&&research.unknown_part.message }} {{ $t('pour') }} {{
                                research.modele.year ? research.modele.modele.brand.name + ' ' + research.modele.modele.name + ' ' + research.modele.version+ ' (' + research.modele.year + ')' :
                                    research.modele.modele.brand.name + ' ' + research.modele.modele.name + ' ' + research.modele.version?research.modele.version:'' + ' (' + research.modele.modele.year_start + '-' + research.modele.modele.year_end + ')'
                            }}
                            <span v-b-modal.modal-information class="text-secondary" v-b-tooltip.hover.bottom>
                                <i class="fal text-secondary fa-info-circle ml-3"></i>
                                <u>
                                    {{ $t('dtails_de_votre_recherche') }}
                                </u>
                            </span>

                        </p>
                    </div>
                </div>
                <top-nav></top-nav>
            </div>

            <modal-state></modal-state>
            <modal-origin></modal-origin>

        </div>

        <b-modal :id="'modal-information'" hide-footer centered :title="$t('dtails_de_votre_recherche')" content-class="rounded border-0 shadow-lg">

            <img v-if="research&&research.modele.modele.img" :src="'/images/Cars/'+research.modele.modele.img+'.png'" class="little-car" @error="research.modele.modele.img=null">
            <img v-else :src="'/images/Cars/emptycar.png'" class="little-car">

            <table class="table text-blue table-striped py-3 mt-3" v-if="research">
                <tbody>
                    <tr>
                        <td class="border-0">
                            <div>
                                <strong>{{ $t('vehicle') }}</strong><br>
                                {{
                                    research.modele.year ? research.modele.modele.brand.name + ' ' + research.modele.modele.name + ' (' + research.modele.year + ')' :
                                        research.modele.modele.brand.name + ' ' + research.modele.modele.name + ' (' + research.modele.modele.year_start + '-' + research.modele.modele.year_end + ')'
                                }}
                                {{ research.modele.version ? research.modele.version : '' }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-0">
                            <strong>{{ $t('produit_recherch') }}</strong><br>
                            {{ research&&research.part && translation('name', research.part) || 'Produit suggéré : '+research&&research.unknown_part.message }}
                            <span v-if="research.reference">(Réf. : {{ research.reference }})</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-0">
                            <strong>{{ $t('type_de_produit') }}</strong><br>
                            <p class="mb-0" v-if="research.type">{{ getStatusText(research.type) }}</p>
                            <ul class="pl-3 mb-0" v-else>
                                <li v-for="type in research.types">
                                    {{ getStatusText(type) }}
                                </li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-0" v-if="translation('details', research)" style="white-space: pre-line;">
                            <strong>{{ $t('dtails') }} </strong>{{ translation('details', research) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border-0" v-if="research.reference">
                            <strong>{{ $t('rfrence_du_produit2') }} </strong>{{ research.reference }}
                        </td>
                    </tr>
                    <tr v-if="research.created_at">
                        <td class="border-0">
                            <strong>{{ $t('date_de_la_recherche') }} </strong>{{ research.created_at }}
                        </td>
                    </tr>
                    <tr v-if="research.img != 0">
                        <td class="border-0">

                            <div class="img-wrapper">
                                <div v-if="research.img.length == 1" @click="zoom(0)" :key="0" class="h-100">
                                    <zoom-on-hover class="h-100 w-100" :img-normal="research.img[0]" :scale="1.2"></zoom-on-hover>
                                </div>

                                <b-carousel
                                    v-model="slide"
                                    :interval="0"
                                    controls
                                    indicators
                                    background="#ababab"
                                    class="h-100"
                                    img-width="1024"
                                    img-height="100%"
                                    @sliding-start="onSlideStart"
                                    @sliding-end="onSlideEnd"
                                    v-else
                                >

                                    <b-carousel-slide v-for="(i, imageIndex) in research.img" :key="imageIndex">
                                        <template v-slot:img>
                                            <div class="h-100" @click="zoom(imageIndex)" :key="imageIndex">
                                                <zoom-on-hover class="h-100 w-100" :img-normal="i" :scale="1.2"></zoom-on-hover>
                                            </div>
                                        </template>
                                    </b-carousel-slide>

                                </b-carousel>
                            </div>

<!--
                            <ul class="list-unstyled d-flex flex-wrap">
                                <li class="img-wrapper" v-for="img in research.img">
                                    <img class="w-100" :src="img">
                                </li>
                            </ul> -->
                        </td>
                    </tr>
                </tbody>
            </table>

        </b-modal>

        <div class="container-fluid position-relative overflow-hidden h-100 max-1000" v-if="isResearchLoaded && research &&!errored">

            <div class="d-flex justify-content-between py-4 text-blue">
                <div>
                    <div class="col-12" v-if="research.status=='finished'">
                        <h3 class="h3-serif">{{ $t('la_recherche_est_termine') }}</h3>
                    </div>
                    <div class="col-12" v-else-if="research.status=='archived'">
                        <h3 class="h3-serif">{{ $t('la_recherche_a_t_archive') }}</h3>
                    </div>
                    <div class="col-12" v-else-if="size>0">
                        <h3 class="h3-serif">{{ $t('les_trouvailles') }}</h3>
                        {{ $tc('research_continues', size, {"size": size}) }}
                    </div>
                    <div class="col-12" v-else-if="research.status=='in_progress'">
                        <h3 class="h3-serif">{{ $t('nos_experts_sont_en_cours_de_recherche') }}</h3>
                    </div>
                    <div class="col-12" v-else-if="!research.status">
                        <h3 class="h3-serif">{{ $t('la_recherche_est_en_attente_de_validation') }}</h3>
                    </div>
                </div>

                <div class="filters pt-2" v-if="size>0">
                    <div class="view-mode mx-2">
                        <div class="view-item">
                            <input @change="setView($event,'matchs_view')" type="radio" name="view-mode" id="table-grid" :value="'table-grid'" :checked="$auth.user().matchs_view == 'table-grid' || $auth.user().matchs_view == null" class="d-none">
                            <label for="table-grid" v-b-tooltip.hover :title="$t('grid_display')"><i class="fal fa-grip-horizontal"></i></label>
                        </div>
                        <div class="view-item">
                            <input @change="setView($event,'matchs_view')" type="radio" name="view-mode" id="table-banner" :value="'table-banner'" :checked="$auth.user().matchs_view == 'table-banner'" class="d-none">
                            <label for="table-banner" v-b-tooltip.hover :title="$t('line_display')"><i class="fal fa-bars"></i></label>
                        </div>
                        <div class="view-item">
                            <input @change="setView($event,'matchs_view')" type="radio" name="view-mode" id="table-base" :value="'table-base'" :checked="$auth.user().matchs_view == 'table-base'" class="d-none">
                            <label for="table-base" v-b-tooltip.hover :title="$t('table_display')"><i class="fal fa-table"></i></label>
                        </div>
                    </div>
                </div>

            </div>

            <div class="table-wrapper" v-if="research.status=='in_progress'" :class="$auth.user().matchs_view || 'table-grid'">

                <vue-good-table
                    :columns="columns"
                    :rows="filtered"
                    styleClass="vgt-table striped"
                    :row-style-class="isActived"
                    :pagination-options="{
                        enabled: research.products.length>15,
                        mode:'pages',
                        perPage:15,
                        perPageDropdownEnabled: true,
                        rowsPerPageLabel: $t('trouvailles_par_page'),
                        ofLabel:'sur',
                        allLabel:$t('tous'),
                        nextLabel:$t('suivant'),
                        prevLabel:$t('prcdent'),
                    }">
<!--                    :sort-options="{-->
<!--                        enabled: true,-->
<!--                        initialSortBy: {field: status, type: 'asc'}-->
<!--                      }"-->

                    <template slot="table-row" slot-scope="props">

                        <template v-if="props.column.field == 'name'">
                            <div class="img-wrapper base-none">
                                <div class="img" :style="'background-image: url('+props.row.img[0]+');' "></div>
                            </div>
                            <div>
                                {{ translation('name', props.row) }}
                                <span v-if="$auth.user().matchs_view == 'table-base'" class="badge badge-danger">{{ $t('offre_refuse') }}</span>
                            </div>
                        </template>


                        <template v-else-if="props.column.label == ''">
                            <div class="d-flex align-items-center action">
                                <a class="btn btn-sm w-100 btn-center bg-gold" @click="openModal(props.row.originalIndex);">{{ $t('consulter_loffre') }}</a>
                            </div>

                            <div class="bg-danger text-white px-3 py-2 lead refused">
                                {{ $t('offre_refuse') }}
                            </div>

                            <div class="unfreeze">
                                <p class="mb-1 text-blue">{{ $t('vous_avez_chang_davis') }}</p>
                                <button class="btn btn-sm btn-full btn-center btn-blue" @click="unfreeze(props.row.originalIndex)">
                                    <img src="/images/picto/unfreeze-icon.svg" class="mr-1">
                                    {{ $t('dgivrer_loffre') }}
                                </button>
                            </div>

                        </template>
                    </template>
                    <div slot="emptystate">
                        {{ $t('pas_encore_de_trouvailles') }}
                    </div>
                </vue-good-table>
                <ResearchProposalDetails v-for="(product,i) in filtered" :key="i" v-click-outside="conf" :index="i" :class="{'active' : showDetails[i]}" @showDetails="openModal(i)" :product="product" @productState="setProductState"></ResearchProposalDetails>


<!--                    <div v-for="(product,i) in research.products" class="my-3 col-12 position-static">-->
<!--                        <ResearchProposalItem :showDetails.sync="showDetails[i]" @closeAllModals="closeAllModals(i)" :product="product"></ResearchProposalItem>-->
<!--                    </div>-->
                <div class="my-4" v-if="research.products.length > 0">

                    <a v-b-modal.not-pieces-suit-me class="basic h4" v-if="size>1">{{ $t('aucun_de_ces_produits_ne_me_conviennent') }}</a>
                    <b-modal hide-footer centered id="not-pieces-suit-me" :title="$t('nous_envoyer_une_suggestion')">
                        <not-found-form :placeholder="$t('je_cherche_des_produits_neufs_les_prix_sont_trop_l')" :label="$t('donnez_nous_plus_de_dtails_sur_le_produit_que_vous')" @submitted="submitted"/>
                    </b-modal>

                </div>

            </div>
        </div>
        <div v-else-if="errored">{{ $t('la_recherche_nest_pas_disponible') }}</div>
        <div v-else><span class="loader"></span></div>

        <b-modal size="lg" id="garantie" hide-footer centered content-class="rounded border-0 shadow-lg" body-class="p-4" header-class="border-0 pb-0">
            <template #modal-title>
                <div class="d-flex align-items-center justify-content-center w-100">
                    <img src="/images/achat-garantie.svg" class="mr-3">
                    <h3 class="mt-2">{{ $t('garantie_classic_parts_finder') }}</h3>
                </div>

            </template>
            <table class="table-striped">
                <tbody>

                    <tr>
                        <td class="px-4 pb-3 pt-3">
                            <p class="mb-0 text-blue"><strong>{{ $t('paiement_par_carte_ou_par_virement') }}</strong> <img class="ml-1" src="/images/logo/logo_obvy_blue.svg"></p>
                        </td>
                    </tr>

                    <tr>
                        <td class="px-4 pb-3 pt-3">
                            <p class="mb-0 text-blue"><strong>{{ $t('garantie_de_transaction_scurise') }}</strong><img class="ml-1" src="/images/logo/logo_obvy_blue.svg"></p>
                        </td>
                    </tr>

                    <tr>
                        <td class="px-4 pb-4 pt-3">
                            <p class="mb-2 text-blue"><strong>{{ $t('garantie_de_paiement_sur_compte_squestre') }}</strong><img class="ml-1" src="/images/logo/logo_obvy_blue.svg"></p>
                            <p class="mb-0">{{ $t('scurisation_des_fonds_protection_de_lacheteur_et_d') }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 pb-3 pt-3">
                            <p class="mb-0 text-blue"><strong>{{ $t('tarifs_de_livraison_ngocis_mondial_relay_et_chrono') }}</strong><img class="ml-1" src="/images/logo/logo_obvy_blue.svg"></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 pb-3 pt-3">
                            <p class="mb-0 text-blue"><strong>{{ $t('suivi_de_livraison_si_choix_via_mondial_relay_ou_c') }}</strong><img class="ml-1" src="/images/logo/logo_obvy_blue.svg"></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 pb-3 pt-3">
                            <p class="mb-0 text-blue"><strong>{{ $t('remboursement_total_ou_partiel_en_cas_de_perte_ou') }}</strong><img class="ml-1" src="/images/logo/logo_obvy_blue.svg"></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 pb-3 pt-3">
                            <p class="mb-0 text-blue"><strong>{{ $t('garantie_lgale_de_conformit_du_bien') }}</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 pb-3 pt-3">
                            <p class="mb-0 text-blue"><strong>{{ $t('garantie_lgale_des_vices_cachs') }}</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 pb-4 pt-3">
                            <p class="mb-2 text-blue"><strong>{{ $t('droit_de_rtractation_de_14_jours') }} </strong><small class="text-danger">{{ $t('si_achat_auprs_dun_professionnel') }}</small></p>
                            <p class="mb-0">{{ $t('larticle_doit_tre_retourn_dans_son_tat_dorigine_et') }}</p>
                        </td>
                    </tr>

                    <tr>
                        <td class="px-4 pb-3 pt-3">
                            <p class="mb-0 text-blue"><strong>{{ $t('assistance_en_cas_de_litige') }}</strong></p>
                            <p class="mb-0">{{ $t('vous_pouvez_nous_faire_votre_demande_via') }} <a href="/litige" target="_blank" class="basic text-gold">{{ $t('ce_formulaire') }}</a></p>
                        </td>
                    </tr>

                </tbody>
            </table>
        </b-modal>

    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";
import {status} from "../../../mixins/status";
import mxView from "../../../mixins/View";
import ModalOrigin from "../../Shared/ModalOrigin";
import ModalState from "../../Shared/ModalState";
const ResearchProposalDetails = () => import(/* webpackChunkName: "group-finder" */'./ResearchProposalDetails');

const NotFoundForm = () => import("../../Shared/NotFoundForm");
const ResearchProposalItem = () => import(/* webpackChunkName: "group-finder" */'./ResearchProposalItem');
const TopNav = () => import(/* webpackChunkName: "group-dashboard" */"../../Shared/TopNav");
export default {
    name: 'ResearchProposalList',
    mixins: [status, mxView],
    components: {NotFoundForm, ResearchProposalItem,ResearchProposalDetails,TopNav,ModalOrigin, ModalState},
    data() {
        return {
            columns: [
                {
                    label:this.$t('titre_de_lannonce'),
                    field:'name',
                    tdClass:'font-weight-bold title',
                },
                {
                    label:this.$t('type'),
                    field:'type',
                    formatFn: this.getStatusText,
                    tdClass:'marque',
                },
                {
                    label:this.$t('prix'),
                    field:'price',
                    type:'number',
                    formatFn: (price)=>price+' €',
                    tdClass:'price',
                },
                {
                    label:'',
                    field:this.status,
                    type:'boolean',
                    sortable:false,
                },
            ],
            size: 0,
            research: null,
            showDetails: [],
            errored: false,
            filtered:null,
        }
    },

    methods: {
        ...mapActions('messages', ['newMessage']),
        submitted(e) {
            this.newMessage({
                template_id: 1,
                params: [e],
                type: 'researches',
                type_id: this.research.id
            })
        },
        handler(event) {
            this.closeAllModals()
        },
        findUpTag(el, tag) {
            while (el.parentNode) {
                el = el.parentNode;
                if (el.className&&el.className.includes(tag))
                    return el;
            }
            return null;
        },
        middleware(event) {
            // return className !== 'btn btn-sm btn-full btn-center bg-gold' &&
            //     className !== 'modal fade' && className !== 'modal-title' && className !== 'modal-body' && className !== 'modal-content' &&
            //     className !== 'cool-lightbox__inner' && className !== 'close' &&
            //     !className.includes('cursor-p')&&
            //     tagName !== 'U' &&
            //     className !== 'cool-lightbox-toolbar__btn' && className !== 'disabled btn btn-sm bg-blue mt-3' && className !== 'dropdown-menu'
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
        closeAllModals(index) {
            let allModal = document.querySelectorAll('.research-proposal-details.active')
            for (var i = 0; i < allModal.length; i++) {
                allModal[i].classList.remove('active');
            }
            for (let i = this.showDetails.length - 1; i >= 0; i--) {
                this.$set(this.showDetails,i,false);
            }
        },
        unfreeze(i){
            this.setProductState([i,null]);
        },
        isActived(row){
           return row.pivot.status_preference == 'no' ? 'disabled' : '';
        },
        setProductState(data) {
            let product = {...this.research.products[data[0]]}
            if (product.pivot){
                product.pivot.status_preference =data[1];
                this.$set(this.research.products,data[0],product)
            }
            this.updateResearchSale([this.$route.params.id, product.id, {'status_preference': data[1]}])
        },
        openModal(i){
            this.closeAllModals(i)
            this.$set(this.showDetails,i,!this.showDetails[i]);
        },
        status(obj){
            return obj.pivot.status_preference=='no';
        },
        ...mapActions('researches', ['updateResearchSale']),
        ...mapActions('researches', ['fetchAllResearches', 'fetchResearch']),
    },
    created() {
        this.fetchAllResearches();
        this.fetchResearch(this.$route.params.id).then(res => {
            this.research = res.data
            this.research.products.forEach(res => {
                this.showDetails.push(false);
            })
            this.filtered = this.research.products.sort((a,b)=>{
                if(a.pivot.status_preference=='no'&&b.pivot.status_preference=='no')return 0;
                if(a.pivot.status_preference!='no'&&b.pivot.status_preference=='no')return -1;
                if(a.pivot.status_preference=='no'&&b.pivot.status_preference!='no')return 1;
            })
            this.filtered = [...this.filtered, ...this.research.product_missed]
            this.size = this.research.products.length;
        }).catch(err => {
            this.errored = true;
        });
    },
    computed: {
        ...mapGetters('researches', ['isResearchLoaded']),
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
    },
    watch: {}
}

</script>
