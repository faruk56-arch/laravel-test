<template>
    <div class="research-proposal-list">
        <div class="card add-bloc py-4 px-4 mb-4">
            <a class="cursor-p pl-1 pr-3" @click="$router.go(-1)">
                <i class="fal fa-long-arrow-left pr-1"></i> retour
            </a>
            <div class="container-fluid position-relative pt-2" v-if="isResearchLoaded && research">
                <h3 class="mb-3 mt-2 text-blue h3-serif">Modification de la recherche</h3>
                <div class="form form-xl pb-2">

                    <label for="category" class="text-blue px-0 d-block">Modèle</label>

                    <div class="d-flex align-items-center">
                        <img v-if="research.modele.modele.img != null" class="little-car mr-3" :src="'/images/Cars/'+research.modele.modele.img+'.png'">
                        <img v-else :src="'/images/Cars/emptycar.png'" class="little-car mr-3">
                        <span>
                            {{ research.modele.modele.brand.name }} {{ research.modele.modele.name }}
                    {{ research.modele.version }} de {{ research.modele.year }}
                        </span>
                    </div>


                </div>
                <div class="alert alert-info">
                    <i class="far fa-info-circle"></i>
                    Modèle non éditable, veuillez supprimer la recherche si le modèle n'est pas adapté.
                </div>

                <div class="row" v-if="!form.part_id&&form.unknown_part">
                    <div class="form form-xl pt-2 py-2 col">
                        <label for="category" class="text-blue px-0 d-block">Catégorie de la pièce
                        </label>
                        <select id="category" class="form-control" v-model="form.unknown_part.category">
                            <option v-for="category in allCategories"
                                    :value="translation('name', category) ">{{ translation('name', category) }}
                            </option>
                        </select>
                    </div>

                    <div class="form form-xl pt-2 py-2 col">
                        <label for="part" class="text-blue px-0 d-block">Pièce suggérée
                        </label>
                        <input type="text" class="w-100" id="part" v-model="form.unknown_part.message" @input="keySubmit+=1" placeholder="Réference ou numéro d'identification"/>
                    </div>
                </div>

                <template v-else>
                    <div class="form form-xl pb-2">
                        <label for="researchPart" class="text-blue px-0 d-block">Nom de la pièce</label>
                        <autocomplete
                            id="researchPart"
                            ref="autocomplete1"
                            name="researchPart"
                            key="1"
                            :search="searchPart"
                            :get-result-value="getResultValuePart"
                            :debounce-time="1"
                            :default-value="translation('name', research.part) +' - '+ translation('name', research.part.category)"
                            @submit="selectPart($event)"
                            placeholder="Pièce détachée"
                            class="w-100 auto-cat-home">
                        </autocomplete>
                    </div>

                </template>

