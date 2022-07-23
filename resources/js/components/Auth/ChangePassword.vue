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
                    <h1 class="text-blue h2 h2-line h2-line-center mb-3 mt-4">{{ $t('update_your_password') }}</h1>
                    <p class="h4 text-blue text-center mb-5">{{ $t('set_new_password') }}</p>

                    <b-form class="row" @submit.prevent="submit">

                        <div class="col-md-6 offset-md-3">
                            <b-form-group :label="$t('new_password')" label-for="new-password" class="form-xl">
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
                            <b-form-group :label="$t('confirm_new_passwd')" label-for="confirm-new-password" class="form-xl">
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
                            <button type="submit" :disabled="$v.form.$anyError || !$v.form.$dirty" class="btn btn-lg bg-blue">{{ $t('modify') }}</button>

                        </div>

                    </b-form>

                </div>
                <div class="col-12" v-else-if="done">
                    <h1 class="text-center mt-4 text-gold"><i class="fal fa-check"></i></h1>
                    <h1 class="text-blue h2 font-family-reset text-center mb-3 ">{{ $t('password_updated') }}</h1>
                    <p class="h4 text-blue text-center mb-5">{{ $t('your_password_updated') }}</p>

                    <div class="text-center">
                        <router-link :to="{ name: 'login' }" class="btn bg-blue btn-lg">{{ $t('login') }}</router-link>
                    </div>
                </div>
                <div class="col-12" v-else-if="errored">
                    <h1 class="text-center mt-4 text-gold"><i class="fal fa-times"></i></h1>
                    <h1 class="text-blue h2 font-family-reset text-center mb-3 ">{{ $t('expired_link') }}</h1>
                    <p class="h4 text-blue text-center mb-5"></p>

                    <div class="text-center">
                        <router-link :to="{ name: 'login' }" class="btn bg-blue btn-lg">{{ $t('back' )}}</router-link>
                    </div>
                </div>

            </div>

        </div>
    </div>
</template>
<script>
import {validationMixin} from "vuelidate";
import {required, sameAs, minLength} from "vuelidate/src/validators"

export default {
    data() {
        return {
            form: {
                password: null,
                password_confirmation: null
            },
            errored: false,
            loaded: false,
            done: false
        }
    },
    props: ['token'],
    mixins: [validationMixin],
    validations: {
        form: {
            password: {
                required,
                minLength: minLength(6)
            },
            password_confirmation: {
                required,
                sameAs: sameAs('password'),
                minLength: minLength(6)
            }
        }
    },
    created() {
        axios.post('/password/token', {token: this.token}).then(() => this.loaded = true).catch(() => this.errored = true)
        if (this.$auth.check()){
            this.$router.push({name:'dashboard'})
        }
    },
    methods: {
        validateState(name) {
            const {$dirty, $error} = this.$v.form[name];
            return $dirty ? !$error : null;
        },
        submit() {
            this.$v.form.$touch();
            if (this.$v.form.$anyError) return;
            axios.post('/password/reset', {
                token: this.token,
                password: this.form.password,
                password_confirmation: this.form.password_confirmation
            }).then(() => this.done = true)
                .catch(() => this.errored = true)
        }
    }
}
</script>
