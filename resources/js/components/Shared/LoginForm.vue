<template>
    <div class="container">
        <div class="row" :class="$route.name != 'createAccount' ? 'pt-md-5' : ''">
            <div :class="$route.name != 'createAccount' ? 'col-md-6 offset-md-3' : 'col-md-8 pl-0'">
                <h1 class="text-blue h2 h2-line h2-line-center h2-carter mb-md-5 mt-4" v-if="!isResearch">{{ $t('log_in') }}</h1>
                <div class="alert alert-danger" v-if="error">
                    <p class="mb-0">{{ $t('log_in_error') }} {{ error }}</p>
                </div>
                <b-form @submit.stop.prevent="onSubmit">
                    <b-form-group class="form-xl">
                        <label for="email">{{ $t('email') }}<sup class="text-danger">*</sup></label>
                        <b-form-input
                            id="email"
                            type="email"
                            name="email"
                            dusk="email-dusk"
                            v-model="$v.logins.email.$model"
                            :state="validateState('email')"
                            autocomplete="email"
                            aria-describedby="email-feedback"
                        >
                        </b-form-input>
                        <b-form-invalid-feedback id="email-feedback">
                            <span v-if="!$v.logins.email.required">{{ $t('field_required') }}</span>
                            <span v-if="!$v.logins.email.email">{{ $t('field_email') }}</span>
                        </b-form-invalid-feedback>
                    </b-form-group>

                    <b-form-group class="form-xl">
                        <label for="password">{{ $t('password') }} <sup class="text-danger">*</sup></label>
                        <b-form-input
                            id="password"
                            name="password"
                            type="password"
                            dusk="password-dusk"
                            v-model="$v.logins.password.$model"
                            :state="validateState('password')"
                            autocomplete="current-password"
                            aria-describedby="password-feedback"
                        >
                        </b-form-input>
                        <b-form-invalid-feedback id="password-feedback">
                            {{ $t('field_required') }}
                        </b-form-invalid-feedback>
                    </b-form-group>

                    <button type="submit" :class="$v.logins.$anyError||!logins.password?'disabled':''" class="btn btn-lg bg-blue">{{ $t('log_in') }}</button>

                    <router-link :to="{ name: 'password-reset' }" class="float-right pt-3 basic text-bold-dark-grey">{{ $t('forgot_your_password') }}</router-link>

                </b-form>
            </div>
        </div>


    </div>

</template>

<script>
import {validationMixin} from "vuelidate";
import email from "vuelidate/lib/validators/email";
import {required} from "vuelidate/lib/validators";
import Bugsnag from "@bugsnag/js";

export default {
    name: "LoginForm",
    mixins: [validationMixin],
    props: {
        isResearch: {
            default: false,
        },
        initialEmail: {
            default: null,
        }
    },
    data() {
        return {
            logins: {
                email: null,
                password: null,
            },
            error: false
        }
    },
    validations: {
        logins: {
            email: {
                email,
                required
            },
            password: {
                required
            }
        }
    },
    created() {
        this.logins.email = this.initialEmail;
    },
    methods: {
        onSubmit() {
            this.$v.logins.$touch();
            if (this.$v.logins.$anyError) {
                return;
            }
            var app = this
            if (this.isResearch) {
                this.$auth.login({
                    params: {
                        email: app.logins.email,
                        password: app.logins.password
                    },
                    redirect: false,
                    rememberMe: true,
                    fetchUser: true,
                }).then(() => {
                    const user = app.$auth.user()
                    Bugsnag.setUser(user.id, user.email, user.firstname + ' ' + user.lastname)
                    this.$emit('login');
                }, (err) => {
                    app.error = true;
                });
            } else {
                let redirect = this.$auth.redirect();
                this.$auth.login({
                    params: {
                        email: app.logins.email,
                        password: app.logins.password
                    },
                    // redirect: {name: 'dashboard'},
                    redirect: false,
                    rememberMe: true,
                    fetchUser: true,


                }).then((res) => {
                    const user = app.$auth.user()
                    Bugsnag.setUser(user.id, user.email, user.firstname + ' ' + user.lastname)
                    app.$router.push(redirect ? redirect.from.fullPath : '/finder').catch(() => {})
                }, (err) => {
                    app.error = err.response.data.error;
                });
            }

        },
        validateState(name) {
            const {$dirty, $error} = this.$v.logins[name];
            return $dirty ? !$error : null;
        },
    }
}
</script>
