<template>
    <div class="reset-password">

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

        <div class="container">

            <div class="row pt-5">
                <div class="col-12" v-if="loaded && !done">
                    <h1 class="text-blue h2 h2-carter text-center mb-3 mt-4">{{ $t('last_step') }}</h1>
                    <p class="h4 text-blue text-center mb-5">{{ $t('complementary_details_required') }}</p>

                    <b-form class="row" @submit.prevent="submit">

                        <div class="col-md-6 offset-md-3">
                            <div v-if="!merchant" class="row">
                                <b-form-group class="form-sm col-md-6">
                                    <label for="firstname">{{ $t('first_name') }} <sup class="text-danger">*</sup></label>
                                    <b-form-input
                                        id="firstname"
                                        type="text"
                                        name="firstname"
                                        autocomplete="firstname"
                                        aria-describedby="firstname-feedback"
                                        v-model="$v.form.firstname.$model"
                                        :state="validateState('firstname')"

                                    >
                                    </b-form-input>
                                    <b-form-invalid-feedback id="firstname-feedback">
                                        <span v-if="!$v.form.firstname.required">{{ $t('field_required') }}</span>
                                        <span v-if="!$v.form.firstname.minLength">{{ $t('at_least_x_char', {'0': 2}) }}</span>
                                        <span v-if="!$v.form.firstname.alpha">{{ $t('field_alpha') }}</span>
                                    </b-form-invalid-feedback>
                                </b-form-group>
                                <b-form-group class="form-sm col-md-6">
                                    <label for="lastname">{{ $t('last_name') }} <sup class="text-danger">*</sup></label>
                                    <b-form-input
                                        id="lastname"
                                        type="text"
                                        name="lastname"
                                        autocomplete="lastname"
                                        aria-describedby="lastname-feedback"
                                        v-model="$v.form.lastname.$model"
                                        :state="validateState('lastname')"

                                    >
                                    </b-form-input>
                                    <b-form-invalid-feedback id="lastname-feedback">
                                        <span v-if="!$v.form.lastname.required">{{ $t('field_required') }}</span>
                                        <span v-if="!$v.form.lastname.minLength">{{ $t('at_least_x_char', {'0': 2}) }}</span>
                                        <span v-if="!$v.form.lastname.alpha">{{ $t('field_alpha') }}</span>

                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </div>

                            <div v-if="!merchant" class="row">

                                <b-form-group
                                    id="input-group-6"
                                    class="form-sm col-md-6"
                                >
                                    <label for="country">{{ $t('country') }} <sup class="text-danger">*</sup></label>
                                    <b-form-select
                                        class="mb-2 mr-sm-2 mb-sm-0"
                                        id="country"
                                        :options="countries"
                                        value-field="id"
                                        @change="country=countries.find(c=>c.id===$event)"
                                        :state="validateState('country_id')"
                                        v-model="$v.form.country_id.$model"
                                        text-field="name"
                                    ></b-form-select>
                                    <b-form-invalid-feedback id="country">
                                        <span v-if="!$v.form.country_id.required">{{ $t('field_required') }}</span>
                                    </b-form-invalid-feedback>
                                </b-form-group>
                                <b-form-group
                                    id="input-group-3"
                                    :label="$t('region')"
                                    label-for="region"
                                    class="form-sm col-md-6"
                                >
                                    <b-form-select
                                        class="mb-2 mr-sm-2 mb-sm-0"
                                        id="region"
                                        :options="country.regions"
                                        value-field="id"
                                        v-model="form.region"
                                        text-field="name"
                                    ></b-form-select>
                                </b-form-group>


                            </div>
                            <b-form-group v-if="!merchant" class="form-sm">
                                <label for="phone">{{ $t('phone_number') }} </label>
                                <b-form-input
                                    id="phone"
                                    type="text"
                                    name="phone"
                                    autocomplete="phone"
                                    v-model="form.phone"
                                >
                                </b-form-input>
                            </b-form-group>



                            <b-form-group inline class="form-sm">
                                <label for="new-password">{{ $t('password') }} <sup class="text-danger">*</sup></label>
                                <b-form-input
                                    id="new-password"
                                    type="password"
                                    name="new-password"
                                    autocomplete="new-password"
                                    aria-describedby="new-password-feedback"
                                    v-model="$v.form.password.$model"
                                    :state="validateState('password')"

                                >
                                </b-form-input>
                                <b-form-invalid-feedback id="new-password-feedback">
                                    <span v-if="!$v.form.password.required">{{ $t('field_required') }}</span>
                                    <span v-if="!$v.form.password.minLength">{{ $t('at_least_x_char', {'0': 6}) }}</span>
                                </b-form-invalid-feedback>
                            </b-form-group>
                            <b-form-group inline class="form-sm">
                                <label for="confirm-new-password">{{ $t('confirm_new_password') }}
                                    <sup class="text-danger">*</sup></label>
                                <b-form-input
                                    id="confirm-new-password"
                                    type="password"
                                    name="confirm-new-password"
                                    autocomplete="new-password"
                                    :state="validateState('password_confirmation')"
                                    v-model="$v.form.password_confirmation.$model"
                                >
                                </b-form-input>
                                <b-form-invalid-feedback id="confirm-new-password-feedback">
                                    <span v-if="!$v.form.password_confirmation.required">{{ $t('field_required') }}</span>
                                    <span v-if="!$v.form.password_confirmation.minLength">{{ $t('at_least_x_char', {'0': 6}) }}</span>
                                    <span v-if="!$v.form.password_confirmation.sameAs">{{ $t('password_must_be_identical') }}</span>
                                </b-form-invalid-feedback>
                            </b-form-group>
                            <p v-if="!merchant" class="text-blue">{{ $t('information_editable_dashboard') }}</p>
                            <button type="submit" :disabled="$v.form.$anyError || (merchant ? !$v.form.password.$dirty || !$v.form.password_confirmation.$dirty : !$v.form.$dirty)" class="btn btn-lg bg-blue float-right">{{ $t('complete_registering') }}</button>

                        </div>

                    </b-form>

                </div>

                <div class="col-12 text-center"  v-else-if="done">
                    <img src="/images/actived-account.svg" class="my-n5 w-75">
                    <h1 class="text-blue h2 h2-carter text-center mt-lg-n5">{{ $t('account_activated') }}</h1>
                    <i18n tag="p" class="text-blue text-center py-lg-4 py-2" path="connect_to_dashboard"><br></i18n>
                    <div class="text-center">
                        <router-link :to="redirect" class="btn bg-blue btn-lg">{{ $t('log_in_dashboard') }}</router-link>
                    </div>
                </div>

                <div class="col-12" v-else-if="errored">
                    <h1 class="text-center mt-4 text-gold"><i class="fal fa-times fa-2x"></i></h1>
                    <h1 class="text-blue h2 h2-carter text-center">{{ $t('expired_link') }}</h1>
                    <p class="h4 text-blue text-center mb-5"></p>

                    <div class="text-center">
                        <router-link :to="redirect" class="btn bg-blue ">{{ $t('back_login') }}</router-link>
                    </div>
                </div>



            </div>

        </div>
    </div>
