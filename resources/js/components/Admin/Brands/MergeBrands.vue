<template>
    <b-modal @ok="merge" cancel-title="Annuler" ok-title="Fusionner" id="merge" centered size="lg"
             title="Fusion du modèle" content-class='border-0 rounded px-3' v-if="selectedMerge">
        <div class="row">
            <div class="form form-sm pt-2 py-3 col">
                <label for="merge-id">Marque sélectionnée</label>
                <input class="form-control" :value=" selectedMerge.id + ' - ' + selectedMerge.name" type="text" disabled/>
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
                <label for="merge-id">Nom de la marque de destination</label>
                <autocomplete
                    id="merge-id"
                    name="research"
                    :search="search"
                    :get-result-value="getResultValue"
                    :debounce-time="1"
                    ref="autocomplete"
                    @submit="submit"
                    placeholder="Nom de la marque"
                >
                    <template #result="{ result, props }">
                        <li v-bind="props">
                            <div class="media">
                                <span class="media-body">
                                    {{
                                        result.id + ' - ' + result.name
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
                La marque sélectionné va être supprimé après transfert de toutes les données qui lui sont associées.
                Ces données concernent : les modèles de véhicules.
            </span>
        </div>
    </b-modal>
</template>

<script>
export default {
    name: "MergeBrands",
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
        search(input) {
            return axios.get('/brand?name=' + input).then(res => res.data)
        },
        merge() {
            axios.delete('brand/' + this.selectedMerge.id + '/' + this.mergeDestinationId).then(res => {
                this.$store.commit('brands/REMOVE_BRAND', this.selectedMerge.id)
                this.$emit('submit')
                this.mergeDestinationId = null;
            })
        },
        getResultValue(result) {
            return result.id + ' - ' + result.name
        },
    }
}
</script>

<style scoped>

</style>
