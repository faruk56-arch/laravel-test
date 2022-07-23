<template>
    <div class="add-alert">
        <div class="bg-white shadow container-fluid py-lg-4 py-3 px-4 mb-4">
            <div class="row">
                <div class="col d-flex justify-content-between align-items-center">
                    <div>
                          <span class="pl-1 pr-3 cursor-p" @click="$router.go(-1)">
                        <i class="fal fa-2x fa-arrow-left"></i></span>
                        <h1 class="h2 d-inline-block text-blue mb-0 pt-lg-3 pt-2">{{ $t('new_simplified_announce') }}</h1>
                    </div>
                    <top-nav></top-nav>
                </div>
            </div>
        </div>
        <div class="px-md-5 text-blue">
            <form @submit.prevent>
                <div class="container mb-5">
                    <div class="pt-md-4 pb-md-2">
                        <p class="lead text-blue font-weight-normal mb-0">{{ $t('receive_mail_research') }}</p>
                    </div>
                    <div class="card px-4 pt-4 pb-4 mb-4 mt-4">
                        <p class="lead text-blue mb-0">{{ $t('vehicles_compatible') }}</p>
                        <label for="researchBrand">
                            {{ $t('vehicles_with_alert') }}
                        </label>
                        <autocomplete
                            id="researchModele"
                            name="researchModele"
                            :search="searchModele"
                            :get-result-value="getResultValueModele"
                            :debounce-time="1"
                            ref="autocompleteModele"
                            @submit="selectCar($event)"
                            @keypress.enter.prevent
                            :placeholder="$t('brand_modeles_etc')"
                            class="col px-0"
                        >
                            <template #result="{ result, props }">
                                <li v-bind="props">
                                    <div class="media">
                                        <img v-if="result.img" class="align" :src="'/images/Cars/tn/'+result.img+'.png'" @error="result.img = null">
                                        <img v-else class="align" :src="'/images/Cars/tn/emptycar.png'" >
                                        <span class="media-body">{{ result.brand.name + ' - ' + result.name +' ('+result.year_start+'-'+result.year_end+')'}}</span>
                                        <div class="details"></div>
                                    </div>
                                </li>
                            </template>
                        </autocomplete>
                        <div class="mt-3">
                            <span v-b-modal.modal-not-found class="basic-link text-blue">{{ $t('cant_find_vehicle') }}</span>
                        </div>
                        <b-modal id="modal-not-found" :title="$t('vehicle_not_found')"
                                 @show="resetModal"
                                 @hidden="resetModal"
                                 @ok="handleOk"
                                 content-class="rounded border-0 shadow-lg">
                            <b-form v-if="!success" class="form-sm py-0 mb-n3">
                                <b-form-group
                                    id="input-group-2"
                                    label-class="required"
                                    :label="$t('your_vehicle_not_found')"
                                    label-for="input-2"
                                >
                                    <b-form-input
                                        id="input-2"
                                        v-model="contact.car"
                                        type="text"
                                        required
                                        :placeholder="$t('brand_modele_etc')"
                                    ></b-form-input>
                                </b-form-group>
                                <p class="text-secondary"><span class="text-danger">*</span> {{ $t('required_informations') }}</p>
                            </b-form>

                            <template v-else>
                                <p class="lead text-center text-blue mb-1">{{ $t('thanks_demand') }}</p>
                                <div class="text-blue py-2">{{ $t('answer_by_mail') }}</div>
                            </template>
                            <template #modal-footer="{ ok, cancel, hide }">
                                <button class="btn px-3 float-right btn-sm btn-outline-secondary" @click="hide()" v-if="!success">
                                    {{ $t('cancel') }}
                                </button>
                                <button class="btn px-5  btn-sm bg-gold" @click="ok()" v-if="!success"
                                        :class="(!$auth.check()&&contact.email&&contact.car)||$auth.check()&&contact.car?'':'disabled'">
                                    {{ $t('send') }}
                                </button>
                                <!-- Button with custom close trigger value -->
                            </template>

                        </b-modal>
                        <b-modal id="modal-part-not-found" :title="$t('part_not_found')"
                                 @show="resetModal"
                                 @hidden="resetModal"
                                 @ok="handleOk"
                                 content-class="rounded border-0 shadow-lg">
                            <b-form v-if="!success" class="form-sm py-0 mb-n3">
                                <b-form-group
                                    id="input-group-2"
                                    label-class="required"
                                    :label="$t('your_unknown_part')"
                                    label-for="input-2"
                                >
                                    <b-form-input
                                        id="input-2"
                                        v-model="contact.part"
                                        type="text"
                                        required
                                        :placeholder="$t('name_part')"
                                    ></b-form-input>
                                </b-form-group>
                                <p class="text-secondary"><span class="text-danger">*</span> {{ $t('required_informations') }}</p>
                            </b-form>

                            <template v-else>
                                <p class="lead text-center text-blue mb-1">{{ $t('thanks_demand') }}</p>
                                <div class="text-blue py-2">{{ $t('unknown_part_mail') }}</div>
                            </template>
                            <template #modal-footer="{ ok, cancel, hide }">
                                <button class="btn px-3 float-right btn-sm btn-outline-secondary" @click="hide()" v-if="!success">
                                    {{ $t('cancel') }}
                                </button>
                                <button class="btn px-5  btn-sm bg-gold" @click="ok()" v-if="!success"
                                        :class="contact.part?'':'disabled'">
                                    {{ $t('send') }}
                                </button>
                                <!-- Button with custom close trigger value -->
                            </template>

                        </b-modal>

                        <div class="row ml-n2 mt-2" :key="keyModel">
                            <template v-for="(car,i) in models">
                                <div class="card p-3 m-2 text-center align-items-center flex-column justify-content-center cursor-p"
                                     :class="{ 'card-outline': car.compatible,  'dots': !car.compatible }"
                                     @click="check(i)" >
