<template>
    <div class="login">

        <div class="search-engine-header py-4" style="background-image: url('/images/bg_header_search_engine_classic_parts_finder.png')">
            <div class="container">
                <div class="row">
                    <div class="col-4 col-md-2 py-2 px-md-3">

                        <a href="https://classic-parts-finder.com" class="d-block">
                            <img class="logo-gold w-75" :src="'/images/logo/logo_classic_parts_finder_gold.svg'">
                        </a>

                    </div>
                </div>
            </div>
        </div>
        <login-form></login-form>

        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="row mt-5">
                        <div class="col-2 pr-0 col-lg-4">
                            <hr>
                        </div>
                        <div class="col-8 col-lg-4">
                            <h4 class="text-center text-blue my-2 mb-4">{{ $t('not_registered_yet') }}</h4></div>
                        <div class="col-2 pl-0 col-lg-4">
                            <hr>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="d-flex flex-wrap justify-content-center">
            <router-link :to="{ name: 'Shop' }" class="btn btn-lg bg-blue mr-lg-3 mb-3">{{ $t('shop') }}</router-link>
            <router-link :to="{ name: 'searchCarModel' }" class="btn btn-lg bg-blue mr-lg-3 mb-3">{{ $t('i_research') }}</router-link>
            <a v-if="$i18n.locale=='fr'" href="https://classic-parts-finder.com/vendre-des-pieces/" class="btn btn-lg bg-blue mb-3">{{ $t('i_sell') }}</a>
            <a v-else-if="$i18n.locale=='en'" :href="'https://classic-parts-finder.com/' + $i18n.locale + '/sell-parts/'" class="btn btn-lg bg-blue mb-3">{{ $t('i_sell') }}</a>
            <a v-else :href="'https://classic-parts-finder.com/' + $i18n.locale + '/teile-verkaufen/'" class="btn btn-lg bg-blue mb-3">{{ $t('i_sell') }}</a>
        </div>
    </div>
</template>
<script>

const LoginForm = () => import(/* webpackChunkName: "group-finder" */"../Shared/LoginForm");


export default {
    components: {LoginForm},
    created(){
        if (this.$auth.check()){
            this.$router.push({name:'dashboard'})
        }
    }
}
</script>
