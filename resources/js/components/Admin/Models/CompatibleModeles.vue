<template>
    <div>
        <label class="mt-3">Indiquez le ou les véhicules compatible(s)
            <autocomplete
                id="researchModele"
                name="researchModele"
                :search="searchModele"
                :get-result-value="getResultValueModele"
                :debounce-time="1"
                ref="autocompleteModele"
                @submit="submit"
                placeholder="Marques, modèles, etc..."
                class="col"
            >
                <template #result="{ result, props }">
                    <li v-bind="props">
                        <div class="media">
                            <img v-if="result.img" class="align" :src="'/images/Cars/tn/'+result.img+'.png'" @error="result.img = null">
                            <img v-else class="align" :src="'/images/Cars/tn/emptycar.png'">
                            <span class="media-body">{{ result.brand.name + ' - ' + result.name + ' (' + result.year_start + '-' + result.year_end + ')' }}</span>
                            <div class="details"></div>
                        </div>
                    </li>
                </template>
            </autocomplete>
        </label>
        <div class="row ml-n2 m-0">
            <template v-for="(car,index) in compatible">
                <div class="card p-3 m-2 mt-3 text-center d-flex align-items-center flex-column justify-content-center cursor-p compatible-car-item card-outline" @click="$emit('remove', index)" >

                    <img v-if="car.img" class="w-100 little-car px-1" :src="'/images/Cars/'+car.img+'.png'" @error="car.img = null">
                    <i v-else class="fad fa-3x mb-2 fa-steering-wheel"></i>

                    <div class="mt-2" v-if="car.brand"><b>{{ car.brand.name + ' - ' + car.name + ' (' + car.year_start + '-' + car.year_end + ')' }}</b></div>
                    <i v-if="car.compatible" class="fas fa-check-circle badge-icon"></i>

                    <div class="delete">
                        <i class="text-danger fal fa-trash-alt"></i>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
export default {
    name: "CompatibleModeles",
    props: {
        compatible: {
            type: Array
        }
    },
    methods: {
        submit(event) {
            if (event) {
                const isDuplicate = this.compatible.find(el => el.id === event.id)

                if (!isDuplicate)
                    this.$emit('add', event)
            }
            this.$refs.autocompleteModele.value = null
        },
        searchModele(input) {
            return axios.get('/modele?name=' + input).then(res => res.data)
        },
        getResultValueModele(result) {
            return result.brand.name + ' - ' + result.name + '(' + result.year_start + '-' + result.year_end + ')'
        },
    },
}
</script>

<style scoped>

</style>
