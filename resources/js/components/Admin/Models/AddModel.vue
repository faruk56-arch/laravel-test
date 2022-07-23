<template>

    <div class="add-model">
        <div class="card add-bloc py-4 px-4 mb-4">

            <router-link class="pl-1 pr-3" :to="{ name: 'admin-modele' }">
                <i class="fal fa-long-arrow-left pr-1"></i> retour
            </router-link>

            <h3 class="mb-4 mt-2 text-blue">{{id?"Éditer un modèle":"Ajouter un modèle"}}</h3>

            <form @submit.prevent="submit" @keypress.enter.prevent>
                <div class="form-group">
                    <label for="researchBrand">Marque du modèle</label>
                    <autocomplete
                        v-if="brandSelected === null"
                        id="researchBrand"
                        name="researchBrand"
                        :search="searchBrand"
                        :get-result-value="getResultValueBrand"
                        :debounce-time="1"
                        ref="autocomplete"
                        @submit="selectBrand($event)"
                        placeholder="Marque"
                    >
                    </autocomplete>
                    <div v-else>

                        <div class="input-fake pl-3">
                            <span class="d-inline-block pl-1">{{ brand.name }}</span>
                            <span class="cursor-p ml-2 text-danger" @click="brandSelected = null">
                                <i class="fas fa-times-circle"></i>
                            </span>
                        </div>

                    </div>
                </div>
                <div class="form-group form-sm">
                    <label for="name">Nom du modèle</label>
                    <input name="name" class="form-control" id="name" type="text" v-model="name" placeholder="Nom du modèle">
                </div>
                <div class="row">

                    <div class="form-group form-sm col-md-6">
                        <label for="start">Année de début de fabrication</label>
                        <input name="year_start" class="form-control" id="start" type="text" v-model="year_start" placeholder="Année de début">
                    </div>
                    <div class="form-group form-sm col-md-6">
                        <label for="end">Année de début de fabrication</label>
                        <input name="year_end" class="form-control" id="end" type="text" v-model="year_end" placeholder="Année de fin">
                    </div>

                </div>

                <div class="form-group form-sm mb-0">
                    <label for="min">Nom du fichier de la miniature (sans l'extension .png/.jpg)</label>
                    <input name="img" class="form-control" id="min" type="text" v-model="img" placeholder="Miniature">
                </div>
                <compatible-modeles :compatible="compatible" @add="compatible.push($event)" @remove="compatible.splice($event, 1)" class="compatible-modeles"></compatible-modeles>
                <button type="submit" class="btn btn-sm btn-primary mt-4"
                        :class="brandSelected!=null&&name!=''&&year_start!=''&&year_end!=''?'':'disabled'">
                    {{ id ? 'Mettre à jour' : 'Ajouter' }}
                </button>
            </form>

        </div>

    </div>
</template>
<script>

import {mapActions, mapGetters} from "vuex";
import CompatibleModeles from "./CompatibleModeles";

export default {
    components: {CompatibleModeles},
    data() {
        return {
            brandSelected: null,
            brand: null,
            name: '',
            year_start: '',
            year_end: '',
            img: '',
            compatible: []
        }
    },
    props: {
        id: {
            required: false
        }
    },
    methods: {
        ...mapActions('cars', ['newCar', 'updateCar']),
        submit() {
            if (this.brandSelected != null && this.name != '' && this.year_start != '' && this.year_end != '') {
                if (this.id)
                    this.updateCar([this.id, {
                        name: this.name,
                        brand_id: this.brandSelected,
                        year_start: this.year_start,
                        year_end: this.year_end,
                        img: this.img,
                        compatible_modeles: this.compatible
                    }])
                else
                    this.newCar([this.name, this.brandSelected, this.year_start, this.year_end, this.img, this.compatible])
                this.$router.push({name: 'admin-modele'})
            }
        },
        selectBrand(event) {
            if (!event)
                return
            this.brandSelected = event.id
            this.brand = event
        },
        searchBrand(input) {
            return axios.get('/brand?name=' + input).then(res => res.data)
        },
        getResultValueBrand(result) {
            return result.name
        },
    },
    computed: {
        ...mapGetters('cars', ['getCar'])
    },
    created() {
        if (this.id) {
            const modele = this.getCar(parseInt(this.id))
            this.brandSelected = modele.brand_id
            this.brand = modele.brand
            this.name = modele.name
            this.compatible = modele.compatible_modeles
            this.year_start = modele.year_start
            this.year_end = modele.year_end
            this.img = modele.img
        }
    }
}
</script>
