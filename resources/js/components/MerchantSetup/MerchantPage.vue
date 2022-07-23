<template>
    <div class="merchant-page">

        <nav class="navbar navbar-expand-lg zi-5 py-4">
            <div class="container position-static">
                <div class="logo navbar-brand">
                    <a href="/">
                        <img src="/images/logo/logo_classic_parts_finder_white.svg" alt="Classic Parts Finder" class="logo">
                    </a>
                </div>
                <div class="navbar-collapse justify-content-end">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link text-white px-3" href="/#comment-ca-marche">{{ $t('how_does_it_work') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white px-3" href="/#avantages">{{ $t('advantages') }}</a>
                        </li>

                        <li class="nav-item" v-if='!$auth.check()'>
                            <a class="nav-link text-white px-3" href="/register">{{ $t('registration') }}</a>
                        </li>

                        <li class="nav-item">

                            <a v-if='!$auth.check()' class="btn btn-default bg-white px-3 mt-4 mt-md-0 ml-md-3" href="/login"><i class="fal fa-user-circle"></i> {{ $t('log_in') }}</a>

                            <a v-else class="btn btn-default bg-white px-3 ml-3" href="/finder/dashboard"><i class="fal fa-user-circle"></i> {{ $t('dashboard') }}</a>

                        </li>
                    </ul>
                </div>
                <div class="menu-switch d-block d-lg-none" onclick="this.classList.toggle('active'); document.querySelector('.navbar-collapse').classList.toggle('active');">
                    <i class="fal fa-bars fa-2x text-white open"></i>
                    <i class="fal fa-times fa-2x text-blue close-m"></i>
                </div>
            </div>
        </nav>


        <div class="merchant-page-header pt-4" style="background-image: url('/images/merchant_page_bg.jpg')">
            <div class="container position-relative">
                <div class="col-md-6 offset-md-6 informations">
                    <i18n tag="h1" path="selling_easy" class="text-white h2 h2-carter mb-4">
                        <span class="text-gold">Classic Parts Finder</span>
                    </i18n>
                    <h2 class="h3 text-white mb-3">{{ $t('create_market_fast') }}</h2>
                    <div>
                        <router-link class="btn bg-gold btn-lg mr-3 mb-3" :to="{name:'MerchantSettings',params:{type:'Pro'}}">{{ $t('i_am_pro') }}</router-link>
                        <router-link v-if="!$auth.check()" class="btn bg-gold btn-lg mb-3" :to="{name:'MerchantSettings',params:{type:'Particulier'}}">{{ $t('i_am_individual') }}</router-link>
                        <div v-else class="btn bg-gold btn-lg mb-3" @click="createMerchant">{{ $t('i_am_individual') }}</div>
                    </div>
                    <router-link v-if="!$auth.check()" class="basic text-white" :to="{name:'login'}">{{ $t('already_have_account') }}</router-link>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row py-5 my-4">
                <div class="col-md-6 d-flex justify-content-center flex-column">
                    <div class="row">
                        <div class="col-4 mb-3 mb-md-0 col-md-2 pl-md-0 pr-md-0">
                            <div class="working-wheel" style="background-image: url('/images/home/working-wheel.svg')">
                                <i class="fal fa-bullhorn text-white"></i>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <h2 class="text-blue font-family-reset">{{ $t('receive_alert_research') }}</h2>
                            <p>{{ $t('simplified_announce_explained') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <img class="w-100" src="/images/merchant/merchant_2.svg">
                </div>
            </div>
            <div class="row py-5">
                <div class="col-md-6 order-md-1 order-2">
                    <img class="w-100" src="/images/merchant/merchant_1.svg">
                </div>
                <div class="col-md-6 d-flex justify-content-center flex-column order-md-2 order-1">
                    <div class="row">
                        <div class="col-4 mb-3 mb-md-0 col-md-2 pl-md-0 pr-md-0">
                            <div class="working-wheel" style="background-image: url('/images/home/working-wheel.svg')">
                                <i class="fal fa-store text-white"></i>
                            </div>
                        </div>
                        <div class="col-md-10">

                            <h2 class="text-blue font-family-reset">{{ $t('publish_products_fast_easy') }}</h2>
                            <p>{{ $t('publish_large_catalog') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row py-5 my-4">
                <div class="col-md-6 d-flex justify-content-center flex-column">
                    <div class="row">
                        <div class="col-4 mb-3 mb-md-0 col-md-2 pl-md-0 pr-md-0">
                            <div class="working-wheel" style="background-image: url('/images/home/working-wheel.svg')">
                                <i class="fal fal fa-shield-check text-white"></i>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <h2 class="text-blue font-family-reset">Paiement sécurisé et
                                garanti par <img class="px-1" src="/images/logo-obvy.png"></h2>
                            <p>Vendez vos pièces en toute sécurité avec Obvy, notre partenaire qui sécurise vos ventes !</p>
                            <router-link class="basic" :to="{name:'ObvyConnection'}">En savoir plus</router-link>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-flex align-items-center justify-content-center flex-column">
                    <img class="w-75" src="/images/merchant/merchant_3.svg">
                </div>
            </div>
        </div>

        <div class="bg-gold text-white py-4">
            <div class="container">
                <h2 class="text-center font-family-reset mb-1">{{ $t('ready_to_sell') }}</h2>
                <p class="lead font-weight-normal text-center mb-4">{{ $t('register_fast') }} </p>
                <div class="d-flex align-items-center justify-content-center flex-wrap">
                    <router-link class="btn bg-white btn-lg mr-md-3 mb-3 mb-md-0" :to="{name:'MerchantSettings',params:{type:'Pro'}}">{{ $t('i_am_pro') }}</router-link>
                    <router-link v-if="!$auth.check()" class="btn bg-white btn-lg" :to="{name:'MerchantSettings',params:{type:'Particulier'}}">{{ $t('i_am_individual') }}</router-link>
                    <div v-else class="btn bg-white btn-lg" @click="createMerchant">{{ $t('i_am_individual') }}</div>
                </div>
            </div>
        </div>
        <footer class="bg-blue pt-5 mt-md-n1 zi-5 position-relative text-white">
            <div class="container">
                <div class="row pb-5">
                    <div class="col-md-4">
                        <img src="/images/logo/logo_classic_parts_finder_white.svg" alt="Classic Parts Finder" class="logo mb-4">
                        <p>{{ $t('free_service_part') }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong class="h5">{{ $t('menu') }}</strong>
                        <ul class="list-unstyled mt-3">
                            <li class="pb-1">
                                <a href="/model" class="text-white">{{ $t('take_my_research') }}</a>
                            </li>
                            <li class="pb-1">
                                <a href="https://classic-parts-finder.com/vendre-des-pieces/" v-if="$i18n.locale=='fr'" class="text-white">{{ $t('i_sell') }}</a>
                                <a :href="'https://classic-parts-finder.com/' + $i18n.locale + '/sell-parts'" v-else-if="$i18n.locale=='en'" class="text-white">{{ $t('i_sell') }}</a>
                                <a :href="'https://classic-parts-finder.com/' + $i18n.locale + '/teile-verkaufen'" v-else class="text-white">{{ $t('i_sell') }}</a>
                            </li>
                            <template v-if="!$auth.check()">
                                <li class="pb-1">
                                    <a href="/register" class="text-white">{{ $t('registration') }}</a>
                                </li>
                                <li class="pb-1">
                                    <a href="/login" class="text-white">{{ $t('log_in') }}</a>
                                </li>
                            </template>
                            <template v-else></template>
                            <li class="pb-1">
                                <a href="/finder/dashboard" class="text-white">{{ $t('dashboard') }}</a>
                            </li>
                            <!-- <li class="pb-1">
                                <a href="/litige" class="text-white">Déclarer un litige</a>
                            </li> -->
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <ul class="list-unstyled d-flex py-2">
                            <li class="px-3 font-weight-normal">
                                <a href="https://www.facebook.com/Classic-Parts-Finder-103525547820241" target="_blank" rel="noopener" :aria-label="$t('facebook_link')">
                                    <i class="fab fa-facebook-f text-white fa-2x"></i>
                                </a>
                            </li>
                            <li class="px-3 font-weight-normal">
                                <a href="https://www.instagram.com/classic_parts_finder/" target="_blank" rel="noopener" :aria-label="$t('instagram_link')">
                                    <i class="fab fa-instagram text-white fa-2x"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="border-white border-top py-4 d-flex justify-content-between">
                    <p class="mb-0">
                        <small>
                            © {{ new Date().getFullYear() }} {{ $t('all_rights') }} - Classic Parts Finder
                        </small>
                    </p>
                    <ul class="list-unstyled d-flex mb-0">
                        <li class="px-2">
                            <a :href="'/' + $i18n.locale + '/conditions-generales-d-utilisation/#privacy-policy'" class="text-white"><small>{{ $t('privacy_policies') }}</small></a>
                        </li>
                        <li class="px-2">
                            <a :href="'/' + $i18n.locale + '/conditions-generales-d-utilisation'" class="text-white"><small>{{ $t('cgu_full') }}</small></a>
                        </li>
                        <li class="pl-2">
                            <a :href="'/' + $i18n.locale + '/mentions-legales'" class="text-white"><small>{{ $t('legals_info') }}</small></a>
                        </li>
                    </ul>
                </div>

            </div>
        </footer>
    </div>
</template>


<script>
import {mapActions} from "vuex";

const AppHeader = () => import('../../layout/AppHeader.vue');
export default {
    components: {AppHeader},
    name: "MerchantPage",
    data() {
        return {
            form: {
                merchantType: this.$route.params.type,
                firstname: null,
                lastname: null,
                merchantName: null,
                merchantSiret: null,
                address: null,
                city: null,
                zip: null,
                country_id: null,
            },
        }
    },
    methods: {
    }
}
</script>

<style scoped>

</style>
