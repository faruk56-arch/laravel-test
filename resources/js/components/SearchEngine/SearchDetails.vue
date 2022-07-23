<template>
    <div class="search-car-details">
        <div class="form mb-3">

            <a class="basic text-bold-dark-grey d-inline-block mb-2 pointer cursor-p" @click="$router.go(-1)">
                <i class="fal fa-long-arrow-left"></i>
                {{ $t('back') }}</a>

            <h3 class="text-blue pb-0 pt-2" :class="{'pb-3' : !showRecommendation}">{{ $t('complementary_info') }}</h3>

            <div class="alert alert-warning d-flex justify-content-between align-items-center" v-if="showRecommendation">
                <span>
                    <i class="fal fa-lightbulb-on"></i> {{ $t('lets_gain_time') }}
                </span>
                <i class="fal fa-times cursor-p" @click='showRecommendation = false'></i>
            </div>

            <div class="row mb-3" v-if="data.car.custom==0||(data.car.custom!=0&&!data.car.year!='')">
                <div class="form form-xl col-md-6" v-if="data.car.custom==0||(data.car.custom!=0&&!data.car.year!='')">
                    <label for="year" class="text-blue px-0 d-block">{{ $t('year_vehicle') }} <span class="text-danger">*</span></label>
                    <select class="w-100" v-model="year" id="year" @change="editYear">
                        <option value="">{{ $t('select_year') }}</option>
                        <option v-for="year in years">{{year}}</option>
                    </select>
                </div>
                <div class="form form-xl col-md-6" v-if="data.car.custom==0||(data.car.custom!=0&&!data.car.version!='')">
                    <label for="details" class="text-blue px-0 d-block">{{ $t('vehicle_version') }}</label>
                    <input class="w-100" type="text" id="version" :placeholder="$t('motor_paint_etc')" v-model="version" @change="editVersion"/>
                </div>
            </div>
            <div class="form form-xl">
                <label for="details" class="text-blue px-0 d-block">{{ $t('part_precisions') }}</label>
                <textarea class="w-100 pt-3" rows="4" type="text" id="details" :placeholder="$t('looking_version_part')" v-model="details" @change="editDetails"/>
            </div>
            <div class="form form-xl">
                <label for="compatible_suggestion" class="text-blue px-0 d-block">{{ $t('part_is_compatible') }}</label>
                <input class="w-100" type="text" id="compatible_suggestion"  v-model="compatible_suggestion"/>
            </div>

            <div class="form form-xl pt-2 py-3">
                <label for="reference" class="text-blue px-0 d-block">
                    {{ $t('kind_of_part') }}
                    <span class="text-danger">*</span>
                    <span v-b-modal.guide-des-typologie class="text-secondary d-block d-lg-inline-block"><small><i class="fal fa-book ml-lg-2"></i> <u>{{ $t('part_type_guide') }}</u></small></span>
                </label>

                <modal-origin></modal-origin>
                <modal-state></modal-state>


                <ul class="list-unstyled mb-0">
                    <li class="form-group d-flex align-items-center mb-0 part-type-item" v-for="(type,i) in partType"  :class="types.find(t=>t==type.value)?'checked':''">

                        <b-form-checkbox switch size="lg" :key="type.value" :id="type.value" @change="editType(type.value)" :checked="types.find(t=>t==type.value)?true:false">
                        </b-form-checkbox>

                        <label class="cursor-p" :for="type.value">{{ type.text }}</label>
<!--
                        <input :key="type.value" type="checkbox" :id="type.value" @change="editType(type.value)"> -->
                    </li>
                </ul>


<!--                <b-form-select id="input-type" v-model="type" @change="editType">-->
<!--                    <b-form-select-option :value="null" disabled>Choisir un type de pièce</b-form-select-option>-->
<!--                    <b-form-select-option v-for="type in partType" :value="type.value" :key="type.value">{{ type.text }}</b-form-select-option>-->
<!--                </b-form-select>-->
            </div>

            <div class="form form-xl pt-2 py-2">
                <label for="reference" class="text-blue px-0 d-block">{{ $t('part_reference') }}
                 </label>
                <input type="text" class="w-100" id="reference" v-model="reference" @change="editReference" :placeholder="$t('reference_id_etc')"/>
            </div>

            <div class="form form-xl pt-3">
                <label class="text-blue px-0 d-block">{{ $t('maximize_add_photos') }}
                    <small><em>({{ $t('maximum_photos') }})</em></small></label>
                <label for="images" class="drop-img py-4 text-blue text-center">
                    <p class="lead mb-0 font-weight-normal">{{ $t('drop_files_here') }}</p>
                    <p class="mb-0">{{ $t('or_if_prefer') }}</p>
                    <button @click="$refs.files.click()" class="btn bg-grey" id="images">{{ $t('select_files') }}</button>
                </label>
                <input class="d-none" type="file" id="files" ref="files" multiple @change="handleFiles()" accept="image/*"/>
                <ul class="list-unstyled drop-img-list" v-if="images.length != 0">
                    <li v-for="(file, key) in images" class="file">
                        <div class="file-listing">
                            <img :key="keyPics" :src="imagePreview[key]">
                        </div>
                        <div class="edit-tools d-flex justify-content-center py-2 px-3 align-items-center">
                            <span class="shadow cursor-p mx-1 rounded-circle bg-white" placement='bottom' v-on:click="removeFile(key)" v-b-tooltip.hover :title="$t('remove')"><i class="fal fa-trash-alt text-danger"></i></span>
                        </div>

                    </li>
                </ul>
            </div>

        </div>
        <p class="text-secondary"><span class="text-danger">*</span> {{ $t('required_informations') }}</p>
        <a class="btn btn-lg bg-blue mb-2" v-if="$auth.check()" :class="!data.part.translation||!year||types.length == 0||isSubmitting?'disabled':''" @click="sendResearch" dusk="details-next" :key="keySubmit">
            <template v-if="isSubmitting">
                <span class="loader loader-white"></span>
                {{ $t('sending_progress') }}
            </template>
            <template v-else>
                {{ $t('finish') }}
            </template>
        </a>
        <router-link dusk="details-next" class="btn btn-lg bg-blue mb-2" v-else :class="!data.part.translation||!year||types.length == 0?'disabled':''" :to="{ name: 'createAccount', params:{idCar:data.car.id,custom:data.car.custom,idCategory: data.category.id, idPart:$route.params.idPart}, query: {message: $route.query.message}}">{{ $t('next_step') }}</router-link>
    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";
