<template>
    <div class="research-proposal-details text-blue custom-scroll">

        <div class="close-details bg-white shadow p-3 cursor-p" @click="closeModal">
            <i class="far fa-chevron-right"></i>
        </div>

        <div class="bg-white p-4 shadow h-100 d-flex justify-content-between flex-column alert-content-details">


            <div class="details-content-wrapper" :class="{'little' : newMsg}">
                <div class="details-content pr-3">
                <p class="h3 mb-2"><strong>{{ translation('name', research.part) }}</strong></p>
                <p>
                    <template v-if="research.type">
                        {{ getStatusText(research.type) }}
                    </template>
                    <template v-else>
                        <span v-for="(type,index) in research.types">
                            {{getStatusText(type)}}
                            <template v-if="(research.types.length !== 1) && (index !== research.types.length-1)">
                                ,
                            </template>
                        </span>
                    </template>
                </p>

                    <div class="img-wrapper mb-3" v-if='research.img.length != 0'>

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

                    <div class="pb-5">
                        <table class="table text-blue table-striped py-3">
                            <tbody>

                            <tr>
                                <td class="border-0">
                                    <strong class="d-inline-block mr-2">{{ $t('date_de_la_recherche2') }}</strong>
                                    {{ research.created_at }}
                                </td>
                            </tr>

                            <tr v-if="research.reference">
                                <td class="border-0">
                                    <strong class="d-inline-block mr-2">{{ $t('rfrence_du_produit') }}</strong>
                                    {{ research.reference }}
                                </td>
                            </tr>

                            <tr v-if="research.details">
                                <td class="border-0" style="white-space: pre-line;">
                                    <strong>{{ $t('dtails') }} </strong>
                                    {{ translation('details', research) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="border-0">
                                    <p class="mb-1"><strong>{{ $t('vehicle') }}</strong></p>
                                    {{ research.modele.modele.brand.name }} {{research.modele.modele.name}} ({{ research.modele.year }})<br>
                                    <em>{{ research.modele.version }}</em>
                                </td>
                            </tr>
                            <tr v-if="research.compatible_suggestion">
                                <td class="border-0" style="white-space: pre-line;">
                                    <strong>{{ $t('compatible_avec') }} </strong>
                                    {{ research.compatible_suggestion }}
                                </td>
                            </tr>

                            <tr>
                                <td class="border-0">
                                    <p class="mb-1"><strong>{{ $t('propos_de_lacheteur') }}</strong></p>
                                    {{ research.user.region && research.user.region.name }} -
                                    {{ research.user.country && research.user.country.name }}
                                    <span :class="'flag-icon flag-icon-'+research.user.country.code.toLowerCase()"></span>

                                    <div>
                                        <span class="py-1 cursor-p" @click="newMsg=true">
                                            <i class="fal fa-envelope pr-1"></i>
                                            <u>{{ $t('contacter_lacheteur_potentiel') }}</u> <br>
                                            <small class="text-secondary">{{ $t('si_vous_avez_besoin_dlments_plus_prcis_en_rapport') }}</small>
                                        </span>
                                    </div>

                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
            <div>
                <div class="d-flex">
                   <button @click="emitToggleResearchDismiss([alert.id, research.id, 0])" v-b-modal="'modal-center'+research.id" class="btn btn-lg  bg-gold text-white py-2 w-100">
                    <p class="lead mb-0 font-weight-normal">{{ $t('proposer_un_produit') }}</p>
                    </button>

                    <button @click="emitToggleResearchDismiss([alert.id, research.id, 1])" class="btn bg-grey btn-del btn-lg py-2 ml-2">
                        <p class="lead mb-0 font-weight-normal">
                            <i class="fal fa-trash-alt"></i>
                        </p>
                    </button>
                </div>
                <div v-if="newMsg" class="mt-2">
                    <p class="lead text-center"><i class="far fa-envelope pr-1"></i>{{ $t('votre_question_pour_cet_acheteur_potentiel') }}</p>
                    <!-- <button @click="newMsg=false">X</button> -->
                    <new-message-form class="my-2" @submitted="submittedMessage" :id="research.id" @close="newMsg=false" type="alert_subscription_researches"/>
                </div>
            </div>

        </div>

    </div>

</template>

<script>
import {mapActions,mapGetters} from "vuex";
import {status} from "../../mixins/status";
const NewMessageForm = () => import("../Shared/NewMessageForm");


export default {
    name: 'ResearchProposalDetails',
    mixins: [status],
    components: {NewMessageForm},
    props: {
        research: {
            type: Object,
            default: null
        }, alert: {
            type: Object,
            default: null
        },
    },
    data() {
        return {
            newMsg: false,
            slide: 0,
            sliding: null,
            confirmation: false,
            indexLightBox: null,
        }
    },
    methods: {
        zoom(index) {
            this.showBox([this.research.img])
            this.setIndex(index)
        },
        emitToggleResearchDismiss(array){
            this.closeModal();
            this.$emit('dismiss',array);
        },
        setProductState(state) {
            this.$emit("productState", state);
        },
        closeModal() {
            this.$emit("showDetails", this.closeModal);
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
        submittedMessage(e) {
            this.newMessage({
                template_id: 4,
                params: [e],
                recipient:this.research.user.id,
                type: 'alert_subscription_researches',
                type_id: this.research.pivot.id
            })
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
