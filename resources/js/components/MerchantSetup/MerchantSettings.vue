<template>
    <div class="merchant-settings row">
        <div class="col-12">
            <h1 class="text-blue h2 h2-line h2-line-center h2-carter py-5 mb-0 mt-4">{{ $t('registration') }}</h1>
        </div>
        <div class="col-md-6 offset-md-3 form-sm">
            <a class="basic text-bold-dark-grey d-inline-block mb-2"  @click="$router.go(-1)">
                <i class="fal fa-long-arrow-left"></i>
                {{ $t('back') }}
            </a>
            <b-form>
                <div class="row" v-if="!$auth.check()">
                    <b-form-group class="col-md-6">
                        <label for="firstname">{{ $t('votre_prnom') }} <sup class="text-danger">*</sup></label>
                        <b-form-input
                            id="firstname"
                            @input="changeParent('firstname')"
                            v-model="$v.form.firstname.$model"
                            required
                            :state="validateState('firstname')"
                            placeholder=""
                        ></b-form-input>
                        <b-form-invalid-feedback id="firstname">
                            <span v-if="!$v.form.firstname.required">{{ $t('field_required') }}</span>
                            <span v-if="!$v.form.firstname.minLength">{{ $t('au_moins_2_caractres') }}</span>
                            <span v-if="!$v.form.firstname.alpha">{{ $t('field_alpha') }}</span>
                        </b-form-invalid-feedback>
                    </b-form-group>
                    <b-form-group class="col-md-6">
                        <label for="lastname">{{ $t('votre_nom') }} <sup class="text-danger">*</sup></label>
                        <b-form-input
                            id="lastname"
                            @input="changeParent('lastname')"
                            v-model="$v.form.lastname.$model"
                            required
                            :state="validateState('lastname')"
                            placeholder=""
                        ></b-form-input>
                        <b-form-invalid-feedback id="lastname">
                            <span v-if="!$v.form.lastname.required">{{ $t('field_required') }}</span>
                            <span v-if="!$v.form.lastname.minLength">{{ $t('au_moins_2_caractres') }}</span>
                            <span v-if="!$v.form.lastname.alpha">{{ $t('field_alpha') }}</span>
                        </b-form-invalid-feedback>
                    </b-form-group>
                </div>

                <template v-if="$route.params.type == 'Pro'">
                    <b-form-group>
                        <label for="merchant-name">{{ $t('nom_de_lentreprise') }} <sup class="text-danger">*</sup></label>
                        <b-form-input
                            id="merchant-name"
                            v-model="$v.form.merchantName.$model"
                            :state="validateState('merchantName')"
                            required
                            placeholder=""
                        ></b-form-input>
                        <b-form-invalid-feedback id="merchant-name">
                            <span v-if="!$v.form.merchantName.required">{{ $t('field_required') }}</span>
                        </b-form-invalid-feedback>
                    </b-form-group>
                    <div class="row">
                        <b-form-group class="col-6">
                            <label for="country_id">{{ $t('pays_de_lentreprise') }} <sup class="text-danger">*</sup></label>
                            <b-form-select
                                id="country_id"
                                name="country_id"
                                @change="changeParent('country_id');country = countries.find(c=>c.id===$event);form.region = null;"
                                v-model="$v.form.country_id.$model"
                                :state="validateState('country_id')"
                                autocomplete="country_id"
                                aria-describedby="country_id-feedback"
                                :options="countries"
                                value-field="id"
                                text-field="name"
                            />
                            <b-form-invalid-feedback id="country_id-feedback">
                                <span v-if="!$v.form.country_id.required">{{ $t('field_required') }}</span>
                                <span v-if="!$v.form.country_id.alpha">{{ $t('valeurs_alphabtiques_uniquement') }}</span>
                            </b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group class="col-6 ">
                            <label for="region">{{ $t('region') }}
                                <sup class="text-danger" v-if="$route.params.type === 'Pro'">*</sup></label>
                            <b-form-select
                                id="region"
                                name="region"
                                @change="changeParent('region')"
                                v-model="$v.form.region.$model"
                                :state="validateState('region')"
                                autocomplete="region"
                                aria-describedby="region-feedback"
                                :options="country.regions"
                                value-field="id"
                                text-field="name"
                                v-if="[18,38,51,62,69,71,100,123,153].includes(country.id)"
                            />
                            <b-form-select
                                id="region"
                                name="region"
                                @change="changeParent('region')"
                                v-model="$v.form.region.$model"
                                :state="validateState('region')"
                                autocomplete="region"
                                aria-describedby="region-feedback"
                                :options="[{id:0,name:'Autre'}]"
                                value-field="id"
                                text-field="name"
                                v-else
                            />
                            <b-form-invalid-feedback id="country-feedback">
                                <span v-if="!$v.form.region.required">{{ $t('field_required') }}</span>
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </div>

                    <div class="pt-4">
                        <h3 class="text-blue mb-0">{{ $t('identit_de_lentreprise') }}</h3>
                        <p class="text-blue">{{ $t('informations_non_visibles_par_les_autres_utilisate') }}</p>
                        <b-form-group>
                            <label for="address">{{ $t('adresse_de_lentreprise') }} <sup class="text-danger">*</sup></label>
                            <b-form-input
                                id="address"
                                @input="changeParent('address')"
                                name="address"
                                v-model="$v.form.address.$model"
                                :state="validateState('address')"
                                autocomplete="address"
                                aria-describedby="address-feedback"
                            />
                            <b-form-invalid-feedback id="address-feedback">
                                <span v-if="!$v.form.address.required">{{ $t('field_required') }}</span>
                            </b-form-invalid-feedback>
                        </b-form-group>

                        <div class="row">
                            <b-form-group class="col-md-6">
                                <label for="city">{{ $t('city') }} <sup class="text-danger">*</sup></label>
                                <b-form-input
                                    id="city"
                                    name="city"
                                    @input="changeParent('city')"
                                    v-model="$v.form.city.$model"
                                    :state="validateState('city')"
                                    autocomplete="city"
                                    aria-describedby="city-feedback"
                                />
                                <b-form-invalid-feedback id="city-feedback">
                                    <span v-if="!$v.form.city.required">{{ $t('field_required') }}</span>
                                </b-form-invalid-feedback>
                            </b-form-group>
                            <b-form-group class="col-md-6">
                                <label for="zip">{{ $t('zip_code') }} <sup class="text-danger">*</sup></label>
                                <b-form-input
                                    id="zip"
                                    name="zip"
                                    v-model="$v.form.zip.$model"
                                    :state="validateState('zip')"
                                    @input="changeParent('zip')"
                                    autocomplete="zip"
                                    aria-describedby="zip-feedback"
                                />
                                <b-form-invalid-feedback id="zip-feedback">
                                    <span v-if="!$v.form.zip.required">{{ $t('field_required') }}</span>
                                    <span v-if="!$v.form.zip.integer">{{ $t('valeur_numrique_uniquement') }}</span>
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </div>
                        <b-form-group v-if="form.country_id === 69">
                            <label for="merchant-siret">{{ $t('n_de_siret') }} <sup class="text-danger">*</sup></label>
                            <b-form-input
                                id="merchant-siret"
                                v-model="$v.form.merchantSiret.$model"
                                :state="validateState('merchantSiret')"
                                :required="form.country_id === 69"
                                maxLength="14"
                                placeholder=""
                            ></b-form-input>
                            <b-form-invalid-feedback id="merchant-siret">
                                <span v-if="!$v.form.merchantSiret.required">{{ $t('field_required') }}</span>
                                <span v-if="!$v.form.merchantSiret.numeric">{{ $t('valeur_numrique_uniquement') }}</span>
                                <span v-if="!$v.form.merchantSiret.length">{{ $t('doit_tre_compos_de_14_chiffres') }}</span>
                            </b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group>
                            <label for="phone">{{ $t('phone_number') }}
                                <sup class="text-danger" v-if="$route.params.type === 'Pro'">*</sup></label>
                            <b-form-input
                                id="phone"
                                v-model="$v.form.phone.$model"
                                :state="validateState('phone')"
                                maxLength="14"
                                placeholder=""
                            ></b-form-input>
                            <b-form-invalid-feedback id="phone">
                                <span v-if="!$v.form.phone.required">{{ $t('field_required') }}</span>
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </div>
                </template>
                <template v-else>

                    <b-form-group>
                        <label for="address">{{ $t('address') }} <sup class="text-danger">*</sup></label>
                        <b-form-input
                            id="address"
                            name="address"
                            v-model="$v.form.address.$model"
                            :state="validateState('address')"
                            @input="changeParent('address')"
                            autocomplete="address"
                            aria-describedby="address-feedback"
                        />
                        <b-form-invalid-feedback id="address-feedback">
                            <span v-if="!$v.form.address.required">{{ $t('field_required') }}</span>
                        </b-form-invalid-feedback>
                    </b-form-group>

                    <div class="row">
                        <b-form-group class="col-12 col-md-6">
                            <label for="city">{{ $t('city') }} <sup class="text-danger">*</sup></label>
                            <b-form-input
                                id="city"
                                name="city"
                                v-model="$v.form.city.$model"
                                :state="validateState('city')"
                                autocomplete="city"
                                aria-describedby="city-feedback"
                            />
                            <b-form-invalid-feedback id="city-feedback">
                                <span v-if="!$v.form.city.required">{{ $t('field_required') }}</span>
                            </b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group class="col-12 col-md-6">
                            <label for="zip">{{ $t('zip_code') }} <sup class="text-danger">*</sup></label>
                            <b-form-input
                                id="zip"
                                name="zip"
                                v-model="$v.form.zip.$model"
                                @input="changeParent('zip')"
                                :state="validateState('zip')"
                                autocomplete="zip"
                                aria-describedby="zip-feedback"
                            />
                            <b-form-invalid-feedback id="zip-feedback">
                                <span v-if="!$v.form.zip.required">{{ $t('field_required') }}</span>
                            </b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group class="col-6 ">
                            <label for="country_id">{{ $t('country') }} <sup class="text-danger">*</sup></label>
                            <b-form-select
                                id="country_id"
                                name="country_id"
                                @change="changeParent('country_id');country = countries.find(c=>c.id===$event);form.region = null;"
                                v-model="$v.form.country_id.$model"
                                :state="validateState('country_id')"
                                autocomplete="country_id"
                                aria-describedby="country_id-feedback"
                                :options="countries"
                                value-field="id"
                                text-field="name"
                            />
                            <b-form-invalid-feedback id="country-feedback">
                                <span v-if="!$v.form.country_id.required">{{ $t('field_required') }}</span>
                                <span v-if="!$v.form.country_id.alpha">{{ $t('valeurs_alphabtiques_uniquement') }}</span>
                            </b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group class="col-6">
                            <label for="region">{{ $t('region') }}
                                <sup class="text-danger" v-if="$route.params.type === 'Pro'">*</sup></label>
                            <b-form-select
                                id="region"
                                name="region"
                                @change="changeParent('region')"
                                v-model="$v.form.region.$model"
                                :state="validateState('region')"
                                autocomplete="region"
                                aria-describedby="region-feedback"
                                :options="country.regions"
                                value-field="id"
                                text-field="name"
                                v-if="[18,38,51,62,69,71,100,123,153].includes(country.id)"
                            />
                            <b-form-select
                                id="region"
                                name="region"
                                @change="changeParent('region')"
                                v-model="$v.form.region.$model"
                                :state="validateState('region')"
                                autocomplete="region"
                                aria-describedby="region-feedback"
                                :options="[{id:0,name:'Autre'}]"
                                value-field="id"
                                text-field="name"
                                v-else
                            />
                            <b-form-invalid-feedback id="country-feedback">
                                <span v-if="!$v.form.region.required">{{ $t('field_required') }}</span>
                            </b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group class="col-12">
                            <label for="merchant-phone">{{ $t('phone_number') }}
                                <sup class="text-danger" v-if="$route.params.type === 'Pro'">*</sup></label>
                            <b-form-input
                                id="merchant-phone"
                                v-model="$v.form.phone.$model"
                                :state="validateState('phone')"
                                maxLength="14"
                                placeholder=""
                            ></b-form-input>
                            <b-form-invalid-feedback id="merchant-phone">
                                <span v-if="!$v.form.phone.required">{{ $t('field_required') }}</span>
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </div>
                </template>
            </b-form>

            <div class="text-right mt-3" @click="$v.form.$touch()">
                <div class="btn bg-blue btn-lg" v-if="!$auth.check()" :class="$v.form.$anyError||!privateUserFields||(((form.merchantType=='Pro' && form.country_id === 69)&&!form.merchantSiret )||(form.merchantType=='Particulier'&&!form.country_id))?'disabled':''" @click="nextPage">{{ $t('suivant') }}</div>
                <div class="btn bg-blue btn-lg" v-else @click="nextPage" :class="$v.form.$anyError?'disabled':''">{{ $t('finish') }}</div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions} from "vuex";
