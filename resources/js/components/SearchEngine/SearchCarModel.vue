<template>
    <div class="search-car-model">

        <a class="basic text-bold-dark-grey d-inline-block mb-2 pointer cursor-p" @click="$router.go(-1)">
            <i class="fal fa-long-arrow-left"></i>
            {{ $t('back') }}</a>

        <!-- <div class="form form-xl" v-if="$auth.check()&&allUserModels.length>0">
            <h3 class="text-blue pb-0 pt-2">Marque et modèle</h3>
        </div> -->

        <div class="row">

            <div class="col-12" v-if="$auth.check()&&allUserModels.length>0">
                <search-car-model-field v-if="isUserModelLoaded" :disabled="checkLength" :custom="data.car.custom"
                                        :value="''+data.car.id" @selected="selectCar($event,false)"
                                        :label="$auth.check() ? $t('select_from_own') : ''"
                                        store="UserModels" :boolNew.sync="boolNew"></search-car-model-field>
                <span v-else-if="$auth.check()" class="loader"></span>
            </div>

            <div class="col-12" v-if="!$auth.check()||boolNew||allUserModels.length==0">

                <div class="form form-xl" id="select-car-model">
                    <label class="text-blue search-form " :class="{'pt-3' : !$auth.check()}">
                        <span v-if="$auth.check()">{{ $t('find_new_vehicle') }}</span>
                        <span v-else>{{ $t('what_is_vehicle') }} <sup class="text-danger">*</sup></span>
                    </label>
                    <autocomplete
                        name="researchModele"
                        :search="searchModele"
                        :get-result-value="getResultValue"
                        :debounce-time="1"
                        ref="autocomplete"
                        @submit="selectCar($event,true)"
                        :placeholder="$t('brand_modele_etc')"
                        class="w-100"
                        id="researchModele"
                        v-if="!bool"
                    >
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
                    <div v-if="bool" class="input-fake" style="height:60px;padding-left:0px !important">
                        <img v-if="data.car.img" class="align car-img mx-2"
                             :src="'/images/Cars/tn/'+data.car.img+'.png'" style="width:30%" @error="data.car.img=null">
                        <img v-else class="align car-img mx-2" :src="'/images/Cars/tn/emptycar.png'" style="width:30%">
                        {{
                            data.car.brand + ' - ' + data.car.name + ' (' + data.car.year_start + '-' + data.car.year_end + ')'
                        }}
                        <span class="cursor-p ml-2 text-danger" @click="deletePart"><i class="fas fa-times-circle"></i></span>
                    </div>
                </div>
                <div class="mt-3">
                    <span v-b-modal.modal-not-found class="basic-link text-blue">{{ $t('cant_find_vehicle') }}</span>
                </div>
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
                    <b-form-group
                        id="input-group-1"
                        label-class="required"
                        :label="$t('your_email')"
                        label-for="input-1"
                        :description="$t('keep_track_demand')"
                        v-if="!this.$auth.check()"
                    >
                        <b-form-input
                            id="input-1"
                            v-model="contact.email"
                            type="email"
                            required
                            placeholder="john@doe.com"
                        ></b-form-input>
                    </b-form-group>
                    <p class="text-secondary"><span class="text-danger">*</span> {{ $t('required_informations') }}</p>
                </b-form>

                <template v-else>
                    <p class="lead text-center text-blue mb-1">{{ $t('thanks_demand') }}</p>
                    <div class="text-blue py-2">{{ $t('email_contact_back') }}</div>
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

        </div>
        <!-- END NOUVEAU SYSTÈME DE RECHERCHE -->
        <router-link dusk="modele-next" tag="a" :class="!data.car.id?'disabled':''" class="btn bg-blue btn-lg mt-3"
                     :to="{ name: 'searchCarPartsCategories'}">
            {{ $t('next_step') }}
        </router-link>
    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";

const SearchCarModelField = () => import("./SearchCarModelField");


export default {
    components: {SearchCarModelField},
    props: ['data'],
    data() {
        return {
            carSelected: 0,
            car: null,
            value: '',
            bool: false,
            boolNew: false,
            success: false,
            contact: {
                email: null,
                car: null,
            }
        }
    },
    mounted() {
        if (this.data.car.custom == '0' && this.data.car.id != 0) {
            this.bool = true;
            this.car = this.data.car
            this.carSelected = this.data.car.id;
            this.value = 'custom';
        }
    },
    methods: {
        ...mapActions('messages', ['newMessage', 'newMessageNotAuth']),
        handleOk(bvModalEvt) {
            bvModalEvt.preventDefault()
            if (this.$auth.check()) {
                this.newMessage({
                    template_id: 5,
                    params: [this.contact.car],
                    type: 'researches'
                }).then(res => {
                    this.success = true;
                });
            } else {
                this.newMessageNotAuth({
                    template_id: 5,
                    params: [this.contact.car],
                    type: 'researches',
                    email: this.contact.email
                }).then(res => {
                    this.success = true;
                });
            }
        },
        resetModal() {
            this.contact = {
                email: null,
                car: null
            }
        },
        selectCar(event, bool) {
            if (event == null) this.deletePart()
            else {
                this.bool = bool;
                this.carSelected = event.id;
                this.car = event
                this.$emit('data', this.car);
            }

        },
        searchModele(input) {
            this.value = input;
            return axios.get('/modele?name=' + input).then(res => res.data)
        },
        getResultValue(result) {
            return result.brand.name + ' - ' + result.name + ' (' + result.year_start + '-' + result.year_end + ')'
        },
        deletePart() {
            this.bool = false;
            this.carSelected = null;
            this.value = ''
            this.car = null
            this.$emit('data', this.car);
        },
    },
    computed: {
        ...mapGetters('cars', ['allCars']),
        ...mapGetters('userModels', ['allUserModels', 'isUserModelLoaded']),
        checkLength() {
            return this.value.length > 0;
        }
    }
}

</script>

