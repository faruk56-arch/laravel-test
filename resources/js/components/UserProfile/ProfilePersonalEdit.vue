<template>

    <div class="p-2 max-700">

        <h3 class="h3-serif text-blue mb-4"><strong>{{ $t('personal_details') }}</strong></h3>

      <div class="card mb-4">
          <div class="card-body">
              <h5>Stripe Connect</h5>

              <div v-if="seller.completed_stripe_onboarding" class="align-items-center d-flex flex-row justify-content-between">
                  <span class="badge badge-success text-white p-2" style="line-height: 2">Connected</span>
                  <button @click="greet(seller.id)" class="btn btn-primary">View stripe account</button>
              </div>
              <div v-else class="align-items-center d-flex flex-row justify-content-between">
                  <span class="badge badge-danger text-white p-2" style="line-height: 2">Not Connected</span>
                  <button @click="greet(seller.id)" class="btn btn-primary">Connect stripe account</button>
              </div>
          </div>
      </div>


        <div class="alert bg-primary text-white p-4 seller-img-bg" v-if="!hasMerchant">
            <i18n tag="h3" path="sell_parts" class="h3-serif mb-2 zindex-top mt-2"><br></i18n>
            <p class="mb-1 zindex-top">{{ $t('publish_products_fast') }}</p>
            <a v-if="$i18n.locale=='fr'" class="btn bg-white btn-sm zindex-top mb-2"
               href="https://classic-parts-finder.com/vendre-des-pieces/">
                {{ $t('activate_seller_account') }}
            </a>
            <a v-else-if="$i18n.locale=='en'" class="btn bg-white btn-sm zindex-top mb-2"
               :href="'https://classic-parts-finder.com/' + $i18n.locale + '/sell-parts/'">
                {{ $t('activate_seller_account') }}
            </a>
            <a v-else class="btn bg-white btn-sm zindex-top mb-2"
               :href="'https://classic-parts-finder.com/' + $i18n.locale + '/teile-verkaufen/'">
                {{ $t('activate_seller_account') }}
            </a>
        </div>
        <ValidationObserver ref="form" v-slot="{ handleSubmit, failed }">
            <div class="alert alert-success" role="alert" v-if="success">
                <i class="fal fa-thumbs-up"></i>
                {{ $t('all_modifications_taken') }}
            </div>
            <div class="alert alert-danger" role="alert" v-if="failed">
                <i class="fal fa-exclamation-circle"></i>
                {{ $t('error_happened_wrong_param') }}
            </div>
            <form class="form-sm" @submit.prevent="handleSubmit(onSubmit)">
                <div class="row">
                    <ValidationProvider name="nom" rules="required|checkName|min:2" v-slot="{ errors }"
                                        vid="lastname" class="col-md-6">
                        <div class="form-group" :class="{'error': errors.length}">
                            <label for="lastname" class="text-blue">{{ $t('last_name') }}</label>
                            <input type="text" v-model="payload.lastname" class="form-control" id="lastname">
                            <span>{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>
                    <ValidationProvider name="prénom" rules="required|checkName|min:2" v-slot="{ errors }"
                                        vid="firstname" class="col-md-6">
                        <div class="form-group" :class="{'error': errors.length}">
                            <label for="firstname" class="text-blue">{{ $t('first_name') }}</label>
                            <input type="text" v-model="payload.firstname" class="form-control" id="firstname">
                            <span>{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>
                </div>
                <ValidationProvider name="e-mail" rules="required|email" v-slot="{ errors }" vid="email">
                    <div class="form-group" :class="{'error': errors.length}">
                        <label for="email" class="text-blue">{{ $t('email') }}</label>
                        <input type="email" v-model="payload.email" class="form-control" id="email">
                        <span>{{ errors[0] }}</span>
                        <span class="text-warning" v-if="new_email"><small><i
                            class="far fa-hourglass-half"></i> {{ $t('email_changing_to') }} {{
                                new_email
                            }}</small></span>
                    </div>
                </ValidationProvider>
                <ValidationProvider name="adresse" rules="required" v-slot="{ errors }" vid="address">
                    <div class="form-group" :class="{'error': errors.length}">
                        <label for="address" class="text-blue">{{ $t('address') }}</label>
                        <input type="text" v-model="payload.address" class="form-control" id="address">
                        <span>{{ errors[0] }}</span>
                    </div>
                </ValidationProvider>
                <div class="row">
                    <ValidationProvider name="code postal" rules="required" v-slot="{ errors }" vid="zip"
                                        class="col-md-6">
                        <div class="form-group" :class="{'error': errors.length}">
                            <label for="zip" class="text-blue">{{ $t('zip_code') }}</label>
                            <input type="text" v-model="payload.zip" class="form-control" id="zip">
                            <span>{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>
                    <ValidationProvider name="ville" rules="required" v-slot="{ errors }" vid="city"
                                        class="col-md-6">
                        <div class="form-group" :class="{'error': errors.length}">
                            <label for="city" class="text-blue">{{ $t('city') }}</label>
                            <input type="text" v-model="payload.city" class="form-control" id="city">
                            <span>{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>
                </div>
                <div class="row">
                    <ValidationProvider name="pays" rules="required" v-slot="{ errors }" vid="country_id"
                                        class="col-md-4">
                        <div class="form-group" :class="{'error': errors.length}">
                            <label for="country_id" class="text-blue">{{ $t('country') }}</label>
                            <select class="form-control" v-model="payload.country_id" id="country_id">
                                <option v-for="country in countries" :value="country.id"
                                        :key="'country-' + country.id + '-' + Math.floor(Math.random() * 1000)">
                                    {{ country.name }}
                                </option>
                            </select>
                            <span>{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>
                    <ValidationProvider :disabled="!regions" name="région" v-slot="{ errors }" vid="region"
                                        class="col-md-4">
                        <div class="form-group" :class="{'error': errors.length}">
                            <label for="region" class="text-blue">{{ $t('region') }}</label>
                            <select class="form-control" v-model="payload.region" id="region">
                                <option v-for="(region,index) in regions" :value="parseInt(region.id)">
                                    {{ region.name }}
                                </option>
                                <template v-if="![18,38,51,62,69,71,100,123,153].includes(payload.country_id)">
                                    <option disabled value="">------------</option>
                                    <option :value="0">{{ $t('other') }}</option>
                                </template>
                            </select>
                            <span>{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>
                    <ValidationProvider name="langues parlées" v-slot="{ errors }" vid="lang" class="col-md-4">
                        <div class="form-group" :class="{'error': errors.length}">
                            <label for="lang" class="text-blue">{{ $t('spoken_langage') }}</label>
                            <input type="text" v-model="payload.lang" class="form-control" id="lang"
                                   :placeholder="$t('french_german')">
                            <span>{{ errors[0] }}</span>
                        </div>
                    </ValidationProvider>
                </div>
                <ValidationProvider name="numéro de téléphone" v-slot="{ errors }" vid="phone">
                    <div class="form-group" :class="{'error': errors.length}">
                        <label for="phone" class="text-blue">{{ $t('phone_number') }}</label>
                        <input type="text" v-model="payload.phone" class="form-control" id="phone"
                               placeholder="+33...">
                        <span>{{ errors[0] }}</span>
                    </div>
                </ValidationProvider>
                <button class="btn bg-gold btn-sm mt-4" type="submit">
                    {{ $t('save_changes') }}
                </button>
            </form>
        </ValidationObserver>


    </div>
</template>
<script>
import validationProfileMixin from "../../mixins/validationProfileMixin";

export default {
    data() {
        return {
            seller: null,
            balance: null,
            payload: {
                firstname: this.$auth.user().firstname,
                lastname: this.$auth.user().lastname,
                email: this.$auth.user().email,
                address: this.$auth.user().address,
                lang: this.$auth.user().lang,
                region: this.$auth.user().region && parseInt(this.$auth.user().region.id),
                country_id: this.$auth.user().country_id,
                city: this.$auth.user().city,
                zip: this.$auth.user().zip,
                phone: this.$auth.user().phone,

            },
        }
    },
    name: 'ProfilPersonnalEdit',
    mixins: [validationProfileMixin],
    methods: {
        onSubmit() {
            axios.put('user/' + this.$auth.user().id, {...this.payload}).then(res => {
                this.success = true
                this.$auth.fetch()
            }).catch(err => {
                this.$refs.form.setErrors({
                    ...err.response.data.errors
                });
            })
        },
        getSellerById: function () {
            axios.get('seller/' + this.$auth.user().merchant_id)
                .then((response) => {
                    this.seller = response.data.seller;
                    this.balance = response.data.balance;
                });
        },
        greet(id) {
            window.open(`/stripe/${id}`)
        }
        ,
    },
    computed: {
        new_email() {
            const meta = this.$auth.user().meta
            if (!meta) {
                return null
            }
            const new_email = meta.find(el => el.key === 'new_email')
            if (!new_email) {
                return null
            }
            return new_email.value
        },
    },
    created: function () {
        this.getSellerById()
    }
}
</script>
