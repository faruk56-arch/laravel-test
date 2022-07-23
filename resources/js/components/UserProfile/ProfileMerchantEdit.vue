<template>
    <div class="p-2 max-700">
        <h3 class="h3-serif text-blue mb-4"><strong>{{ $t('vendor_parameters') }}</strong></h3>
        <div class="alert bg-primary text-white p-4 seller-img-bg" v-if="!hasMerchant">
            <i18n tag="h3" path="sell_parts" class="h3-serif mb-2 zindex-top mt-2"><br></i18n>
            <p class="mb-1 zindex-top">{{ $t('diffuse_products') }}</p>
            <a v-if="$i18n.locale=='fr'" class="btn bg-white btn-sm zindex-top mb-2" href="https://classic-parts-finder.com/vendre-des-pieces/">
                {{ $t('activate_vendor') }}
            </a>
            <a v-else-if="$i18n.locale=='en'" class="btn bg-white btn-sm zindex-top mb-2" :href="'https://classic-parts-finder.com/' + $i18n.locale + '/sell-parts'">
                {{ $t('activate_vendor') }}
            </a>
            <a v-else class="btn bg-white btn-sm zindex-top mb-2" :href="'https://classic-parts-finder.com/' + $i18n.locale + '/teile-verkaufen'">
                {{ $t('activate_vendor') }}
            </a>
        </div>
        <ValidationObserver ref="form" v-slot="{ handleSubmit, failed }" v-if="isPro">
            <div class="alert alert-success" role="alert" v-if="success">
                <i class="fal fa-thumbs-up"></i>
                {{ $t('all_modifications_taken') }}
            </div>
            <div class="alert alert-danger" role="alert" v-if="failed">
                <i class="fal fa-exclamation-circle"></i>
                {{ $t('error_happened_wrong_param') }}
            </div>
            <form class="form-sm" @submit.prevent="handleSubmit(onSubmit)">
                <ValidationProvider name="nom de la boutique" rules="required" v-slot="{ errors }" vid="merchant_name">
                    <div class="form-group" :class="{'error': errors.length}">
                        <label for="merchant_name" class="text-blue">{{ $t('business_name') }}</label>
                        <input type="text" v-model="payload.merchant_name" class="form-control" id="merchant_name">
                        <span>{{ errors[0] }}</span>
                    </div>
                </ValidationProvider>
                <ValidationProvider name="adresse de la boutique" rules="required" v-slot="{ errors }" vid="merchant_address">
                    <div class="form-group" :class="{'error': errors.length}">
                        <label for="merchant_address" class="text-blue">{{ $t('business_address') }}</label>
                        <input type="text" v-model="payload.merchant_address" class="form-control" id="merchant_address">
                        <span>{{ errors[0] }}</span>
                    </div>
                </ValidationProvider>
                <div class="row">
                    <ValidationProvider name="code postal" rules="required" v-slot="{ errors }" vid="merchant_zip" class="col-md-6">
                        <div class="form-group" :class="{'error': errors.length}">
                            <label for="merchant_zip" class="text-blue">{{ $t('zip_code') }}</label>
                            <input type="text" v-model="payload.merchant_zip" class="form-control" id="merchant_zip">
                            <span>{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>
                    <ValidationProvider name="ville" rules="required" v-slot="{ errors }" vid="merchant_city" class="col-md-6">
                        <div class="form-group" :class="{'error': errors.length}">
                            <label for="merchant_city" class="text-blue">{{ $t('city') }}</label>
                            <input type="text" v-model="payload.merchant_city" class="form-control" id="merchant_city">
                            <span>{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>
                </div>
                <div class="row">
                    <ValidationProvider name="pays" rules="required" v-slot="{ errors }" vid="country_id" class="col-md-6">
                        <div class="form-group" :class="{'error': errors.length}">
                            <label for="country_id" class="text-blue">{{ $t('country') }}</label>
                            <select class="form-control" v-model="payload.country_id" id="country_id">
                                <option v-for="country in countries" :value="country.id" :key="'country-' + country.id + '-' + Math.floor(Math.random() * 1000)">{{ country.name }}</option>
                            </select>
                            <span>{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>
                    <ValidationProvider :disabled="!regions" name="région" rules="required" v-slot="{ errors }" vid="region" class="col-md-6">
                        <div class="form-group" :class="{'error': errors.length}">
                            <label for="region" class="text-blue">{{ $t('region') }}</label>
                            <select class="form-control" v-model="payload.region" id="region">
                                <option v-for="(region,index) in regions" :value="parseInt(region.id)" :key="region.name+'-' + Math.floor(Math.random() * 1000)">{{ region.name }}</option>
                                <template v-if="![18,38,51,62,69,71,100,123,153].includes(payload.country_id)">
                                    <option disabled value="">------------</option>
                                    <option :value="0">{{ $t('other') }}</option>
                                </template>
                            </select>
                            <span>{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>
                </div>
                <ValidationProvider name="numéro de Siret" rules="required|min:14|max:14|numeric" v-slot="{ errors }" vid="merchant_siret" v-if="payload.country_id === 69">
                    <div class="form-group" :class="{'error': errors.length}">
                        <label for="merchant_siret" class="text-blue">{{ $t('siret') }}</label>
                        <input type="text" maxlength="14" v-model="payload.merchant_siret" class="form-control" id="merchant_siret">
                        <span>{{ errors[0] }}</span>
                    </div>
                </ValidationProvider>
                <ValidationProvider name="numéro de téléphone" rules="required|numeric" v-slot="{ errors }" vid="phone">
                    <div class="form-group" :class="{'error': errors.length}">
                        <label for="phone" class="text-blue">{{ $t('phone_number') }}</label>
                        <input type="text" v-model="payload.phone" class="form-control" id="phone">
                        <span>{{ errors[0] }}</span>
                    </div>
                </ValidationProvider>
                <button type="submit" class="btn bg-gold btn-sm mt-4">
                    {{ $t('save_changes') }}
                </button>
            </form>
        </ValidationObserver>
        <div class="alert alert-info" role="alert" v-else-if="hasMerchant">
            <p class="mb-2">
                {{ $t('account_info_linked_business') }}
            </p>
            <router-link :to="{name:'MerchantSettings',params:{type:'Pro'}}" class="btn btn-info btn-sm">
                {{ $t('become_pro') }}
            </router-link>
        </div>
    </div>
</template>
<script>
import validationProfileMixin from "../../mixins/validationProfileMixin";

export default {
    name: 'ProfilMerchantEdit',
    mixins: [validationProfileMixin],
    data() {
        return {
            payload: {
                merchant_city: this.$auth.user().merchant.merchant_city,
                merchant_address: this.$auth.user().merchant.merchant_address,
                merchant_zip: this.$auth.user().merchant.merchant_zip,
                merchant_siret: this.$auth.user().merchant.merchant_siret,
                merchant_name: this.$auth.user().merchant.merchant_name,
                country_id: this.$auth.user().merchant.country_id,
                region: this.$auth.user().merchant.region && parseInt(this.$auth.user().merchant.region.id),
                phone: this.$auth.user().merchant.phone
            },
        }
    },
    methods: {
        onSubmit() {
            axios.put('merchant/' + this.hasMerchant.id, {...this.payload}).then(res => {
                this.success = true
            }).catch(err => {
                this.$refs.form.setErrors({
                    ...err.response.data.errors
                });
            })
        },
    },
    computed: {
        isPro() {
            return this.hasMerchant && this.hasMerchant.merchant_type === 'Pro'
        },
    }
}
</script>