</template>
<script>
import {validationMixin} from "vuelidate";
import {required, sameAs, minLength, numeric} from "vuelidate/src/validators"
import requiredIf from "vuelidate/lib/validators/requiredIf";
import alpha from 'vuelidate/lib/validators/alpha';

export default {
    data() {
        return {
            form: {
                zip: null,
                firstname: null,
                lastname: null,
                region: null,
                country_id: null,
                password: null,
                password_confirmation: null,
                phone: null
            },
            country: {},
            errored: false,
            loaded: false,
            done: false,
            countries: []
        }
    },
    props: {
        token: {},
        merchant: {
            required: false
        }
    },
    mixins: [validationMixin],
    computed: {
        redirect() {
            if (this.merchant) {
                return {name: 'MerchantConfirmation'}
            }
            return {name: 'login'}
        },
    },
    validations: {
        form: {
            password: {
                required,
                minLength: minLength(6)
            }, firstname: {
                required: requiredIf(function (el) {
                    return !el.merchant
                }),
                minLength: minLength(2),
                validName:(value)=>{
                    let re = new RegExp("^[A-Za-zÀ-ÖØ-öø-ÿ]'?[- A-Za-zÀ-ÖØ-öø-ÿ]+$")
                    return re.test(value);
                }
            }, lastname: {
                required: requiredIf(function (el) {
                    return !el.merchant
                }),
                minLength: minLength(2),
                validName:(value)=>{
                    let re = new RegExp("^[A-Za-zÀ-ÖØ-öø-ÿ]'?[- A-Za-zÀ-ÖØ-öø-ÿ]+$")
                    return re.test(value);
                }
            },
            password_confirmation: {
                required,
                sameAs: sameAs('password'),
                minLength: minLength(6)
            },
            country_id: {
                required: requiredIf(function (el) {
                    return !el.merchant
                })
            },

        }
    },
    async created() {
        await this.$auth
            .load()
        if (this.$auth.user()) {
            this.$auth.logout({redirect: false})
        }
        await axios.get('/pays').then((res) => {
            this.countries = res.data;
            this.countries.unshift({id:null, name:"Choisir un pays"})
        });
        // this.country = this.countries[3]
        // this.form.country_id = this.country.id
        // require('jsonp')('https://get.geojs.io/v1/ip/country.js', null, (err, data) => {
        //     if (err) {
        //         console.error(err.message);
        //     } else {
        //         let country = this.countries.find(c => c.name === data.name);
        //         if (country) {
        //             this.country = country
        //             this.form.country_id = country.id
        //         }
        //     }
        // });
        axios.post('/password/token', {token: this.token}).then(() => this.loaded = true).catch((err) => {
            this.errored = true
            console.error(err)
        })
    },
    methods: {
        validateState(name) {
            const {$dirty, $error} = this.$v.form[name];
            return $dirty ? !$error : null;
        },
        submit() {
            if (!this.merchant)
                this.$v.form.$touch();
            if (this.$v.form.$anyError) return;
            let data = this.merchant ? {
                token: this.token, password: this.form.password,
                password_confirmation: this.form.password_confirmation,
                _merchant: true
            } : {
                token: this.token,
                firstname: this.form.firstname,
                lastname: this.form.lastname,
                password: this.form.password,
                password_confirmation: this.form.password_confirmation,
                country_id: this.form.country_id,
                region: this.form.region,
                phone: this.form.phone
            }
            axios.post('/password/reset', data).then(() => this.done = true)
                .catch(() => this.errored = true)
        },
    },
}
</script>
