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
                <div class="col-12" v-if="!done">
                    <h1 class="text-blue h2 h2-line h2-line-center h2-carter mb-3 mt-4">{{ $t('forgot_your_password') }}</h1>
                    <p class="h4 text-blue text-center mb-5">{{ $t('reset_send_email') }}</p>

                    <b-form class="row" @submit.prevent="submit">

                        <div class="col-md-6 offset-md-3">
                            <router-link class="basic text-bold-dark-grey d-inline-block mb-2" :to="{name:'login'}">
                                <i class="fal fa-long-arrow-left"></i>
                                {{ $t('back') }}
                            </router-link>
                            <b-form-group class="form-xl">
                                <label for="email">{{ $t('email') }} <sup class="text-danger">*</sup></label>
                                <b-form-input
                                    id="email"
                                    type="email"
                                    name="email"
                                    aria-describedby="email-feedback"
                                    v-model="$v.email.$model"
                                    required
                                    :state="validateState('email')"
                                >
                                </b-form-input>
                                <b-form-invalid-feedback id="email-feedback">
                                    <span v-if="!$v.email.required">{{ $t('field_required') }}</span>
                                    <span v-if="!$v.email.email">{{ $t('field_email') }}</span>

                                </b-form-invalid-feedback>
                            </b-form-group>

                            <p v-if="error !== ''" class="text-danger">
                                <template v-if="error === 'User cannot be found.'">
                                    {{ $t('inexistant_email') }}
                                </template>
                                <template v-else-if="error === 'User not activated yet.'">
                                    <i18n tag="h1" class="h2 h2-carter text-center text-blue" path="registering_email_sent"><br></i18n>
                                    <i18n path="check_spams" tag="p" class="text-center py-4"><br><a href="mailto:info@classic-parts-finder.com" class="basic text-gold">info@classic-parts-finder.com</a></i18n>
                                </template>
                            </p>

                            <button type="submit" :disabled="$v.$anyError || !$v.$anyDirty" class="btn btn-lg bg-blue">{{ $t('validate') }}</button>

                        </div>

                    </b-form>

                </div>

                <div class="col-12" v-else>
                    <h1 class="text-center mt-4 text-gold"><i class="fal fa-check"></i></h1>
                    <h1 class="text-blue h2 font-family-reset text-center mb-3 ">{{ $t('reset_email_sent') }}</h1>
                    <p class="h4 text-blue text-center mb-5"></p>

                    <div class="text-center">
                        <router-link :to="{ name: 'login' }" class="btn bg-blue btn-lg">{{ $t('back') }}</router-link>
                    </div>
                </div>

            </div>


        </div>
    </div>
</template>
<script>
import {validationMixin} from "vuelidate";
import {required, email} from "vuelidate/src/validators"

export default {
    data() {
        return {
            email: null,
            error: '',
            done: false,
        }
    },
    mixins: [validationMixin],
    validations: {
        email: {
            required,
            email
        }
    },
    created(){
        if (this.$auth.check()){
            this.$router.push({name:'dashboard'})
        }
    },
    methods: {
        submit() {
            this.$v.$touch();
            if (this.$v.$anyError) return;
            axios.post('/password/email', {email: this.email})
                .then(() => this.done = true)
                .catch((err) => this.error = err.response.data.message)
        },
        validateState(name) {
            const {$dirty, $error} = this.$v[name];
            return $dirty ? !$error : null;
        },
    }
}
</script>