<!--                <div class="form form-xl" v-if="research.status">-->
<!--                    <label for="details-fr" class="text-blue px-0 pt-2 d-block">Des précisions sur la pièce recherchée ? (FR)</label>-->
<!--                    <textarea class="w-100 pt-3" rows="4" type="text" id="details-fr" placeholder="Je recherche une version particulière de la pièce..." v-model="form.details_FR"/>-->
<!--                    <label for="details-de" class="text-blue px-0 pt-2 d-block">Des précisions sur la pièce recherchée ? (DE)</label>-->
<!--                    <textarea class="w-100 pt-3" rows="4" type="text" id="details-de" placeholder="Je recherche une version particulière de la pièce..." v-model="form.details_DE"/>-->
<!--                    <label for="details-en" class="text-blue px-0 pt-2 d-block">Des précisions sur la pièce recherchée ? (EN)</label>-->
<!--                    <textarea class="w-100 pt-3" rows="4" type="text" id="details-en" placeholder="Je recherche une version particulière de la pièce..." v-model="form.details_EN"/>-->
<!--                </div>-->
                <div class="form form-xl">
                    <label for="details" class="text-blue px-0 pt-2 d-block">Des précisions sur la pièce recherchée ? -
                        <template v-if="research.original_language">
                            langue d'origine :
                            <select v-model="form.original_language">
                                <option value="fr_FR">Français</option>
                                <option value="de_DE">Allemand</option>
                                <option value="en_EN">Anglais</option>
                            </select>
                        </template>
                        <template v-else>
                            Attention : rédiger en "{{ $i18n.locale }}"
                        </template>
                    </label>
                    <textarea class="w-100 pt-3" rows="4" type="text" id="details" placeholder="Je recherche une version particulière de la pièce..." v-model="form.details"/>
                </div>

                <div class="form form-xl pt-2 py-3">
                    <label for="reference" class="text-blue px-0 d-block">Quels types de pièces recherchez-vous ?
                        <span class="text-danger">*</span>
                    </label>
                    <ul class="list-unstyled mb-0">
                        <li class="form-group d-flex align-items-center mb-0 part-type-item" v-for="(type,i) in partType"
                            :class="form.types.find(t=>t==type.value)?'checked':''">
                            <b-form-checkbox switch size="lg" :key="type.value" :id="type.value"
                                             @change="editType(type.value)"
                                             :checked="form.types.find(t=>t==type.value)?true:false">
                            </b-form-checkbox>
                            <label class="cursor-p" :for="type.value">{{ type.text }}</label>
                        </li>
                    </ul>
                </div>

                <div class="form form-xl pt-2 py-2">
                    <label for="reference" class="text-blue px-0 d-block">Référence de la pièce
                    </label>
                    <input type="text" class="w-100" id="reference" v-model="form.reference" placeholder="Réference ou numéro d'identification"/>
                </div>
                <div class="form form-xl pt-2 py-2">
                    <label for="compatibility" class="text-blue px-0 d-block">Compatibilité de la pièce
                    </label>
                    <input type="text" class="w-100" id="compatibility" v-model="form.compatible_suggestion" placeholder="Pièce compatible avec..."/>
                </div>


                <div class="form form-xl pt-3">
                    <label class="text-blue px-0 d-block">Photos <small><em>(Maximum 5)</em></small></label>
                    <label for="images" class="drop-img py-4 text-blue text-center">
                        <p class="lead mb-0 font-weight-normal">Déposez les fichiers ici</p>
                        <p class="mb-0">ou, si vous préférez</p>
                        <button @click="$refs.files.click()" class="btn bg-grey" id="images">Sélectionnez les fichiers
                        </button>
                    </label>
                    <input class="d-none" type="file" id="files" ref="files" multiple @change="handleFiles()"
                           accept="image/*"/>
                    <ul class="list-unstyled drop-img-list" v-if="form.img.length != 0">
                        <li v-for="(file, key) in form.img" class="file">
                            <div class="file-listing">
                                <img :key="keyPics" :src="imagePreview[key]">
                            </div>
                            <div class="edit-tools d-flex justify-content-center py-2 px-3 align-items-center">
                                <span class="shadow cursor-p mx-1 rounded-circle bg-white" placement='bottom'
                                      v-on:click="removeFile(key)" v-b-tooltip.hover title="Supprimer"><i
                                    class="fal fa-trash-alt text-danger"></i></span>
                            </div>
                        </li>
                    </ul>
                </div>
                <a @click="submit" class="btn btn-sm bg-gold mb-2 mt-4"
                   :class="!form.part_id&&form.unknown_part&&!form.unknown_part.message||form.types.length == 0?'disabled':''"
                   dusk="details-next" :key="keySubmit">
                    Enregistrer les modifications
                </a>
            </div>
            <span v-else class="loader"></span>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";
import mxResearches from "../../../mixins/Researches";
import {status} from "../../../mixins/status";