<!--                                    <span class="remove-file" v-on:click="removeFile( key )"><i class="fal fa-trash-alt bg-white rounded-circle p-2 text-danger"></i></span>-->
                                   <img v-if="car.img" class="w-100 little-car px-1" :src="'/images/Cars/'+car.img+'.png'">
                                    <i v-else class="fad fa-3x mb-2 fa-steering-wheel"></i>
                                    <div class="mt-2" v-if="car.brand"><b>{{ car.brand.name }} {{ car.name }}</b></div>
                                    <i v-if="car.compatible" class="fas fa-check-circle badge-icon"></i>
                                </div>
                            </template>
                        </div>
                        <!-- <small v-if="models.length>0"><em>Cliquez sur un véhicule pour le retirer de la liste</em></small> -->
                    </div>
                    <div class="card px-4 pt-4 pb-2 mb-4">
                        <p class="lead text-blue">{{ $t('part_categories') }} <small>{{ $t('multiple_choices') }}</small></p>
                        <ul class="list-unstyled d-flex flex-wrap">
                            <li v-for="(category, index) in allCategoriesWithParts" class="add-alert-cat-item mr-3 mb-3">
                                <input type="checkbox" name="cat" :id="'cat-'+index" class="d-none" @change="selectCategory(category,false)">
                                <label :for="'cat-'+index" class="rounded shadow border bg-white w-100 text-center position-relative py-3 mb-0 cursor-p">
                                    <i class="fas fa-check-circle d-none text-gold bg-white rounded"></i>
                                    <img :src="category.url" class="w-100 pb-1">
                                    {{ translation('name', category) }}
                                </label>
                            </li>
                        </ul>
                    </div>

                    <ul class="list-unstyled" v-for="(category, index) in allCategoriesWithParts" v-if="categoriesSelected.includes(category.id)">
                        <li class="card px-4 py-3 mb-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="lead text-blue mb-0">{{ translation('name', category) }}</p>
                                </div>
                                <div class="col-md-6 d-flex align-items-center justify-content-end">
                                    <input type="checkbox" :id="'c-'+category.id" @change="selectCategory(category,true)">
                                    <label class="text-blue pl-1 mb-0" :for="'c-'+category.id">{{ $t('category_all_parts') }}</label>
                                </div>
                            </div>
                            <!-- Mettre v-if si all cat is select ou pas -->
                            <CategoryPartList :parts="category.parts" @part-checkbox="selectPart" v-if="!categories.includes(category.id)"></CategoryPartList>
                            <div class="mt-3" v-if="!categories.includes(category.id)">
                                <span v-b-modal.modal-part-not-found class="basic-link text-blue">{{ $t('cant_find_part_list') }}</span>
                            </div>
                        </li>
                    </ul>
                    <div>
                        <button class="btn btn-lg bg-gold w-100" @click="addAlert" :class="!((categories.length>0||parts.length>0)&&models.filter(m=>m.compatible===true).length>0)?'disabled':''">{{ $t('activate_announce') }}</button>
                    </div>
                </div>
            </form>

        </div>

    </div>
