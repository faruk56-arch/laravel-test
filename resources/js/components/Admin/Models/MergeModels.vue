<template>
    <b-modal @ok="merge" cancel-title="Annuler" ok-title="Fusionner" id="merge" centered size="lg"
             title="Fusion du modèle" content-class='border-0 rounded px-3' v-if="selectedMerge">
        <div class="row">
            <div class="form form-sm pt-2 py-3 col">
                <label for="merge-id">Modèle sélectionné</label>
                <input class="form-control" :value=" selectedMerge.id + ' - ' + selectedMerge.brand.name + ' - ' + selectedMerge.name +
                                        ' (' + selectedMerge.year_start + '-' + selectedMerge.year_end + ')'" type="text" disabled/>
            </div>
            <div class="col-2 d-flex flex-column justify-content-center align-item-center">
                    <span class="text-center">
                        <strong>Fusionner</strong>
                    </span>
                <div class="mt-1 text-center">
                    <i class="far fa-sort-alt fa-2x rotate-md-90"></i>
                </div>
            </div>
            <div class="form form-sm pt-2 py-3 col">
                <label for="merge-id">Nom du modèle de destination</label>
                <autocomplete
                    id="merge-id"
                    name="researchModele"
                    :search="searchModele"
                    :get-result-value="getResultValueModele"
                    :debounce-time="1"
                    ref="autocompleteModele"
                    @submit="submit"
                    placeholder="Marques, modèles, etc..."
                >
                    <template #result="{ result, props }">
                        <li v-bind="props">
                            <div class="media">
                                <span class="media-body">
                                    {{
                                        result.id + ' - ' + result.brand.name + ' - ' + result.name +
                                        ' (' + result.year_start + '-' + result.year_end + ')'
                                    }}
                                </span>
                                <div class="details"></div>
                            </div>
                        </li>
                    </template>
                </autocomplete>
            </div>
        </div>
        <div class="alert alert-info d-flex">
            <i class="far fa-info-circle mr-2 pt-1"></i>
            <span>
                Le modèle sélectionné va être supprimé après transfert de toutes les données qui lui sont associées.
                Ces données concernent : les compatibilités entre modèles, les voitures d'utilisateur.
            </span>
        </div>
    </b-modal>
</template>

<script>
export default {
    name: "MergeModels",
    data() {
        return {
            mergeDestinationId: null,
        }
    },
    props: {
        selectedMerge: null
    },
    methods: {
        submit(event) {
            if (!event)
                return false
            this.mergeDestinationId = event.id

        },
        searchModele(input) {
            return axios.get('/modele?name=' + input).then(res => res.data)
        },
        merge() {
            axios.delete('modele/' + this.selectedMerge.id + '/' + this.mergeDestinationId).then(res => {
                this.$store.commit('cars/REMOVE_CAR', this.selectedMerge.id)
                this.$emit('submit')
                this.mergeDestinationId = null;
            })
        },
        getResultValueModele(result) {
            return result.id + ' - ' + result.brand.name + ' - ' + result.name + '(' + result.year_start + '-'
                + result.year_end + ')'
        },
    }
}
</script>

<style scoped>

</style>
