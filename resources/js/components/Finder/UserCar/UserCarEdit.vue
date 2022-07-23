<template>
    <div class="user-car-edit">

        <div class="bg-white shadow container-fluid py-4 px-4 mb-4">

            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <a class="cursor-p pl-1 pr-3" @click="$router.go(-1)"><i class="fal fa-2x fa-arrow-left"></i></a>
                    <h1 v-if="$route.params.id!=0" class="h2 d-inline-block text-blue mb-0 pt-3">{{ $t('vehicle_parameters') }}</h1>
                    <h1 v-else class="h2 d-inline-block text-blue mb-0 pt-3">{{ $t('new_vehicle') }}</h1>
                </div>
                <top-nav></top-nav>
            </div>

        </div>

        <div class="container">

            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <h3 class="mb-3">
                        <span v-if="$route.params.id!=0">{{ $t('vehicle_parameters') }}</span>
                    </h3>



                    <div class="card p-4 mb-2">
                        <div class="form" id="select-car-model">

                            <vue-form v-if="$route.params.id!=0" :state="formstate" @submit.prevent="localUpdateUserModel">
                                <validate auto-label class="form-group" :class="fieldClassName(formstate.name)">
                                    <label class="text-blue">{{ $t('last_name') }}</label>
                                    <input type="text" :value="car_name" name="name" class="w-100" minlength="3" maxlength="25" @input="name = $event.target.value">
                                    <field-messages name="name" show="$touched || $submitted" class="form-control-feedback">
                                        <div>{{ $t('success') }}</div>
                                        <div slot="minlength">{{ $t('name_too_short') }}</div>
                                        <div slot="maxlength">{{ $t('name_too_long') }}</div>
                                    </field-messages>
                                </validate>
                                <div class="py-2">
                                    <button class="btn bg-blue" type="submit">{{ $t('update') }}</button>
                                </div>
                            </vue-form>

                            <vue-form v-else :state="formstate" @submit.prevent>
                                <label class="text-blue" v-if="$route.params.id==0">{{ $t('what_is_your_vehicle') }} <small class="text-danger"><sup>*</sup></small></label>
                                <autocomplete
                                    name="researchModele"
                                    id="researchModele"
                                    ref="autocomplete"
                                    :search="searchModele"
                                    :get-result-value="getResultValue"
                                    :debounce-time="1"
                                    @submit="setModele($event)"
                                    :placeholder="$t('brand_modeles_etc')"
                                    class="w-100"
                                    v-if="suggesting"
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
                                <div v-if="!suggesting" class="input-fake pl-1">
                                    <img v-if="img" class="align car-img mx-2" :src="'/images/Cars/tn/'+img+'.png'" @error="img=null">
                                    <img v-else class="align car-img mx-2" :src="'/images/Cars/tn/emptycar.png'">
                                    {{ modeleBrand+' - '+modeleName }}
                                    <span class="cursor-p ml-2 text-danger" @click="deletePart"><i class="fas fa-times-circle"></i></span>
                                </div>
                                <div class="row">
                                    <div class="form-sm mt-2 col-md-6">
                                        <label class="text-blue pt-2">{{ $t('year_vehicle') }} <small class="text-danger"><sup>*</sup></small></label>
                                        <select v-model="modeleYear" class="w-100 pr-3" :class="{'disabled' : !modeleYearStart}" :disabled="!modeleYearStart">
                                            <option v-if="!modeleYearStart" value="default">{{ $t('select_vehicle_first') }}</option>
                                            <option v-for="year in years">{{year}}</option>
                                        </select>
                                    </div>
                                    <div class="form-sm mt-2 col-md-6">
                                        <label class="text-blue pt-2">{{ $t('vehicle_version') }}
                                            <small>({{ $t('optional') }})</small></label>
                                        <input :disabled="!modeleYearStart" type="text" name="name" class="w-100" minlength="3" maxlength="25" :placeholder="$t('engine_bodywork_etc')" @input="version = $event.target.value">
                                    </div>
                                </div>
                                <div class="py-3">
                                    <button class="btn bg-blue btn-lg" type="submit" :disabled="!isCarSet" @click="submit">{{ $t('add') }}</button>
                                </div>
                            </vue-form>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</template>

<script>
import VueForm from 'vue-form';
import {mapActions, mapGetters} from 'vuex';

const TopNav = () => import(/* webpackChunkName: "group-dashboard" */"../../Shared/TopNav");

export default {
    name: 'UserCarEdit',
    components: {TopNav},
    mixins: [VueForm],
    data() {
        return {
            formstate: {},
            name: '',
            modeleId: null,
            modeleName: null,
            modeleBrand: null,
            modeleYearStart:null,
            modeleYearEnd:null,
            modeleYear:"default",
            years:[],
            suggesting: true,
            img:null,
            version:''
        }
    },
    methods: {
        ...mapActions('userModels', ['updateUserModel', 'newUserModel']),
        searchModele(input) {
            return axios.get('/modele?name=' + input).then(res => res.data)
        },
        getResultValue(result) {
            return result.brand.name + ' - ' + result.name
        },
        fieldClassName: function (field) {
            if (!field) {
                return '';
            }
            if ((field.$touched || field.$submitted) && field.$valid) {
                return 'has-success';
            }
            if ((field.$touched || field.$submitted) && field.$invalid) {
                return 'has-danger';
            }
        },
        setModele($event) {
            this.modeleId = $event.id;
            this.modeleName = $event.name
            this.modeleBrand = $event.brand.name
            this.modeleYearStart = $event.year_start
            this.modeleYearEnd = $event.year_end
            this.suggesting = false
            this.img = $event.img
            this.years = [];
            for ( let i = this.modeleYearStart; i <= this.modeleYearEnd;i++){
                this.years.push(i);
            }
        },
        deletePart() {
            this.modeleName = null
            this.modeleBrand = null
            this.modeleYear = "default"
            this.modeleId = null
            this.suggesting = true
            this.img = ''
            this.years = [];
            this.modeleYearStart=null
        },
        submit() {
            if (this.isCarSet) {
                this.localUpdateUserModel();
            }
        },
        async localUpdateUserModel() {
            if (this.$route.params.id != 0) await this.updateUserModel([this.$route.params.id, this.name,this.modeleYear,this.version])
            else await this.newUserModel([this.modeleId, this.name,this.modeleYear,this.version])
            this.$router.push({name: 'carList'}).catch(() => {});
        },
    },
    created() {
        if (this.$route.params.id == 0) {
            this.$store.dispatch('cars/fetchAllCars')
                .catch(error => {
                    console.log(error);
                })

        }
    },
    computed: {
        ...mapGetters('userModels', ['getCarName']),
        ...mapGetters('cars', ['allCars']),
        car_name() {
            return this.getCarName(this.$route.params.id);
        },
        isCarSet() {
            return this.modeleName !== null && this.modeleId !== null &&this.modeleYear!=='default'
        }
    }
}</script>
