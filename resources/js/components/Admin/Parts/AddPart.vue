<template>

    <div class="">
        <div class="card add-bloc py-4 px-4 mb-4">

            <router-link class="pl-1 pr-3" :to="{ name: 'admin-part' }">
                <i class="fal fa-long-arrow-left pr-1"></i> retour
            </router-link>

            <h3 class="mb-4 mt-2 text-blue">{{id?"Éditer une pièce":"Ajouter une pièce"}}</h3>

            <form @sumbit.prevent>
                <div class="form-group">

                    <b-form-checkbox v-model="boolCar" name="check-button" switch @click="carSelected=null">
                      Cette pièce est disponible pour tous les véhicules
                    </b-form-checkbox>

                </div>
                <div class="form-group" v-if="!boolCar">
                    <label for="researchModele">Véhicule avec laquelle la pièce est compatible <sup class="text-danger">*</sup></label>
                    <autocomplete
                        v-if="carSelected === null"
                        id="researchModele"
                        name="researchModele"
                        :search="searchModele"
                        :get-result-value="getResultValueModele"
                        :debounce-time="1"
                        @submit="selectCar($event)"
                        placeholder="Marques, modèle, etc..."
                        class="w-100">
                        <template #result="{ result, props }">
                            <li v-bind="props">
                                <div class="media">
                                    <img v-if="result.img" class="align" :src="'/images/Cars/tn/'+result.img+'.png'"
                                         @error="result.img = null">
                                    <img v-else class="align" :src="'/images/Cars/tn/emptycar.png'">
                                    <span
                                        class="media-body">{{ result.brand.name + ' - ' + result.name + ' (' + result.year_start + '-' + result.year_end + ')' }}</span>
                                    <div class="details"></div>
                                </div>
                            </li>
                        </template>
                    </autocomplete>
                    <div v-else class="input-fake" style="height:60px;padding-left:0px !important">

                        <img v-if="modele.img" class="align car-img mx-2"
                             :src="'/images/Cars/tn/'+modele.img+'.png'" style="width:30%" @error="modele.img=null">
                        <img v-else class="align car-img mx-2" :src="'/images/Cars/tn/emptycar.png'" style="width:30%">

                        {{ modele.brand.name + ' - ' + modele.name + ' (' + modele.year_start + '-' + modele.year_end + ')' }}
                        <span class="cursor-p ml-2 text-danger"  @click="carSelected = null"><i class="fas fa-times-circle"></i></span>
                    </div>
                </div>
                <div class="form-group form-sm" v-if="id">
                    <label for="partName-fr">Nom de la pièce "FR"</label>
                    <input id="partName-fr" class="form-control" type="text" v-model="part.translation.name.fr_FR"
                           placeholder="Nom de la pièce">
                    <label for="partName-en">Nom de la pièce "EN"</label>
                    <input id="partName-en" class="form-control" type="text" v-model="part.translation.name.en_EN"
                           placeholder="Nom de la pièce">
                    <label for="partName-de">Nom de la pièce "DE"</label>
                    <input id="partName-de" class="form-control" type="text" v-model="part.translation.name.de_DE"
                           placeholder="Nom de la pièce">
                </div>
                <div v-else>
                    <label for="partName-fr">Nom de la pièce "FR"</label>
                    <input id="partName-fr" class="form-control" type="text" v-model="nameFR"
                           placeholder="Nom de la pièce">
                </div>
                <div class="form-group">
                    <label>Liste des synonymes</label>
                    <ul>
                        <li v-for="(s,i) in synonym" class="form-group form-sm">
                            <input type="text" v-model="synonym[i]" class=""
                                   :placeholder="'Synonyme '+(i+1)" @keypress.enter.prevent>
                            <span @click="synonym.splice(i,1)" class="text-danger cursor-p"><i class="far fa-times-circle"></i></span>
                        </li>
<!--                        <li class="form-group form-sm"><input type="text" placeholder="Nouveau synonyme" v-model="synonymCurrent"-->
<!--                                   @change="synonym.push(synonymCurrent);synonymCurrent=''" @keypress.enter.prevent>-->
<!--                        </li>-->
                    </ul>
                    <span class="btn btn-sm bg-gold" @click="synonym.push('')">Nouveau synonyme</span>
                </div>

                <!--        <input type="text" v-model="nameDE" placeholder="Nom de la pièce(Allemand)">-->
                <!--        <input type="text" v-model="nameEN" placeholder="Nom de la pièce(Anglais)">-->
                <div class="form-group form-sm">
                    <label for="categorySelected">Catégorie de la pièce <sup class="text-danger">*</sup></label>
                    <select class="form-control" v-model="categorySelected" id="categorySelected">
                        <option v-for="category in allCategories" :value="category.id">{{ translation('name', category) }}
                        </option>
                    </select>
                </div>

                <router-link tag="button" class="btn btn-sm bg-gold"
                             :class="categorySelected!=''?'':'disabled'" :to="{name:'admin-part'}"
                             @click.native="id ? updatePart([id, {fr_FR: part.translation.name.fr_FR, en_EN: part.translation.name.en_EN, de_DE: part.translation.name.de_DE, bywords: synonym, modele_id: carSelected,category_id: categorySelected}]) : newPart({fr_FR: nameFR,bywords: synonym,modele_id: carSelected,category_id: categorySelected})">
                    {{ id ? 'Mettre à jour' : 'Ajouter' }}
                </router-link>
            </form>

        </div>


    </div>
</template>
<script>

import {mapActions, mapGetters} from "vuex";
import mxResearches from "../../../mixins/Researches";

export default {
    data() {
        return {
            carSelected: null,
            part: null,
            synonym: [],
            categorySelected: '',
            synonymCurrent: '',
            boolCar: true,
            modele: null,
            nameFR: ''
        }
    },
    props: {
        id: {
            required: false
        }
    },
    methods: {
        ...mapActions('parts', ['newPart']),
        ...mapActions('parts', ['updatePart']),
        ...mapActions('parts', ['fetchPart']),
    },
    mixins: [mxResearches],
    async created() {
        await this.$store.dispatch('categories/fetchAllCategories');
        if (this.id) {
            this.part = await this.fetchPart(this.id)
            this.boolCar = this.part.modele_id === null
            this.carSelected = this.part.modele_id === null ? null : this.part.modele_id
            this.categorySelected = this.part.category_id
            this.synonym = this.part.bywords === null ? [] : this.part.bywords
            this.modele = this.part.modele
        }
    },
    computed: {
        ...mapGetters('categories', ['allCategories']),
        ...mapGetters('parts', ['getPart']),
    }
}
</script>
