<template>
    <div>
        <div class="search-engine-header py-4" style="background-image: url('/images/bg_header_search_engine_classic_parts_finder.png')">
            <div class="container">
                <div class="row">
                    <div class="col-4 col-md-2 py-2 px-md-3">

                        <a v-if="!$auth.check()" href="https://classic-parts-finder.com">
                            <img class="logo-gold w-75" :src="'/images/logo/logo_classic_parts_finder_gold.svg'">
                        </a>
                        <router-link v-else :to="{ name: 'dashboard' }">
                            <img class="logo-gold w-75" :src="'/images/logo/logo_classic_parts_finder_gold.svg'">
                        </router-link>

                    </div>
                </div>
            </div>
        </div>
        <div class="container search-engine-content">
            <div class="row">
                <div class="col-12 text-center">
                    <img src="/images/confirmation-email.svg" class="mb-4">
                    <i18n tag="h1" path="email_sent_confirm_research" class="h2 h2-carter text-center text-blue"><br></i18n>
                    <i18n tag="p" path="check_spams" class="text-center py-4"><br><a href="mailto:info@classic-parts-finder.com" class="basic text-gold">info@classic-parts-finder.com</a></i18n>
                    <router-link tag='button' class='btn btn-lg bg-blue' :to="{name:'login'}">{{ $t('back_login') }}</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CreateAccountConfirmation",
    beforeCreate() {
    }
}
</script>

<style scoped>

</style>