import {status} from "../../mixins/status";
import ModalOrigin from "../Shared/ModalOrigin";
import ModalState from "../Shared/ModalState";

export default {
    name: "SearchDetails",
    props: ['data'],
    components:{ModalOrigin,ModalState},
    mixins: [status],
    data() {
        return {
            compatible_suggestion: null,
            images: [],
            reference: '',
            details: '',
            imagePreview: [],
            years: [],
            year: '',
            version: '',
            types: [17,18,19,20],
            isSubmitting:false,
            keySubmit:0,
            showRecommendation: true,
        }
    },

    mounted() {
        let app = this;
        if (this.data.images.length > 0) this.images = this.data.images;
        if (this.data.reference) this.reference = this.data.reference;
        if (this.data.types.length>0) this.types = this.data.types;
        if (this.data.version) this.version = this.data.version;
        if (this.data.year) this.year = this.data.year;

        if (this.data.details) this.details = this.data.details;
        for (var i = 0; i < this.images.length; i++) {
            let reader = new FileReader();
            reader.addEventListener("load", function () {
                app.imagePreview.push(reader.result);
            }.bind(app), false);
            if (/\.(jpe?g|png|gif)$/i.test(this.images[i].name)) {
                reader.readAsDataURL(this.images[i]);
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
            if (files.length + app.images.length > 5) {
                alert('5 photos maximum')
            } else {
                for (var i = 0; i < files.length; i++) {
                    let reader = new FileReader();
                    reader.addEventListener("load", function () {
                        app.imagePreview.push(reader.result);
                    }.bind(app), false);
                    if (/\.(jpe?g|png|gif)$/i.test(files[i].name)) {
                        app.images.push(files[i]);
                        reader.readAsDataURL(files[i]);
                    }
                    app.editImages();
                }
            }
        }
    },
    methods: {
        ...mapActions('researches', ['newResearch']),
        ...mapActions('userModels', ['fetchAllUserModels']),
        sendResearch() {
            let app = this;
            let types = JSON.stringify(this.types);
            this.isSubmitting=true;
            this.newResearch([
                this.data.car.id, this.data.part.id, this.$auth.user().id, this.data.car.custom, this.data.part.translation,
                this.data.category.id, this.reference, this.details, this.images, this.data.year, this.data.version, types, this.compatible_suggestion
            ]).then((res) => {
                app.fetchAllUserModels(true).then((res) => {
                    app.$router.push({name: 'researchList'}).catch(() => {})
                });

            })
        },
        removeFile(key) {
            this.images.splice(key, 1);
            this.imagePreview.splice(key, 1);
            this.editImages();
        },
        handleFiles() {
            let app = this;
            let uploadedFiles = this.$refs.files.files;
            if (uploadedFiles.length + this.images.length > 5) {
                alert('5 photos maximum')
            } else {
                for (var i = 0; i < uploadedFiles.length; i++) {
                    let reader = new FileReader();
                    reader.addEventListener("load", function () {
                        app.imagePreview.push(reader.result);
                    }.bind(app), false);
                    if (/\.(jpe?g|png|gif)$/i.test(uploadedFiles[i].name)) {
                        app.images.push(uploadedFiles[i]);
                        reader.readAsDataURL(uploadedFiles[i]);
                    }
                    this.editImages();
                }
            }
            this.$refs.files.value = ""
        },
        editReference() {
            this.$emit('reference', this.reference);
        },editType(id) {
            let index = this.types.findIndex(t=>t==id);
            if (index!==-1)this.types.splice(index,1);
            else this.types.push(parseInt(id))
            this.$emit('types', this.types);
        }, editYear() {
            this.$emit('year', this.year);
        }, editVersion() {
            this.$emit('version', this.version);
        }, editDetails() {
            this.$emit('details', this.details);
        }, editImages() {
            this.$emit('images', this.images);
        },
    },
    computed: mapGetters('parts', ['allModeleCategoryParts']),
    watch: {
        'data.car': {
            deep: true,
            immediate: true,
            handler() {
                if (this.data.car.year_start && this.data.car.year_end) {
                    for (let i = this.data.car.year_start; i <= this.data.car.year_end; i++) {
                        this.years.push(i);
                    }
                    if (this.data.year) this.year = this.data.year;
                }
            }
        },
    }
}
</script>

<style scoped>

</style>
