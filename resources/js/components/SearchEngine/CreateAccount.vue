<template>
    <div>
        <div class="alert alert-danger" v-if="error && !success">
            <p class="mb-0">{{
                    errors.email ? $t('email_already_used_log') : $t('cannot_register')
                }}</p>
        </div>
        <template v-if="isLogin">
            <a class="basic text-bold-dark-grey d-inline-block mb-2 cursor-p" @click="isLogin=false">
                <i class="fal fa-long-arrow-left"></i>
                {{ $t('back') }}
            </a>
            <h3 class="text-blue mb-3">{{ $t('nearly_there_login') }}</h3>
            <login-form :isResearch="true" :initialEmail="user.email" @login="onLogin"></login-form>
        </template>
        <template v-else>
            <a class="basic text-bold-dark-grey d-inline-block mb-2 pointer cursor-p" @click="$router.go(-1)">
                <i class="fal fa-long-arrow-left"></i>
                {{ $t('back') }}</a>
            <h3 class="text-blue mb-3">{{ $t('nearly_there_register') }}</h3>
            <b-form @input="errors= {}; error = false" @submit.stop.prevent="onSubmit">
                <b-form-group :label="$t('email_address')" label-for="email" class="form-sm">
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
                    <!--                 <b-form-group class="col-12">
                                        <b-form-checkbox
                                            id="cgv"
                                            v-model="cgv"
                                            name="cgv"
                                            required>
                                            J'ai lu et j'accepte les conditions générales de vente
                                        </b-form-checkbox>
                                    </b-form-group> -->
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
                        </b-form-checkbox>
                    </b-form-group>
                </div>
                <button type="submit" :class="!(cgu)||$v.user.$anyError||!$v.user.$dirty||isSubmitting?'disabled':''"
                        class="btn bg-blue btn-lg mt-2 me-2 me-lg-3" :key="keySubmit">
                    <template v-if="isSubmitting">
                        <span class="loader loader-white"></span> {{ $t('finish') }}
                    </template>
                    <template v-else>
                        {{ $t('finish') }}
                    </template>
                </button>
                <button class="btn bg-blue btn-lg mt-2" @click="isLogin=true">
                    {{ $t('already_have_account') }}
                </button>
            </b-form>
        </template>
    </div>
</template>

<script>
import {mapActions} from 'vuex';
import {validationMixin} from 'vuelidate'
import {required, minLength} from "vuelidate/lib/validators";
import email from "vuelidate/lib/validators/email";
import integer from "vuelidate/lib/validators/integer";
import alpha from "vuelidate/lib/validators/alpha";
import sameAs from "vuelidate/lib/validators/sameAs";
const LoginForm = () => import(/* webpackChunkName: "group-finder" */"../Shared/LoginForm");


export default {
    mixins: [validationMixin],
    props: ['data'],
    components:{LoginForm},
    data() {
        return {
            user: {
                email: null,
                cgv: false,
            },
            error: false,
            errors: {},
            success: false,
            cgu: false,
            cgv: false,
            isSubmitting: false,
            keySubmit: 0,
            isLogin: false,

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
    methods: {
        ...mapActions('userModels', ['fetchAllUserModels']),

        validateState(name) {
            const {$dirty, $error} = this.$v.user[name];
            return $dirty ? !$error : null;
        },
        ...mapActions('researches', ['newResearch']),
        onSubmit() {
            this.$v.user.$touch();
            if (this.$v.user.$anyError) {
                return;
            }
            let app = this;
            this.isSubmitting = true;
            this.$auth.register({
                data: {
                    email: app.user.email,
                    loginAfterSignUp: false,
                },
                redirect: null,
                staySignedIn: false,
                autoLogin: false,
                fetchUser: true
            }).then((resp) => {
                app.success = true;
                let types = JSON.stringify(app.data.types);
                app.newResearch([app.$route.params.idCar, app.$route.params.idPart, resp.data.id, app.$route.params.custom, app.$route.query.message, app.$route.params.idCategory,
                    app.data.reference, app.data.details, app.data.images, app.data.year, app.data.version, types]);
                app.$router.push({name: 'CreateAccountConfirmation'}).catch(() => {});
            }, (resp) => {
                this.isSubmitting = false;
                app.error = true;
                app.errors = resp.response.data.errors;
                if (app.errors.email){
                    app.isLogin = true;
                }
            });

        },
        onLogin(){
            let types = JSON.stringify(this.data.types);
            this.newResearch([this.$route.params.idCar, this.$route.params.idPart, this.$auth.user().id, this.$route.params.custom, this.$route.query.message, this.$route.params.idCategory,
                this.data.reference, this.data.details, this.data.images, this.data.year, this.data.version, types]);
            this.fetchAllUserModels(true).then((res) => {
                this.$router.push({name: 'researchList'}).catch(() => {})
            });
        }
    }
}
</script>
