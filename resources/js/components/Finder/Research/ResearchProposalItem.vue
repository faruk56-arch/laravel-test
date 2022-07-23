<template>
    <div class="research-proposal-item" :class="missed_product ? 'missed_product' : ''">

        <div class="information row bg-white shadow rounded p-4 position-relative overflow-hidden" :class="[{'bg-grey' : state==='no'},state]">

            <div class="col-12 col-md-3 h-100 proposal-item-img" :style="'background-image: url('+product.img[0]+')'" :class="state==='no'?'disabled':''"></div>

            <div class="col-12 col-md-3 d-flex justify-content-center flex-column" :class="state==='no'?'disabled':''">
                <p class="text-blue mb-1">{{ translation('name', product) }}</p>
                <p class="lead text-gold mb-0">{{ product.condition.label }}</p>
                <p class="lead text-gold mb-0 line-height-100">{{ getStatusText(product.type) }}</p>
            </div>

            <p class="col-12 col-md-3 h3 text-blue d-flex align-items-center mb-0 pl-4 py-4" :class="state==='no'?'disabled':''">
                {{ product.price }} €
            </p>

            <div class="col-12 col-md-3 d-flex align-items-center" :class="state==='no'?'disabled':''" v-if="!missed_product">
                <a class="btn btn-sm btn-full btn-center bg-gold" @click="changeShowDetails">{{ $t('consulter_loffre') }}</a>
            </div>

        </div>

    </div>

</template>

<script>
import {mapActions} from "vuex";
import {status} from "../../../mixins/status";

const ResearchProposalDetails = () => import(/* webpackChunkName: "group-finder" */'./ResearchProposalDetails');
export default {
    data() {
        return {
            state: this.missed_product ? null : this.product.pivot.status_preference,
            indexLightBox: null,

        }
    },

    name: 'ResearchProposalItem',
    components: {ResearchProposalDetails},
    mixins: [status],
    props: {
        product: {
            type: Object,
            default: null
        },
        showDetails: {
            type: Boolean,
            default: false
        },
        missed_product: {
            type: Boolean,
            default: false
        }
    },
    mounted() {
    },
    methods: {

        changeShowDetails() {
            this.$emit("update:showDetails", !this.showDetails);
            this.$emit("closeAllModals");
        },
        setProductState(data) {
            this.state = data;
            this.product.pivot.status_preference = data;
            this.updateResearchSale([this.$route.params.id, this.product.id, {'status_preference': data}])
        },
        ...mapActions('researches', ['updateResearchSale']),
    },
    computed: {
        conf() {
            if (this.missed_product) {
                return null
            }
            this.config.isActive = this.showDetails;
            return Object.assign({}, this.config)
        },
    },
}
</script>
