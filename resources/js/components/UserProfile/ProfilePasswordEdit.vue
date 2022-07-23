<template>
    <div class="p-2 max-700">
        <h3 class="h3-serif text-blue mb-4"><strong>{{ $t('password_security') }}</strong></h3>
        <ValidationObserver ref="form" v-slot="{ handleSubmit, failed }">
            <div class="alert alert-success" role="alert" v-if="success">
                <i class="fal fa-thumbs-up"></i>
                {{ $t('all_modifications_taken') }}
            </div>
            <div class="alert alert-danger" role="alert" v-if="failed">
                <i class="fal fa-exclamation-circle"></i>
                {{ $('error_happened_wrong_param') }}
            </div>
            <form class="form-sm" @submit.prevent="handleSubmit(onSubmit)">
                <ValidationProvider name="mot de passe" rules="required|min:6" v-slot="{ errors }" vid="current_password">
                    <div class="form-group" :class="{'error': errors.length}">
                        <label for="actual-password" class="text-blue">{{ $t('actual_password') }}</label>
                        <input type="password" v-model="payload.current_password" class="form-control" id="actual-password" autocomplete="password">
                        <span>{{ errors[0] }}</span>
                    </div>
                </ValidationProvider>
                <ValidationProvider name="nouveau mot de passe" rules="required|confirmed:password_confirmation|min:6" v-slot="{ errors }">
                    <div class="form-group" :class="{'error': errors.length}">
                        <label for="new-password" class="text-blue">{{ $t('new_password') }}</label>
                        <input type="password" v-model="payload.password" class="form-control" id="new-password" autocomplete="new-password">
                        <span>{{ errors[0] }}</span>
                    </div>
                </ValidationProvider>
                <ValidationProvider name="confirmer votre nouveau mot de passe" rules="required|min:6" v-slot="{ errors }" vid="password_confirmation">
                    <div class="form-group" :class="{'error': errors.length}">
                        <label for="new-password-confirm" class="text-blue">{{ $t('confirm_your_new_password') }}</label>
                        <input type="password" v-model="payload.password_confirmation" class="form-control" id="new-password-confirm" autocomplete="new-password">
                        <span>{{ errors[0] }}</span>
                    </div>
                </ValidationProvider>

                <button type="submit" class="btn bg-gold btn-sm mt-4">
                    {{ $t('update_your_password') }}
                </button>
            </form>
        </ValidationObserver>

        <!-- Si erreur -->
        <div class="alert alert-danger mt-5" role="alert">
            <i18n tag="p" path="account_removal" class="mb-2"><br></i18n>
            <button class="btn btn-sm btn-danger" v-b-modal.delete-modal>
                {{ $t('remove_my_account') }}
            </button>
        </div>

        <b-modal id="delete-modal" :title="$t('remove_my_account')" hide-footer content-class="shadow border-0 rounded">
            <p class="mb-3">
                {{ $t('action_will_disconnect') }}
            </p>
            <div class="d-flex">
                <button class="btn btn-sm bg-dark-grey mr-2" @click="$bvModal.hide('delete-modal')">
                    {{ $t('cancel') }}
                </button>
                <button class="btn btn-sm btn-danger w-100" @click="destroy">
                    {{ $t('confirm_account_removal') }}
                </button>
            </div>

        </b-modal>

    </div>
</template>
<script>
import validationProfileMixin from "../../mixins/validationProfileMixin";

export default {
    name: 'ProfilePasswordEdit',
    mixins: [validationProfileMixin],
    data() {
        return {
            payload: {
                current_password: null,
                password: null,
                password_confirmation: null
            },
        }
    },
    methods: {
        onSubmit() {
            axios.post('auth/updatePassword', {...this.payload}).then(res => {
                this.success = true
            }).catch(err => {
                this.$refs.form.setErrors({
                    ...err.response.data.errors
                });
            })
        },
        destroy: async function () {
            try {
                await axios.delete('auth')
                window.location.href = '/'
            } catch (e) {
                window.location.href = '/'
            }
        }
    },
}
</script>
