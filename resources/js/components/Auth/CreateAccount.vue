<template>
    <div class="register">

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

        <div class="container" v-if='!success'>

            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <h1 class="text-blue h2 h2-line h2-line-center h2-carter py-5 mb-0 mt-4">{{ $t('registration') }}</h1>
                    <b-form @submit.stop.prevent="register" @input="errors = {}; error = false">
                        <a class="basic text-bold-dark-grey d-inline-block mb-2" @click="$router.go(-1)">
                            <i class="fal fa-long-arrow-left"></i>
                            {{ $t('back') }}
                        </a>
                        <div class="alert alert-danger" v-if="error">
                            <i class="far fa-exclamation-triangle"></i> {{ errors.email ? $t('email_already_used') : $t('registering_error') }}
                        </div>
                        <b-form-group class="form-sm">
                            <label for="email">{{ $t('email') }} <sup class="text-danger">*</sup></label>
                            <b-form-input
                                id="email"
                                type="email"
                                name="email"
                                v-model="$v.user.email.$model"
                                :state="validateState('email')"
                                autocomplete="email"
                                aria-describedby="email-feedback"
                            >
                            </b-form-input>
                            <b-form-invalid-feedback id="email-feedback">
                                <span v-if="!$v.user.email.required">{{ $t('field_required') }}</span>
                                <span v-if="!$v.user.email.email">{{ $t('field_email') }}</span>
                                <span v-if="!$v.user.email.avalaible">{{ $t('field_email_used') }}</span>
                            </b-form-invalid-feedback>
                        </b-form-group>

                        <div class="row">
                            <b-form-group class="form-sm col-12">
                                <b-form-checkbox
                                    id="cgu"
                                    v-model="cgu"
                                    name="cgu">
                                    <i18n path="accept_cgu">
                                        <template v-if="$i18n.locale=='fr'">
                                            <a href="https://classic-parts-finder.com/conditions-generales-dutilisation" class="basic" target="_blank">{{ $t('cgu') }}</a>
                                            <a class="basic" target="_blank" href="https://classic-parts-finder.com/politique-de-confidentialite/">{{ $t('privacy') }}</a>.
                                        </template>
                                        <template v-else-if="$i18n.locale=='en'">
                                            <a href="https://classic-parts-finder.com/en/terms-of-service/" class="basic" target="_blank">{{ $t('cgu') }}</a>
                                            <a class="basic" target="_blank" href="https://classic-parts-finder.com/en/privacy-policies/">{{ $t('privacy') }}</a>.
                                        </template>
                                        <template v-else>
                                            <a href="https://classic-parts-finder.com/de/allgemeine-nutzungsbedingungen/" class="basic" target="_blank">{{ $t('cgu') }}</a>
                                            <a class="basic" target="_blank" href="https://classic-parts-finder.com/de/datenschutzrichtlinien/">{{ $t('privacy') }}</a>.
                                        </template>
                                    </i18n>.
                                    <sup class="text-danger">*</sup>

                                </b-form-checkbox>
                            </b-form-group>
                        </div>

                        <div>
                            {{ errorMessage }}
                        </div>
                        <div class="text-right">
                            <button :class="!cgu||$v.user.$anyError||!$v.user.$dirty?'disabled':''" type="submit" class="btn bg-blue btn-lg mt-2">{{ $t('finish') }}</button>
                        </div>
                    </b-form>
                </div>
            </div>

        </div>

        <div class="container search-engine-content" v-else>
            <div class="row">
                <div class="col-12 text-center">
                    <img src="/images/confirmation-email.svg" class="mb-4">
                    <i18n tag="h1" class="h2 h2-carter text-center text-blue" path="registering_email_sent"><br></i18n>
                    <i18n path="check_spams" tag="p" class="text-center py-4"><br><a href="mailto:info@classic-parts-finder.com" class="basic text-gold">info@classic-parts-finder.com</a></i18n>
                    <router-link tag='button' class='btn btn-lg bg-blue' :to="{name:'login'}">{{ $t('back_login') }}</router-link>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import {required} from "vuelidate/lib/validators";
import email from "vuelidate/lib/validators/email";
import {validationMixin} from "vuelidate";

export default {
    name: "CreateAccount",
    mixins: [validationMixin],
    validations: {
        user: {
            email: {
                required,
                email,
                avalaible: function () {
                    return this.errors && this.errors.email === undefined
                }
            },
        }
    },
    data() {
        return{
            error:null,
            errors:[],
            cgu: false,
            user: {
                email:null,
            },
            errorMessage:null,
            success: false,
        }
    },
    created(){
        if (this.$auth.check()){
            this.$router.push({name:'dashboard'})
        }
    },
    methods:{
        validateState(name) {
            const {$dirty, $error} = this.$v.user[name];
            return $dirty ? !$error : null;
        },
        register(){
            axios.post('/auth/register',{
                email:this.user.email
            }).then((res)=>{
                this.success = true
            }).catch((e)=>{
                this.error = true
                this.errors = e.data.errors
            })
        }
    }
}
</script>

<style scoped>

</style>
