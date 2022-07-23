<template>
    <div class="research-alert-proposal-item mb-4" v-if="isAlertsLoaded">

        <div class="information row bg-white shadow rounded p-4 position-relative overflow-hidden" :class="{'disabled' : isDismissed}">

            <div class="col-12 col-sm-6 col-md-2 d-flex justify-content-center flex-column px-md-0 py-2 py-md-0 text-blue">
                <p class="mb-0">{{ $t('date_de_la_recherche2') }}</p>
                <p class="mb-0 lead">{{ research.created_at }}</p>
            </div>

            <div class="col-12 col-sm-6 col-md-2 d-flex justify-content-center flex-column px-md-0 py-2 py-md-0 text-blue">
                <p class="mb-0">{{ $t('localisation_de_lacheteur') }}</p>
                <p class="mb-0 lead">
                    {{ research.user.region && research.user.region.name }} - {{ research.user.country && research.user.country.name }}
                    <span :class="'flag-icon flag-icon-'+research.user.country.code.toLowerCase()"></span>
                </p>
            </div>

            <div class="col-12 col-sm-6 col-md-4 px-md-1 py-2 py-md-0 text-blue d-flex justify-content-center flex-column">
                <p class="mb-0">{{ translation('name', research.part.category) }}</p>
                <p class="lead mb-0 text-gold line-height-100">{{ translation('name', research.part) }}</p>
            </div>
<!--            <span v-if="research.status=='archived'">Trop tard, cet acheteur a déjà trouvé son bonheur</span>-->
            <div class="col-12 col-sm-6 col-md-2 py-2 py-md-0 px-md-0 text-blue d-flex justify-content-center flex-column">
                <p class="mb-0" v-if="research.reference">{{ $t('rfrence_de_la_pice') }}</p>
                <p class="lead mb-0 text-gold">{{ research.reference }}</p>
            </div>

            <!-- <div class="col-12 col-sm-12 col-md-2 py-2 py-md-0 px-md-0 text-blue d-flex justify-content-center flex-column">
                <p class="mb-0" v-if="research.reference">Type de la pièce souhaité</p>
                <p class="lead mb-0 text-gold pb-3 pb-md-0 line-height-100" v-if="research.reference">{{ getStatusText(research.type) }}</p>
            </div> -->

            <div class="col-12 col-md-2 pr-md-0 pl-md-3 d-flex justify-content-center flex-column">
                <div class="row">

                </div>
            </div>

        </div>



    </div>

</template>

<script>
import FlagIcon from 'vue-flag-icon'
import {status} from "../../mixins/status";
import {mapActions, mapGetters} from "vuex";

Vue.use(FlagIcon);

export default {
    name: 'ResearchAlertProposalItem',
    props: ['research', 'alert','showDetails'],
    mixins: [status],
    components: {ResearchAlertProposalDetails},
    data(){
      return {

      }
    },
    methods: {
        changeShowDetails() {
            this.$emit("update:showDetails", !this.showDetails);
            this.$emit("closeAllModals");
        },

    },
    computed: {
        isDismissed() {
            return this.isDismissedFunc(this.alert.id, this.research.id)

        },
            conf() {
                this.config.isActive = this.showDetails;
                return Object.assign({}, this.config)
            },

    },
}
</script>