export default {
    name: "AdminEditResearch",
    data() {
        return {
            research: null,
            form: {
                part_id: null,
                category: null,
                reference: null,
                details:null,
                details_FR: null,
                details_DE: null,
                details_EN: null,
                img: [],
                types: [],
                unknown_part: null,
                original_language:null,
            },
            carSelected: null,
            imagePreview: [],
            keyPics: 0,
            keySubmit: 0,
        }
    },
    mixins: [mxResearches, status],
    created() {
        this.fetchAll();
    },
    mounted() {
        this.fetchAll();
        let app = this;
        for (var i = 0; i < this.form.img.length; i++) {
            let reader = new FileReader();
            reader.addEventListener("load", function () {
                app.imagePreview.push(reader.result);
            }.bind(app), false);
            if (/\.(jpe?g|png|gif)$/i.test(this.form.img[i].name)) {
                reader.readAsDataURL(this.form.img[i]);
            }
        }
        var droparea;
        droparea = document.querySelector(".drop-img");
        if (droparea != null) {
            AddEventListeners();
        }

        function AddEventListeners() {
            droparea.addEventListener("dragenter", DragEnter, false);
            droparea.addEventListener("dragleave", DragLeave, false);
            droparea.addEventListener("dragover", DragOver, false);
            droparea.addEventListener("drop", Drop, false);
        }

        function DragEnter(e) {
            e.stopPropagation();
            e.preventDefault();
            droparea.classList.add("dragging-over");
        }

        function DragOver(e) {
            e.stopPropagation();
            e.preventDefault();
        }

        function DragLeave(e) {
            e.stopPropagation();
            e.preventDefault();
            droparea.classList.remove("dragging-over");
        }

        function Drop(e) {
            e.stopPropagation();
            e.preventDefault();
            droparea.classList.remove("dragging-over");
            var dt = e.dataTransfer;
            var files = dt.files;
            if (files.length + app.form.img.length > 5) {
                alert('5 photos maximum')
            } else {
                for (var i = 0; i < files.length; i++) {
                    let reader = new FileReader();
                    reader.addEventListener("load", function () {
                        app.imagePreview.push(reader.result);
                    }.bind(app), false);
                    if (/\.(jpe?g|png|gif)$/i.test(files[i].name)) {
                        app.form.img.push(files[i]);
                        reader.readAsDataURL(files[i]);
                    }
                }
            }
        }
    },
    methods: {
        ...mapActions('messages', ['newMessage']),
        ...mapActions('parts', ['assignPart', 'newPart']),
        ...mapActions('researches', ['fetchAllResearches', 'fetchResearch']),
        ...mapActions('admin', ['changeResearchStatus', 'editResearch']),
        editType(id) {
            let index = this.form.types.findIndex(t => t == id);
            if (index !== -1) this.form.types.splice(index, 1);
            else this.form.types.push(parseInt(id))
        },
        fetchAll() {
            this.fetchAllResearches();
            this.fetchResearch(this.$route.params.id).then(res => {
                this.research = res.data
                if (this.research.part) {
                    this.form.part_id = this.research.part.id
                    this.form.category = this.research.part.category.id;
                } else {
                    this.form.unknown_part = {};
                    this.form.unknown_part.category = this.research.unknown_part.category
                    this.form.unknown_part.message = this.research.unknown_part.message
                }
                this.form.reference = this.research.reference;
                this.form.details = this.research.original_details;
                // if (this.research.translation.details) {
                //     this.form.details_FR = this.research.translation.details.fr_FR;
                //     this.form.details_DE = this.research.translation.details.de_DE;
                //     this.form.details_EN = this.research.translation.details.en_EN;
                // } else {
                //     this.form.details_FR = this.research.details;
                //     this.form.details_DE = this.research.details;
                //     this.form.details_EN = this.research.details;
                // }
                this.form.img = this.research.img;
                this.imagePreview = [...this.research.img];
                this.form.types = this.research.types;
                this.form.compatible_suggestion = this.research.compatible_suggestion;
                this.form.original_language = this.research.original_language;
                this.carSelected = this.research.modele.modele.id
            });
            this.$store.dispatch('categories/fetchAllCategories');


        },
        removeFile(key) {
            this.form.img.splice(key, 1);
            this.imagePreview.splice(key, 1);
        },
        handleFiles() {
            let app = this;
            let uploadedFiles = this.$refs.files.files;
            if (uploadedFiles.length + this.form.img.length > 5) {
                alert('5 photos maximum')
            } else {
                for (var i = 0; i < uploadedFiles.length; i++) {
                    let reader = new FileReader();
                    reader.addEventListener("load", function () {
                        app.imagePreview.push(reader.result);
                    }.bind(app), false);
                    if (/\.(jpe?g|png|gif)$/i.test(uploadedFiles[i].name)) {
                        app.form.img.push(uploadedFiles[i]);
                        reader.readAsDataURL(uploadedFiles[i]);
                    }
                }
            }
            this.$refs.files.value = ""
        },
        submit: function () {
            let form = new FormData();
            let imagesUrl = [];
            for (var i = 0; i < this.form.img.length; i++) {
                if (typeof (this.form.img[i]) === 'string') {
                    imagesUrl.push(this.form.img[i]);
                } else {
                    let file = this.form.img[i];
                    form.append('files[' + i + ']', file);
                }
            }
            form.append('images', JSON.stringify(imagesUrl));
            form.append('part_id', this.form.part_id);
            if (this.form.unknown_part) {
                form.append('unknown_part', JSON.stringify({
                    'category': this.form.unknown_part.category,
                    'message': this.form.unknown_part.message
                }));
            }
            if(this.form.original_language!==this.research.original_language){
                form.append('original_language', this.form.original_language);
            }
            form.append('reference', this.form.reference);
            form.append('compatible_suggestion', this.form.compatible_suggestion);
            // form.append('details_FR', this.form.details_FR);
            // form.append('details_DE', this.form.details_DE);
            // form.append('details_EN', this.form.details_EN);
            form.append('details', this.form.details);
            form.append('types', JSON.stringify(this.form.types));
            this.editResearch([form, this.$route.params.id]).then((res) => {
                this.$router.push({name: 'adminResearchDetails', params: {id: this.$route.params.id}});
            });
        },
        selectPart(event) {
            this.form.part_id = event.id
        },
    },
    computed: {
        ...mapGetters('researches', ['isResearchLoaded']),
        ...mapGetters('categories', ['allCategories']),
    }
}
</script>

<style scoped>

</style>
