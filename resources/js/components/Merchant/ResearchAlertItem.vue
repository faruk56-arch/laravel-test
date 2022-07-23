<template>
    <div class="research-alert-item bg-white position-relative shadow rounded px-4 py-2 text-blue row mx-0 mb-3"
         :class="{'research-active':alert.researches.length>0&&alert.active,'active':alert.researches.length==0&&alert.active}">
        <div class="col-12 col-md-8 pl-md-0">
            <div class="row">
                <div class="col-4 col-md-2 d-flex align-items-center pr-0">
                    <img v-if="alert.modele.img" class="w-100 py-3 px-4" :src="'/images/Cars/'+alert.modele.img+'.png'" @error="alert.modele.img=null">
                    <img v-else class="w-100  py-3 px-4" :src="'/images/Cars/emptycar.png'">
                </div>
                <div class="col-8 col-md-4 d-flex justify-content-center flex-column">
                    <p class="lead mb-0" v-if="alert.brand_id">{{ alert.brand.name }}</p>
                    <p class="mb-0" v-if="alert.modele_id">{{ alert.modele.name }}</p>
                </div>
                <div class="col-12 col-md-6 px-0 d-flex justify-content-center flex-column">
                    <p class="mb-0" v-if="alert.category">{{ translation('name', alert.category) }}</p>
                    <p class="mb-0" v-else-if="alert.part_id">{{ translation('name', alert.part.category) }}</p>
                    <p class="lead mb-0 text-gold" v-if="alert.part_id">{{ translation('name', alert.part) }}</p>
                    <p class="lead mb-0 text-gold" v-else>{{ $t('category_all_parts') }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 pr-md-0 pt-3 pt-md-0">
            <div class="row h-100">

                <div class="col-8 col-md-6 d-flex align-items-center justify-content-center px-0">
                    <div v-if="alert.active==1" class="w-100">
                        <router-link :to="{name:'ResearchAlertProposalList',params:{id:alert.id}}" v-if="alert.researches.length>0" class="btn bg-gold btn-full btn-center">{{ $t('voir_les_recherches') }}</router-link>
                        <p class="mb-0 text-center" v-else>{{ $t('en_attente_dune_recherche') }}</p>
                    </div>

                    <span v-else class="badge badge-danger px-4">{{ $t('dsactiv') }}</span>


                </div>

                <div class="col-4 col-md-6 px-0 text-dark-grey d-flex align-items-center justify-content-center">
                    <b-dropdown right size="xs">
                        <template v-slot:button-content>
                            <i class="fal fa-edit"></i>
                        </template>
                        <b-dropdown-item v-if="alert.active===1" @click="updateActiveState([alert.id, 0])">
                            <i class="fal fa-ban pr-1"></i>{{ $t('dsactiver') }}
                        </b-dropdown-item>
                        <b-dropdown-item v-else @click="updateActiveState([alert.id, 1])">
                            <i class="fal fa-check"></i> {{ $t('activer') }}
                        </b-dropdown-item>
                        <b-dropdown-divider></b-dropdown-divider>
                        <b-dropdown-item @click="removal(alert)"><i class="fal fa-trash"></i> {{ $t('remove') }}
                        </b-dropdown-item>
                    </b-dropdown>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';

export default {
    name: 'ResearchAlertItem',
    props: ['alert'],

    methods: {
        ...mapActions('alerts', ['updateActiveState']),
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
    },
    computed: {
        ...mapGetters('alerts', ['allAlerts']),
    }
}
</script>
