<template>
    <div class="col-xl-6 pt-md-3 pt-xl-0">
        <div class="row">
            <div class="col-6 col-md-6 text-blue pb-4 px-2 px-md-3">
                <router-link :to="{ name: 'researchAlert' }" class="bg-white shadow rounded d-block dashboard-section-link text-center py-lg-5 py-4">
                    <i class="fal fa-bullhorn fa-5x text-gold"></i>
                    <p class="lead font-weight-normal text-center mb-0 mt-3">{{ $t('my_simplified_announces') }}</p>
                </router-link>
            </div>
            <div class="col-6 col-md-6 text-blue pb-4 px-2 px-md-3">
                <router-link :to="{ name: 'catalog' }" class="bg-white shadow rounded d-block dashboard-section-link text-center py-lg-5 py-4 h-100">
                    <i class="fal fa-store fa-5x text-gold"></i>
                    <p class="lead font-weight-normal text-center mb-0 mt-3">{{ $t('my_products') }}</p>
                </router-link>
            </div>


            <div class="col-12 text-blue pb-4 pb-md-0 d-none d-sm-block">
                <div class="bg-white shadow rounded p-4 position-relative">
                    <p class="lead font-weight-normal">
                        <i class="fal fa-bullhorn pr-1"></i>
                        {{ $t('my_last_simplified_announces') }}
                    </p>
                    <hr>
                    <table class="table table-striped py-3 last-research" v-if="isAlertsLoaded">
                        <tbody>
                        <tr v-if="getLatestAlerts == 0">
                            <td class="border-0" scope="row">
                                                    <span class="py-1 pb-2 d-block">
                                                        {{ $t('no_simplified_announces_currently') }}
                                                    </span>
                            </td>
                        </tr>
                        <tr v-for="alert in getLatestAlerts">
                            <th class="border-0" scope="row">
                                <img v-if="alert.modele.img" :src="'/images/Cars/'+alert.modele.img+'.png'" class="car py-1 pr-2" @error="alert.modele.img=null">
                                <img v-else :src="'/images/Cars/emptycar.png'" class="car py-1 pr-2">
                                {{ alert.brand.name }} {{ alert.modele.name }}
                            </th>
                            <td class="border-0 " v-if="alert.part">
                                {{ translation('name', alert.part) }}
                            </td>
                            <td class="border-0 " v-else-if="alert.category">
                                {{ translation('name', alert.category) }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div v-else>
                        <span class="loader"></span>
                    </div>
                    <router-link :to="{ name: 'researchAlert' }" class="btn bg-blue btn-sm btn-center mt-1">
                        {{ $t('all_my_simplified_announces') }}
                    </router-link>
                </div>
            </div>

        </div>

        <div class="not-activated rounded" v-if="!$auth.user().merchant_id">
            <i18n tag="p" path="didnt_config_seller_space" class="h3 text-center px-3"><br>
            </i18n>
            <a href="https://classic-parts-finder.com/vendre-des-pieces/" class="btn bg-gold btn-sm mt-2" v-if="$i18n.locale=='fr'">{{ $t('configure') }}</a>
            <a :href="'https://classic-parts-finder.com/' + $i18n.locale + '/sell-parts'" class="btn bg-gold btn-sm mt-2" v-else-if="$i18n.locale=='en'">{{ $t('configure') }}</a>
            <a :href="'https://classic-parts-finder.com/' + $i18n.locale + '/teile-verkaufen'" class="btn bg-gold btn-sm mt-2" v-else>{{ $t('configure') }}</a>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";

export default {
    name: "LastSales",
    created() {
        if (this.$auth.user().merchant_id) {
            this.fetchAllAlerts()
        }
    },
    methods: {
        ...mapActions('alerts', ['fetchAllAlerts']),
    },
    computed: {
        ...mapGetters('alerts', ['getLatestAlerts', 'isAlertsLoaded']),
    }
}
</script>

<style scoped>

</style>
