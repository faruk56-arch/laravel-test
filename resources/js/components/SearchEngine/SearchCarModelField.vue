<template>
    <div class="form form-xl mb-3 mb-md-0 search-car-model-field" >
        <label class="text-blue search-form pt-3">
            <span>{{ label }} <sup class="text-danger">*</sup></span>
        </label>
        <!-- <select :disabled="disabled" v-model="selected" class="w-100" id="selectCar" :key="key">
            <option value="0" disabled>
                Choisir une voiture
            </option>
            <option v-for="car in allUserModels" :value="car.id">
                    {{ car.car_name ? car.car_name+' ('+car.modele.brand.name+' '+car.modele.name+')' : car.modele.brand.name+' '+car.modele.name }}
            </option>
        </select> -->
        <div class="row" :key="key">
            <div class="col-4" v-for="car in allUserModels" :key="car.id">

                <div class="card p-3 mb-3 text-center d-flex align-items-center flex-column justify-content-center cursor-p card-outline" :class="car.id==custom?'active':''" @click="personalCar(car.id)">

                    <img v-if="!car.modele.img" class="w-100 little-car px-1" :src="'/images/Cars/tn/emptycar.png'">

                    <img v-else :src="'/images/Cars/'+car.modele.img+'.png'" class="w-100 little-car px-1">

                    <div class="mt-2">
                        <b>{{ car.car_name ? car.car_name+' ('+car.modele.brand.name+' '+car.modele.name+')' : car.modele.brand.name+' '+car.modele.name }}</b>
                    </div>

                    <i class="fas fa-check-circle badge-icon text-gold"></i>

                </div>

            </div>
            <div class="col-4">

                <div class="card p-3 py-4 mb-3 text-center d-flex align-items-center flex-column justify-content-center cursor-p dots" @click="customCar">

                    <i class="fal fa-plus fa-2x"></i>

                    <div class="mt-2">
                        <b>{{ $t('add_vehicle') }}</b>
                    </div>

                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: 'SearchCarModelField.vue',
        props: {
            store: {
                type: String,
                required: true,
                default: ''
            },
            label: {
                type: String,
                required: true,
                default: ''
            },
            value: {
                type: String,
                required: true,
                default: "0"
            },
            custom: {
                type: String,
                required: true,
                default: '0'
            },disabled: {
                type: Boolean,
                required: true,
                default: false
            },
            boolNew:{
                type:Boolean
            }
        },
        data() {
            return {
                selected: "0",
                key:0,
                initial:false,
            }
        },
        created() {
            if (this.value&&this.custom!=="0")this.selected= parseInt(this.custom);
            if (this.store === 'Cars') {
                this.$store.dispatch('cars/fetchAllCars').catch(error => {
                    console.log(error);
                    this.errored = true;
                }).finally(() => this.loading = false);
            } else {
                this.$store.dispatch('userModels/fetchAllUserModels').catch(error => {
                    console.log(error);
                    this.errored = true;
                }).finally(() => this.loading = false);
            }
        },
        mounted(){
            this.key++;
        },
        computed: {
            ...mapGetters('cars', ['allCars']),
            ...mapGetters('userModels', ['allUserModels','isUserModelLoaded']),
            all() {
                return this['all' + this.store]
            },
        },
        methods:{
            customCar(){
                this.$emit('update:boolNew',true)
                this.selected=null;
            } ,
            personalCar(e){
                this.$emit('update:boolNew',false)
                this.selected=e;
            }
        },
        watch: {
            custom(){
                if (!this.initial){
                    this.initial = true;
                    if (this.value)this.selected= parseInt(this.custom);
                }
            },
            selected() {
                if(this.selected!=null&&this.selected!='0')this.$emit('selected', this.allUserModels.find(a=>a.id ==this.selected))
                else if(this.custom!='0') this.$emit('selected',null);
            },
            value(){
                if (this.value&&this.custom=='0')this.selected= "0";
            }
        }
    };
</script>

<style scoped>

</style>
