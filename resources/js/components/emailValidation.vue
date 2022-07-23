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

                    <template v-if="loading">
                        <span class="loader h1"></span>
                        <h1 class="h2 h2-carter text-center text-blue">{{ $('email_modification') }}</h1>
                    </template>

                    <template v-else-if='error'>

                        <img src="/images/page-error.svg" class="mb-4">
                        <h1 class="h2 h2-carter text-center text-blue mt-4">{{ $t('expired_link') }}</h1>
                        <i18n path="to_change_email_dashboard" tag="p" class="text-center py-4"><br><a href="mailto:info@classic-parts-finder.com" class="basic text-gold">info@classic-parts-finder.com</a></i18n>
                        <router-link tag='button' class='btn btn-lg bg-blue' :to="{name:'login'}">{{ $t('log_myself') }}</router-link>

                    </template>

                    <template v-else>

                        <img src="/images/change-email-confirmation.svg" class="mb-4">
                        <h1 class="h2 h2-carter text-center text-blue mt-2 mb-5">{{ $t('success_email_change') }}</h1>
                        <router-link tag='button' class='btn btn-lg bg-blue' v-if="!$auth.check()" :to="{name:'login'}">{{ $t('log_myself') }}</router-link>
                        <router-link tag='button' class='btn btn-lg bg-blue' v-else :to="{name:'dashboard'}">{{ $t('my_dashboard') }}</router-link>

                    </template>


                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "emailValidation",
    props: ['url'],
    data() {
        return {
            loading: true,
            error: false
        }
    },
    computed: {
        decoded_url() {
            return atob(this.url)
        }
    },
    created() {
        axios.get(this.decoded_url).then(res => {
        }).catch(err => {
            this.error = true
        })
            .finally(res => {
                this.loading = false
            })
    }
}
</script>

<style scoped>

</style>
