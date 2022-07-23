<template>
    <div class="register">

        <div class="container">

            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <h1 class="text-blue h2 h2-line h2-line-center h2-carter py-5 mb-0 mt-4">{{ $t('registration') }}</h1>
                    <b-form @submit.stop.prevent="addMerchant" @input="errors = {}; error = false">
                        <div class="alert alert-danger" v-if="error">
                            <p>{{ errors.email ? $t('email_already_used') : $t('registering_error') }}</p>
                        </div>
                        <router-link class="basic text-bold-dark-grey d-inline-block mb-2" :to="{name:'MerchantSettings',params:{type:form.merchantType==='Pro'?'Pro':'Particulier'}}">
                            <i class="fal fa-long-arrow-left"></i>
                            {{ $t('back') }}
                        </router-link>
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
                                <span v-if="!$v.user.email.required">{{  $t('field_required') }}</span>
                                <span v-if="!$v.user.email.email">{{  $t('field_email') }}</span>
                                <span v-if="!$v.user.email.avalaible">{{  $t('field_email_used') }}</span>
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
                                    </i18n>
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
    </div>
</template>
<script>
import {validationMixin} from "vuelidate";
import {minLength, required} from "vuelidate/lib/validators";
import email from "vuelidate/lib/validators/email";
import {mapActions} from "vuex";

export default {
    mixins: [validationMixin],
    props: ['form', 'user'],
    data() {
        return {
            error: false,
            errorMessage: null,
            errors: {},
            cgu: false,
        };
    },
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
    created(){
        if (this.$auth.check()){
            this.$router.push({name:'dashboard'})
        }
    },
    methods: {
        validateState(name) {
            const {$dirty, $error} = this.$v.user[name];
            return $dirty ? !$error : null;
        },
        addMerchant() {
            this.$v.user.$touch();
            if (this.$v.user.$anyError) return;
            let app = this;
            this.newMerchant(this.form)
                .then((res) => {
                    app.register(res.data.id);
                }).catch((err) => {
                this.errorMessage = err.message
            })
        },
        changeParent(event, name) {
            this.$emit('update:params.' + name, event)
        },
        register(id) {
            let app = this;
            axios.post('/auth/register', {
                merchantId: id,
                firstname: app.form.firstname,
                lastname: app.form.lastname,
                email: app.user.email,
            }).then((res) => {
                return this.$router.push({name: 'CreateAccountConfirmation'}).catch(() => {})
            }).catch((res) => {
                this.error = true
                this.errors = res.data.errors
            })
        },
        ...mapActions('merchant', ['newMerchant']),

    },
}
</script>
