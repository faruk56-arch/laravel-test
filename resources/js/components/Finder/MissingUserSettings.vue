<template>
    <b-form @submit.prevent="onSubmit">
        <b-modal :id="'missing-user-settings-' + id" :title="$t('missing_info_profil')">
            {{ $t('required_info_to_continue') }}
            <div>
                <b-form-group
                    id="input-group-1"
                    :label="$t('first_name')"
                    label-for="firstname"
                    v-if="!user.firstname"
                >
                    <b-form-input
                        id="firstname"
                        type="text"
                        required
                        v-model="form.firstname"
                        placeholder="John"
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    id="input-group-2"
                    :label="$t('last_name')"
                    label-for="lastname"
                    v-if="!user.lastname"
                >
                    <b-form-input
                        id="lastname"
                        type="text"
                        required
                        v-model="form.lastname"
                        placeholder="Doe"
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    id="input-group-3"
                    :label="$t('address')"
                    label-for="address"
                    v-if="!user.address"
                >
                    <b-form-input
                        id="address"
                        type="text"
                        required
                        v-model="form.address"
                        placeholder=""
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    id="input-group-4"
                    :label="$t('zip_code')"
                    label-for="zip"
                    v-if="!user.zip"
                >
                    <b-form-input
                        id="zip"
                        type="text"
                        required
                        v-model="form.zip"
                        placeholder=""
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    id="input-group-5"
                    :label="$t('city')"
                    label-for="city"
                    v-if="!user.city"
                >
                    <b-form-input
                        id="city"
                        type="text"
                        required
                        v-model="form.city"
                        placeholder=""
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    id="input-group-6"
                    :label="$t('country')"
                    label-for="country"
                    v-if="!user.country_id"
                >
                    <b-form-select
                        class="mb-2 mr-sm-2 mb-sm-0"
                        id="country"
                        :options="countries"
                        value-field="id"
                        v-model="form.country_id"
                        text-field="name"
                    ></b-form-select>
                </b-form-group>
            </div>
            <template v-slot:modal-footer="{ cancel }">
                <b-button size="sm" variant="danger" @click="cancel()">
                    {{ $t('cancel') }}
                </b-button>
                <b-button type="submit" @click="onSubmit">{{ $t('save_informations') }}</b-button>
            </template>
        </b-modal>
    </b-form>
</template>

<script>
export default {
    name: "MissingUserSettings",
    props: ["id"],
    data() {
        return {
            countries: [],
            form: {
                firstname: null,
                lastname: null,
                address: null,
                zip: null,
                city: null,
                country_id: null,
            }
        }
    },
    created() {
    },
    methods: {
        getUserDetailsFilled: function () {
            return Object.fromEntries(Object.entries(this.form).filter(([key, value]) => value !== null));
        },
        onSubmit() {
            const request = this.getUserDetailsFilled()
            axios.put('/user/' + this.user.id, request).then(() => {
                this.$auth.fetch();
            })
            this.$bvModal.hide('missing-user-settings-' + this.id);
        }
    },
    computed: {
        user() {
            return this.$auth.user()
        }
    }
}
</script>

<style scoped>

</style>
