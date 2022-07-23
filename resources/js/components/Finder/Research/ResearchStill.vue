<template>
    <div>
        <div class="container pt-5" v-if="!loading">
            <div v-if="error">

                <div class="text-center p-4">
                    <h1>{{ $t('oups') }}</h1>
                    <p class="lead">{{ $t('votre_recherche_est_introuvable_ou_a_dj_t_prolonge') }}</p>
                    <router-link :to="{ name: 'dashboard' }" class="btn bg-blue py-2">{{ $t('accder_mon_tableau_de_bord') }}</router-link>
                </div>

            </div>
            <div v-else>

                <div class="col-12 col-md-6 offset-md-3 text-center bg-white shadow rounded p-4">
                    <h1 class="h2">{{ $t('cest_not') }}</h1>
                    <p class="lead">{{ $t('votre_recherche_a_bien_t_prolonge') }}</p>
                    <p>{{ $t('notez_que_votre_demande_nest_dsormais_plus_limite') }}</p>
                    <router-link :to="{ name: 'dashboard' }" class="btn bg-blue py-2">{{ $t('accder_mon_tableau_de_bord2') }}</router-link>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import Bugsnag from "@bugsnag/js";

export default {
    name: "ResearchStill",
    data() {
        return {
            loading: true,
            error: false,
        }
    },
    props: ['id'],
    async created() {
        try {
            await axios.get('/extend-research-duration/' + this.id)
            this.loading = false
        } catch (e) {
            Bugsnag.notify(e);
            this.error = true
            this.loading = false
        }
    },
}
</script>

<style scoped>

</style>
