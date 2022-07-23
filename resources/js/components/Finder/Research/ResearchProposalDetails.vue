<template>
    <div class="research-proposal-details right-modal text-blue custom-scroll">

        <div class="close-details bg-white shadow p-3 cursor-p" @click="closeModal">
            <i class="far fa-chevron-right"></i>
        </div>

        <div class="bg-white p-4 shadow h-100 d-flex justify-content-between flex-column">

            <div class="details-content-wrapper text-blue" :class="{'little' : newMsg}">

                <div class="details-content pr-3">
                    <b-dropdown class="mr-2 float-right mt-n2" size="lg" variant="link"
                                toggle-class="text-decoration-none"
                                no-caret menu-class="border-0 rounded shadow-lg">
                        <template #button-content>
                            <button class="bg-white shadow rounded-circle share text-primary border-0"><i
                                class="fas fa-share-alt"></i></button>
                        </template>
                        <b-dropdown-text menu-class="px-0">
                            <p class="mb-0 lead pt-1 max-content text-blue">{{ $t('partager_le_produit') }}</p>
                        </b-dropdown-text>
                        <b-dropdown-item class="text-blue" @click="copyUrl()"><i
                            class="fas fa-share-alt fa-fw mr-1"></i>
                            {{ $t('copier_le_lien') }}
                        </b-dropdown-item>
                        <b-dropdown-item class="text-blue" target="_blank"
                                         :href="'https://www.facebook.com/sharer.php?u='+returnAppUrl()+'/' + $i18n.locale + '/product/'+product.id">
                            <i class="fab fa-facebook fa-fw mr-1"></i> {{ $t('facebook') }}
                        </b-dropdown-item>
                    </b-dropdown>
                    <p class="h3 mb-2"><strong>{{ translation('name', product) }}</strong></p>
                    <p>

                    <span class="cursor-p" v-b-modal.guide-des-typologie>
                        {{ getStatusText(product.type) }}
                        <i class="fal fa-info-circle"></i>
                    </span>

                        <b-badge class="text-white badge-lg cursor-p ml-2" pill
                                 :variant="getVariantBadgeColor(product.condition)">
                        <span v-b-modal.guide-des-etats>
                            {{ $t('state') }} {{ getStatusText(product.condition).toLowerCase() }}
                            <i class="fal fa-info-circle"></i>
                        </span>
                        </b-badge>

                    </p>

                    <div class="img-wrapper mb-3" v-if="product.img != null">

                        <div v-if="product.img.length == 1" @click="zoom(0)" :key="0" class="h-100">
                            <zoom-on-hover class="h-100 w-100" :img-normal="product.img[0]"
                                           :scale="1.2"></zoom-on-hover>
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

                            <b-carousel-slide v-for="(i, imageIndex) in product.img" :key="imageIndex">
                                <template v-slot:img>
                                    <div class="h-100" @click="zoom(imageIndex)" :key="imageIndex">
                                        <zoom-on-hover class="h-100 w-100" :img-normal="i" :scale="1.2"></zoom-on-hover>
                                    </div>
                                </template>
                            </b-carousel-slide>

                        </b-carousel>

                    </div>

                    <div>
                        <table class="table text-blue table-striped py-3">
                            <tbody>

                            <tr>
                                <td class="border-0" style="white-space: pre-line;">
                                    <strong>{{ $t('description') }} </strong>
                                    {{ translation('description', product) }}
                                </td>
                            </tr>

                            <tr v-if="product.reference">
                                <td class="border-0">
                                    <strong class="d-inline-block mr-2">{{ $t('rfrence_du_produit') }}</strong>
                                    {{ product.reference }}
                                </td>
                            </tr>
                            <tr v-if="product.manufacturer">
                                <td class="border-0">
                                    <strong class="d-inline-block mr-2">{{ $t('fabricant') }}</strong>
                                    {{ product.manufacturer }}
                                </td>
                            </tr>
                            <tr>
                                <td class="border-0">
                                    <p class="mb-1"><strong>{{ $t('a_propos_du_vendeur') }}</strong></p>

                                    <template v-if="product.merchant.user[0].role!=='admin'">
                                        {{ $t('seller') }} {{ product.merchant.merchant_type }} -
                                        {{ product.merchant.region ? product.merchant.region.name : '' }} -
                                        {{ product.merchant.country.name }}
                                        <span
                                            :class="'flag-icon flag-icon-'+product.merchant.country.code.toLowerCase()"></span>
                                    </template>

                                    <template v-else>
                                        {{ $t('vendu_directement_par_cpf') }}
                                    </template>

                                    <div>
                                        <span class="py-1 cursor-p" @click="newMsg=true">
                                            <i class="fal fa-envelope pr-1"></i>
                                            <u>{{ $t('contacter_le_vendeur') }}</u>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="product.average_preparation_time">
                                <td class="border-0">
                                    <strong class="d-inline-block mr-2">{{ $t('temps_moyen_de_prparation') }}</strong>
                                    {{ $t('jours', {"average_preparation_time": product.average_preparation_time}) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="border-0">
                                    <strong class="d-inline-block mr-2">{{ $t('poids_du_produit') }}</strong>
                                    {{ product.weight }} Kg
                                </td>
                            </tr>

                            <tr v-if="product.width && product.height && product.depth">
                                <td class="border-0">
                                    <strong class="d-inline-block mr-2">{{ $t('dimensions_du_produit') }}</strong>
                                    {{ product.width }}cm x {{ product.height }}cm x {{ product.depth }}cm
                                </td>
                            </tr>


                            <tr>
                                <td class="border-0">
                                    <p class="mb-1"><strong>{{ $t('expdition') }}</strong>
                                        <template v-if="product.averagePreparationTime">{{
                                                $t('colis_remis_au_transporteur_sous_jours', {"averagePreparationTime": product.averagePreparationTime})
                                            }}
                                        </template>
                                    </p>
                                    {{ deliveryMethod }}<br>
                                    <span v-if="product.delivery_option !== 2">
                                            <i>{{ $t('frais_de_port_en_sus_calculer_selon_le_mode_de_liv') }}</i>
                                    </span>
                                </td>
                            </tr>


                            <tr>
                                <td class="border-0">
                                    <!--                                    <strong class="d-inline-block mr-2">{{ $t('prix') }}</strong>-->

                                    <!--                                    <div class="row">-->
                                    <!--                                        <div class="col-8">{{ $t('selling_price') }} :</div>-->
                                    <!--                                        <div class="col-4 text-right">{{ sellerPrice(product.price).profit }} €</div>-->
                                    <!--                                    </div>-->

                                    <!--                                    <div class="row">-->
                                    <!--                                        <div class="col-8">{{ $t('operational_fees') }}:</div>-->
                                    <!--                                        <div class="col-4 text-right">{{ sellerPrice(product.price).commission }} €</div>-->
                                    <!--                                    </div>-->
                                    <!--                                    <hr>-->
                                    <div class="row">
                                        <div class="col-8"><strong>{{ $t('prix') }}</strong></div>
                                        <div class="col-4 text-right"><strong>{{ product.price.toFixed(2) }} €</strong>
                                        </div>
                                        <div class="col-12 text-right">{{ $t('hors_frais_de_ports') }}</div>
                                    </div>

                                </td>
                            </tr>

                            </tbody>
                        </table>

                    </div>


                </div>


            </div>
            <div>
                <button class="btn btn-sm w-100 btn-center bg-gold" @click="redirectTo(product.id)">Acheter</button>
            </div>

        </div>

    </div>

</template>

<script>
import {mapActions} from "vuex";
import ObvyPaiement from "./ObvyPaiement";
import {status} from "../../../mixins/status";
import profitCalculation from "../../../mixins/profitCalculation";

const NotFoundForm = () => import("../../Shared/NotFoundForm");
const NewMessageForm = () => import("../../Shared/NewMessageForm");

export default {
    name: 'ResearchProposalDetails',
    components: {ObvyPaiement, NewMessageForm, NotFoundForm},
    mixins: [status, profitCalculation],

    props: {
        product: {
            type: Object,
            default: null
        },
        index: {
            type: Number,
            default: null
        },
    },
    data() {
        return {
            slide: 0,
            sliding: null,
            confirmation: false,
            indexLightBox: null,
            newMsg: false,
            link_url: process.env.MIX_APP_URL + '/' + this.$i18n.locale + '/product/' + this.product.id
        }
    },
    methods: {

        redirectTo(id) {
            window.open(`/${this.$i18n.locale}/product/${id}/checkout`)
        },
        returnAppUrl() {
            return process.env.MIX_APP_URL;
        },
        copyUrl() {
            const el = document.createElement('textarea');
            el.value = this.link_url;
            el.setAttribute('readonly', '');
            el.style.position = 'absolute';
            el.style.left = '-9999px';
            document.body.appendChild(el);
            const selected = document.getSelection().rangeCount > 0 ? document.getSelection().getRangeAt(0) : false;
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            if (selected) {
                document.getSelection().removeAllRanges();
                document.getSelection().addRange(selected);
            }
        },
        zoom(index) {
            this.showBox([this.product.img])
            this.setIndex(index)
        },
        getVariantBadgeColor(etat) {
            if (etat == 12 || etat == 13) {
                return 'success';
            }
            if (etat == 14 || etat == 15) {
                return 'warning';
            }
            if (etat == 16) {
                return 'danger';
            }
        },
        setProductState(state) {
            this.$emit("productState", [this.index, state]);
        },
        submitted(e) {
            this.newMessage({
                template_id: 2,
                params: [e],
                type: 'research_product',
                type_id: this.product.pivot.id
            })
        },
        submittedMessage(e) {
            this.newMessage({
                template_id: 4,
                params: [e],
                recipient: this.product.merchant.user[0].id,
                type: 'research_product',
                type_id: this.product.pivot.id
            })
        },
        closeModal() {
            this.$emit("showDetails", this.closeModal);
            this.newMsg = false
        },
        setSale() {
            this.updateResearchSale([this.$route.params.id, this.product.id, {status: 4}]).then(() => {
            });
        },
        onSlideStart(slide) {
            this.sliding = true
        },
        onSlideEnd(slide) {
            this.sliding = false
        },
        ...mapActions('messages', ['newMessage']),
        ...mapActions('researches', ['updateResearchSale']),
        ...mapActions('coolLightBox', ['showBox', 'setIndex'])
    },
    computed: {
        the_shipping_cost() {
            if (this.$auth.user().country.code !== this.product.merchant.code) return this.product.shipping_cost_abroad;
            else return this.product.shipping_cost;
        },
        deliveryMethod() {
            const delivery = {
                0: this.$t('envoi_postal'),
                1: this.$t('remise_en_main_propre_et_envoi_postal'),
                2: this.$t('remise_en_main_propre_uniquement')
            }
            const deliveryOption = this.product.delivery_option
            return delivery[deliveryOption]
        },
        negociableCategory() {
            const delivery = {
                0: 'remote_only',
                1: 'hand_remote',
                2: 'hand_only'
            }
            const deliveryOption = this.product.delivery_option
            const negotiable = this.product.negotiable
            if (delivery[deliveryOption] === 'remote_only') {
                return negotiable ? 'part_negotiable' : 'part'
            }
            if (delivery[deliveryOption] === 'hand_remote') {
                return negotiable ? 'part_negotiable_hand' : 'part_hand'
            }
            if (delivery[deliveryOption] === 'hand_only') {
                return negotiable ? 'part_negotiable_hand_only' : 'part_hand_only'
            }
        },
    }
}
</script>
