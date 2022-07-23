<template>
    <div class="merchant-setup">

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
        <div class="container merchant-setup-content">
            <div class="row">
                <div class="col-md-12">
                    <router-view v-bind.sync="params" @country_id="params.form.country_id=$event"></router-view>
                </div>
            </div>
        </div>
        <div class="bg-white py-5 text-center text-blue mt-5">
            © {{ new Date().getFullYear() }} {{ $t('all_rights') }} - Classic Parts Finder
        </div>

    </div>
</template>

<script>
import {mapActions} from "vuex";

export default {
    beforeRouteEnter(to, from, next) {
        next(vm => {
            vm.redirectIfUnauthorized()
        })
    },
    data() {
        return {
            params: {
                form: {
                    merchantType: this.$route.params.type,
                    firstname: null,
                    lastname: null,
                    merchantName: null,
                    merchantSiret: null,
                    address: null,
                    city: null,
                    zip: null,
                    phone: null,
                    country_id: null,
                    region: null
                },
                user: {
                    email: null,
                    password: null,
                    repeat_password: null,
                    cgv: false
                },
            }
        }
    },
    methods: {
        redirectIfUnauthorized() {
            const user = this.$auth.user()
            if (!user) {
                return true
            }
            if (!user.merchant) {
                if (this.$route.params.type === 'Particulier') {
                    return this.createMerchant()
                }
                return true
            }
            if (user.merchant.merchantType === 'Pro') {
                return this.$router.push({name: 'dashboard'}).catch(() => {})
            }
            if (user.merchant.merchantType === 'Particulier') {
                return this.$router.push({name: 'MerchantSettings', params: {type: 'Pro'}}).catch(() => {})
            }
        },
        createMerchant() {
            this.params.form.address = this.$auth.user().address;
            this.params.form.firstname = this.$auth.user().firstname;
            this.params.form.lastname = this.$auth.user().lastname;
            this.params.form.zip = this.$auth.user().zip;
            // this.params.form.country_id = this.$auth.user().country_id;
            this.params.form.city = this.$auth.user().city;
            this.params.form.phone = this.$auth.user().phone;
            this.params.form.merchantType = 'Particulier';
            // this.params.form.region = this.$auth.user().region.id;
            // this.addMerchant();
        },
        addMerchant() {
            let app = this;
            this.newMerchant(this.params.form)
                .then((res) => {
                    app.$auth.fetch()
                    app.$router.push({name: 'MerchantConfirmation'}).catch(() => {})
                })
        },
        ...mapActions('merchant', ['newMerchant']),
    },
    // watch: {
    //     'params.form.country_id': {
    //         deep: true,
    //         handler() {
    //             this.params.form.region = null;
    //             console.log('cuicui',this.params.form.region)
    //         }
    //     }
    // },
}
</script>
