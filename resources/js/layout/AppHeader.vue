<template>
    <div class="app-header container">

        <div class="row w-100">

            <div class="col-4 d-flex align-items-center logo">
                <a v-if="!$auth.check()" href="/">
                    <img class="logo logo-white" :src="'/images/logo/logo_classic_parts_finder_white.svg'" alt="Logo Classic Parts Finder">
                </a>
                <router-link v-else :to="{ name: 'dashboard' }">
                    <img class="logo logo-white" :src="'/images/logo/logo_classic_parts_finder_white.svg'" alt="Logo Classic Parts Finder">
                </router-link>
            </div>

            <div class="col-8 d-flex align-items-center justify-content-end">

                <span v-if="$auth.check()">
                    <a class="basic text-white" href="#" @click.prevent="logout">
                        <i class="far fa-user-alt-slash"></i>
                        {{ $t('log_out') }}
                    </a>
                    <router-link :to="{ name: 'dashboard' }" class="btn bg-white btn-sm ml-2">{{ $t('my_dashboard') }}</router-link>
                </span>

                <span v-else>
                    <router-link class="basic text-white" :to="{ name: 'login' }">
                        <i class="fal fa-user-circle"></i>
                        {{ $t('log_in') }}
                    </router-link>

                    <router-link :to="{ name: 'searchCarModel' }" class="btn bg-white btn-sm ml-2">{{ $t('i_research') }}</router-link>

                    <a href="https://classic-parts-finder.com/vendre-des-pieces/" class="btn bg-white btn-sm ml-2" v-if="($route.name=='home'||$route.name=='logout')&&$i18n.locale=='fr'">{{ $t('i_sell') }}</a>
                    <a :href="'https://classic-parts-finder.com/' + $i18n.locale + '/sell-parts'" class="btn bg-white btn-sm ml-2" v-else-if="($route.name=='home'||$route.name=='logout')&&$i18n.locale=='en'">{{ $t('i_sell') }}</a>
                    <a :href="'https://classic-parts-finder.com/' + $i18n.locale + '/teile-verkaufen'" class="btn bg-white btn-sm ml-2" v-else-if="($route.name=='home'||$route.name=='logout')&&$i18n.locale=='de'">{{ $t('i_sell') }}</a>
                </span>

            </div>

        </div>

    </div>
</template>

<script>
export default {
    name: 'AppHeader',
    methods: {
        logout() {
            let app = this;
            this.$store.reset();
            this.$auth.logout({redirect: '/logout'})
        }
    }
};
</script>

<style scoped>

</style>