</template>
<script>
import {mapActions, mapGetters} from "vuex";
import CategoryPartList from "./CategoryPartList";

const TopNav = () => import(/* webpackChunkName: "group-dashboard" */"../Shared/TopNav");


export default {
    name: 'AddAlert',
    components: {CategoryPartList, TopNav},
    data() {
        return {
            name: 'catalog',
            categoriesSelected: [],
            partSelected: null,
            parts: [],
            models: [],
            success:false,
            contact:{
                car:null,
                part:null,
            },
            configs: [],
            categories: [],
            keyModel: 0

        }
    },
    methods: {
        ...mapActions('alerts', ['newAlert']),
        ...mapActions('messages', ['newMessage', 'newMessageNotAuth']),
        handleOk(bvModalEvt) {
            bvModalEvt.preventDefault()
            if (this.contact.car) {
                this.newMessage({
                    template_id: 5,
                    params: [this.contact.car],
                    type: 'researches'
                }).then(res => {
                    this.success = true;
                });
            }else{
                this.newMessage({
                    template_id: 6,
                    params: [this.contact.part],
                    type: 'researches'
                }).then(res => {
                    this.success = true;
                });
            }
        },
        resetModal() {
            this.contact = {
                part: null,
                car: null
            }
            this.success=false;
        },
        addAlert() {
            let app = this;
            let models = Object.create(this.models);
            models = models.filter(car => car.compatible)
            models = models.map(car => [car.id, car.brand_id])
            if (models.length > 0 && (this.categories.length > 0 || this.parts.length > 0)) {
                this.newAlert([models, this.categories, this.parts])
                    .then(() => this.$router.push({'name': 'researchAlert'}).catch(() => {}))
            }
        },
        check(index) {
            this.models[index].compatible = !this.models[index].compatible;
            this.keyModel++;
            // this.models.splice(index, 1)
        },
        getResultValueModele(result) {
            return result.brand.name + ' - ' + result.name + '(' + result.year_start + '-' + result.year_end + ')'
        },
        searchModele(input) {
            return axios.get('/modele?name=' + input).then(res => res.data)
        },
        selectCar(event) {
            if (event === undefined) return
            this.models.push(event);
            this.models[this.models.length - 1].compatible = true;
            this.getCompatible(event);
            this.$refs.autocompleteModele.value = '';
        },
        getCompatible: function (e) {
            var app = this;
            axios.get('modele/' + e.id + '/compatible').then(function (res) {
                res.data.data.forEach(function (car) {
                        car.compatible = false;
                        app.models.push(car);
                    }
                )
            })
        },

        selectPart(event) {
            let index = this.parts.findIndex(a => a === event.id);
            if (index < 0) {
                if (this.categories.includes(event.category_id)) {
                    this.categories.splice(this.categories.findIndex(a => a == event.category_id))
                    document.getElementById('c-' + event.category_id).checked = false;
                }
                this.parts.push(event.id)
            } else {
                this.parts.splice(index, 1);
            }
        },
        selectCategory(event, bool) {
            if (bool) {
                let index = this.categories.findIndex(a => a === event.id);
                if (index < 0) {
                    this.categories.push(event.id)
                    let category = this.allCategoriesWithParts.find(a => a.id == event.id);
                    let app = this;
                    category.parts.map(a => a.id).forEach(function (idPart) {
                        let i = app.parts.findIndex(a => a === idPart);
                        app.parts.splice(i, 1);
                        document.getElementById('p-' + idPart).checked = false;
                    });
                } else {
                    this.categories.splice(index, 1);
                }
            } else {
                let index = this.categoriesSelected.findIndex(a => a === event.id);
                if (index < 0) {
                    this.categoriesSelected.push(event.id)
                    this.categories.push(event.id)
                    setTimeout(function () {
                        document.getElementById('c-' + event.id).checked = true;
                    }, 50)

                } else {
                    this.categoriesSelected.splice(index, 1);
                    this.categories.splice(index, 1);
                    setTimeout(function () {
                        let el = document.getElementById('c-' + event.id)
                        if (el) {
                            el.checked = false;
                        }
                    }, 50)
                }
            }

        },
    },
    computed: {
        ...mapGetters('brands', ['allBrands']),
        ...mapGetters('alerts', ['allAlerts']),
        ...mapGetters('merchant', ['merchant']),
        ...mapGetters('categories', ['allCategoriesWithParts'])
    },
    created() {
        this.$store.dispatch('categories/fetchAllCategoriesWithParts')
            .catch(error => {
                console.log(error);
            })
    },

    watch: {},


}

</script>