import {minLength, maxLength, required, numeric, and, requiredIf} from "vuelidate/lib/validators";
import requiredUnless from "vuelidate/lib/validators/requiredUnless";
import {validationMixin} from "vuelidate";
import alpha from 'vuelidate/lib/validators/alpha';

export default {
    name: "MerchantSettings",
    props: ['form'],
    data() {
        return {
            countries: [],
            country: {}

        }
    },
    mixins: [validationMixin],

    validations: {
        form: {
            firstname: {
                required: requiredUnless(function () {
                    return this.form.firstname || this.$auth.check()
                }),
                minLength: minLength(2),
                validName:(value)=>{
                    let re = new RegExp("^[A-Za-zÀ-ÖØ-öø-ÿ]'?[- A-Za-zÀ-ÖØ-öø-ÿ]+$")
                    return re.test(value);
                }
            },
            lastname: {
                required: requiredUnless(function () {
                    return this.form.lastname || this.$auth.check()
                }),
                minLength: minLength(2),
                validName:(value)=>{
                    let re = new RegExp("^[A-Za-zÀ-ÖØ-öø-ÿ]'?[- A-Za-zÀ-ÖØ-öø-ÿ]+$")
                    return re.test(value);
                }
            },
            merchantSiret: {
                required: requiredUnless(function () {
                    return this.form.merchantSiret || this.form.merchantType == 'Particulier' || this.form.country_id !== 69
                }),
                length: and(minLength(14), maxLength(14)),
                numeric
            },
            phone: {
                required: requiredIf(function () {
                    return this.$route.params.type === 'Pro'
                }),
            },
            merchantName: {
                required: requiredUnless(function () {
                    return this.form.merchantName || this.form.merchantType == 'Particulier'
                })
            },
            zip: {
                required,
            },
            region: {
                required: requiredIf(function () {
                    return this.$route.params.type === 'Pro'
                }),
            },
            country_id: {
                required,
            }, city: {
                required,
            }, address: {
                required,
            },
        }
    },
    beforeCreate() {

    },
    async created() {
        if (this.$auth.check()) {
            this.$v.form.firstname.$touch()
            this.$v.form.lastname.$touch()
        }
        let app = this;
        await axios.get('/pays').then((res) => {
            this.countries = res.data;
            this.countries.unshift({id:null, name:"Choisir un pays"})
            if(this.form.country_id)this.country = this.countries.find(c=>c.id==this.form.country_id)
        });
        if (this.$auth.user() && this.$auth.user().merchant_id && (this.$route.params.type == this.$auth.user().merchant.merchant_type)) this.$router.push({name: 'dashboard'})
        else if (this.$auth.user() && this.$auth.user().merchant_id) {//si conversion de compte
            this.form.country_id = this.$auth.user().merchant.country_id;
            this.country = this.countries.find(c=>c.id==this.form.country_id)
            this.form.zip = this.$auth.user().merchant.merchant_zip;
            this.form.address = this.$auth.user().merchant.merchant_address;
            this.form.city = this.$auth.user().merchant.merchant_city;
            this.form.merchantName = this.$auth.user().merchant.merchant_name;
            this.form.region = this.$auth.user().merchant.region.id;
            this.form.phone = this.$auth.user().merchant.phone
            if(this.form.address)this.$v.form.$touch();
        }else if(this.$auth.user()&&this.$route.params.type=="Particulier") {//si nouveau marchand particulier
            this.form.country_id = this.$auth.user().country_id
            this.country = this.countries.find(c=>c.id==this.form.country_id)
            this.form.region = this.$auth.user().region.id;
            if(this.form.address)this.$v.form.$touch();
        }
        // else {  //si nouveau compte
        //     this.country = this.countries[3]
        //     this.form.country_id = this.country.id
        //     require('jsonp')('https://get.geojs.io/v1/ip/country.js', null, (err, data) => {
        //         if (err) {
        //             console.error(err.message);
        //         } else {
        //             let country = this.countries.find(c => c.name === data.name);
        //             if (country) {
        //                 this.country = country
        //                 this.form.country_id = country.id
        //             }
        //         }
        //     });
        // }
    },
    mounted() {

    },
    methods: {
        validateState(name) {
            const {$dirty, $error} = this.$v.form[name];
            return $dirty ? !$error : null;
        },
        nextPage() {
            let app = this;
            this.$v.form.$touch();
            if (this.$v.form.$anyError) return;
            else if (this.$auth.user() && this.$auth.user().merchant_id) {
                this.form.merchant_zip = this.form.zip;
                this.form.merchant_address = this.form.address;
                this.form.merchant_name = this.form.merchantName;
                this.form.merchant_city = this.form.city;
                this.form.merchant_siret = this.form.merchantSiret;
                this.form.merchant_type = this.form.merchantType;
                this.updateMerchant(this.form)
                    .then((res) => {
                        app.$auth.fetch().then(()=>{
                            app.$router.push({name: 'profileMerchantEdit'}).catch(() => {})
                        })
                    })
            } else if (this.$auth.check()) {
                this.newMerchant(this.form)
                    .then((res) => {
                        app.$auth.fetch()
                        app.$router.push({name: 'MerchantConfirmation'}).catch(() => {})
                    })
            } else {
                app.$router.push({name: 'register'}).catch(() => {})
            }
        },
        changeParent(event, name) {
            this.$emit('update:params.' + name, event)
        },
        changeCountry(event) {
            this.$emit('country_id', event);
        },
        ...mapActions('merchant', ['newMerchant', 'updateMerchant']),

    },
    computed: {
        privateUserFields() {
            if (((this.$auth.user() && this.$auth.user().merchant_id && this.$auth.user().merchant.merchant_type === 'Pro') || this.$route.params.type === 'Pro') && !this.form.phone)  {
                return false
            }
            if (this.$auth.user() && !this.$auth.user().merchant_id) return this.form.firstname && this.form.lastname && this.form.address && this.form.zip && this.form.city && this.form.country_id && this.form.region !== null
            else return this.form.address && this.form.zip && this.form.city && this.form.country_id ? true : false
        }
    }
}
</script>

<style scoped>

</style>
