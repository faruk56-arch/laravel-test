<template>
    <div class="search-engine">

        <div class="search-engine-header py-4" style="background-image: url('/images/bg_header_search_engine_classic_parts_finder.png')">
            <div class="container">
                <div class="row">
                    <div class="col-4 col-md-2 py-2 px-md-3">

                        <a v-if="!$auth.check()" href="https://classic-parts-finder.com" class="d-block">
                            <img class="logo-gold w-75" :src="'/images/logo/logo_classic_parts_finder_gold.svg'">
                        </a>
                        <router-link v-else :to="{ name: 'dashboard' }" class="d-block">
                            <img class="logo-gold w-75" :src="'/images/logo/logo_classic_parts_finder_gold.svg'">
                        </router-link>

                    </div>
                </div>
            </div>
        </div>

        <div class="search-engine-content container">

            <div class="row">
                <div class="col-md-3 px-md-0 order-md-1 order-2 mb-5 mb-md-0">
                    <div class="rounded shadow bg-white overflow-hidden research-resume d-none d-md-block">
                        <div class="text-center lead bg-blue text-white py-3">{{ $t('your_research') }}</div>
                        <ul class="list-unstyled">
                            <li class="px-3 py-2" :class="{'active':$route.name==='searchCarModel'}">
                                <router-link :to="{ name: 'searchCarModel' }">
                                    <p class="mb-0">{{ $t('brand_modele') }}</p>
                                    <div v-if="researchData.car.brand" class="d-flex align-items-center py-2">
                                        <img v-if="researchData.car.img" :src="'/images/Cars/tn/'+researchData.car.img+'.png'" class="py-2" @error="researchData.car.img=null">
                                        <img v-else :src="'/images/Cars/tn/emptycar.png'" class="py-2">
                                        <span class="d-block pl-3">
                                            {{researchData.car.brand}}<br>
                                            {{researchData.car.year?researchData.car.name+' ('+researchData.car.year+')':researchData.car.name +' ('+researchData.car.year_start+'-'+researchData.car.year_end+')'}}
                                        </span>
                                    </div>
                                </router-link>
                            </li>
                            <li class="px-3 py-2" :class="{'disabled':!checkVisited('searchCarPartsCategories'),'active':$route.name==='searchCarPartsCategories'}">
                                <router-link :to="{ name: 'searchCarPartsCategories' }">
                                    <p class="mb-0">{{ $t('part_category') }}</p>
                                    <div v-if="researchData.category" class="d-flex align-items-center">
                                        <span class="d-block">
                                            {{ translation('name', researchData.category) }}
                                        </span>
                                    </div>
                                </router-link>
                            </li>
                            <li class="px-3 py-2" :class="{'disabled':!checkVisited('searchCarParts'),'active':$route.name==='searchCarParts'}">
                                <router-link :to="{ name: 'searchCarParts' }">
                                    <p class="mb-0">{{ $t('part_research') }}</p>
                                    <div v-if="researchData.part" class="d-flex align-items-center">
                                        <span class="d-block">
                                            {{researchData.part.translation}}
                                        </span>
                                    </div>
                                </router-link>
                            </li>
                            <li class="px-3 py-2" :class="{'disabled':!checkVisited('SearchDetails'),'active':$route.name==='SearchDetails'}">
                                <router-link :to="{ name: 'SearchDetails' }">
                                    <p class="mb-0">{{ $t('complementary_info') }}</p>
                                </router-link>
                            </li>
                            <li class="px-3 py-2" :class="{'disabled':(researchData.part===''||researchData.category===''||researchData.car.brand==='')||!checkVisited('createAccount'),'active':$route.name==='createAccount'}" v-if="!$auth.check()">
                                <router-link :to="{ name: 'createAccount', params:{idCar:researchData.car.id,custom:researchData.car.custom,idCategory: researchData.category.id, idPart:researchData.part.id}, query: {message: $route.query.message}}">
                                <p class="mb-0">{{ $t('registration') }}</p>
                                </router-link>
                            </li>

                        </ul>
                    </div>
                </div>

                <div class="col-md-9 pl-md-5 order-md-2 order-1 mb-4 mb-md-0">
                    <router-view @details="onDetails" @reference="onReference" @types="onTypes" @images="onImages" @data="onData" @year="onYear" @version="onVersion" :data="researchData"></router-view>
                </div>

            </div>

        </div>

    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        data() {
            return {
                researchData:{
                    car:{
                        brand: "",
                        name: "",
                        year_start: "",
                        year_end: "",
                        id:0,
                        custom:'0',
                        img:'',
                        year:'',
                        version:'',
                    },
                    category:'',
                    part:'',
                    reference:null,
                    details:null,
                    images:[],
                    year:null,
                    version:null,
                    types:[17,18,19,20]
                },
                visited:[],
            }
        },
        async created() {
            let app = this;
            if (this.$route.name !== "searchCarModel"&&!this.$route.params.idCarPerso&&!this.$route.params.idCar&&this.researchData.car.id===0) {
                this.$router.push({name:'searchCarModel'}).catch(() => {});
            }
            this.$store.dispatch('categories/fetchAllCategories');
            if (this.$auth.user())this.$store.dispatch('userModels/fetchAllUserModels');
            if (this.$route.params.idCarPerso){
                await axios.get('user/'+this.$auth.user().id+'/userModel/'+this.$route.params.idCarPerso).then(res=>{
                    let car = res.data;
                    app.researchData.car.brand = car.modele.brand.name;
                    app.researchData.car.name = car.modele.name;
                    app.researchData.car.year_start = car.modele.year_start;
                    app.researchData.car.year_end = car.modele.year_end;
                    app.researchData.car.id = car.modele_id;
                    app.researchData.car.img = car.modele.img;
                    app.researchData.car.custom = ''+car.id;
                    app.researchData.car.year = car.year;
                    app.researchData.year = car.year;
                    app.researchData.car.version = car.version;
                    app.researchData.version = car.version;
                    app.visited.push('searchCarModel')
                    app.visited.push('searchCarPartsCategories')
                }).catch(error => {
                    console.log(error);
                    this.errored = true;
                });
            }
            else if (this.$route.params.idCar){
                await axios.get('/modele/'+this.$route.params.idCar).then(res=>{
                    let car = res.data;
                    app.researchData.car.brand = car.brand.name;
                    app.researchData.car.name = car.name;
                    app.researchData.car.img = car.img;
                    app.researchData.car.year_start = car.year_start;
                    app.researchData.car.year_end = car.year_end;
                    app.researchData.car.id = car.id;
                    app.researchData.car.custom = '0';
                    app.researchData.year = '';
                    app.visited.push('searchCarModel')
                    app.visited.push('searchCarPartsCategories')
                }).catch(error => {
                    console.log(error);
                    this.errored = true;
                });
            }if (this.$route.params.idCategory){
                await axios.get('/category/'+this.$route.params.idCategory).then(res=>{
                    let category = res.data;
                    app.researchData.category=category;
                    app.visited.push('searchCarPartsCategories')

                }).catch(error => {
                    console.log(error);
                    this.errored = true;
                });
            }if (this.$route.params.idPart){
                await axios.get('/part/'+this.$route.params.idPart).then(res=>{
                    let part = res.data;
                    app.researchData.part=part;
                    app.visited.push('searchCarParts')
                    app.visited.push('SearchDetails')
                }).catch(error => {
                    console.log(error);
                    this.errored = true;
                });
            }if (this.$route.name=='SearchDetails'){
                await axios.get('/part/'+this.$route.params.idPart).then(res=>{
                    let part = res.data;
                    app.researchData.part=part;
                    app.visited.push('createAccount')
                }).catch(error => {
                    console.log(error);
                    this.errored = true;
                });
            }
        },
        methods: {
            onData(data) {
                if (data&& data.modele){
                    this.researchData.car.brand = data.modele.brand.name;
                    this.researchData.car.name = data.modele.name;
                    this.researchData.car.year_start = data.modele.year_start;
                    this.researchData.car.year_end = data.modele.year_end;
                    this.researchData.car.id = data.modele.id;
                    this.researchData.car.img = data.modele.img;
                    this.researchData.car.custom = ''+data.id;
                    this.researchData.car.year = data.year;
                    this.researchData.year = data.year;
                    this.researchData.car.version = data.version;
                    this.researchData.version = data.version;

                }
                else if (data&& data.brand){
                    this.researchData.car.brand = data.brand.name;
                    this.researchData.car.name = data.name;
                    this.researchData.car.year_start = data.year_start;
                    this.researchData.car.year_end = data.year_end;
                    this.researchData.car.year = '';
                    this.researchData.car.img = data.img;
                    this.researchData.car.id = data.id;
                    this.researchData.car.custom = '0';
                }
                else if(data&& data.hover){
                    this.researchData.category = data;
                }
                else if(data){
                    this.researchData.part = data;
                }else{
                    this.researchData.car = {
                        brand: "",
                        name: "",
                        year_start: "",
                        year_end: "",
                        id:0,
                        custom:'0',
                        img:''
                    }
                }
            },
            onDetails(data){
                this.researchData.details = data;
            },
            onYear(data){
                this.researchData.year = data;
            },
            onVersion(data){
                this.researchData.version = data;
            },onReference(data){
                this.researchData.reference = data;

            },onTypes(data){
                this.researchData.types = data;

            },onImages(data){
                this.researchData.images = data;
            },
            checkVisited(name){
                return this.visited.includes(name)
            }
        },
        computed: {
            progressBar() {
                if (this.$route.name == "searchCarModel") {
                    if (Vue.auth.check()) {
                        return "33%";
                    } else {
                        return "25%";
                    }
                }
                if (this.$route.name == "searchCarPartsCategories") {
                    if (Vue.auth.check()) {
                        return "66%";
                    } else {
                        return "50%";
                    }
                }
                if (this.$route.name == "searchCarParts") {
                    if (Vue.auth.check()) {
                        return "100%";
                    } else {
                        return "75%";
                    }
                }
                if (this.$route.name == "createAccount") {
                    return "100%";
                }
            },
            progressBarEnd() {
                if (this.$route.name == "searchCarParts") {
                    if (Vue.auth.check()) {
                        return "active";
                    }
                }
                if (this.$route.name == "createAccount") {
                    return "active";
                }
            },
            nbStep(){
                if (Vue.auth.check()){return 3;}else{return 4;}
            },
            ...mapGetters('cars', ['allCars']),
            category() {
                return this.researchData.category;
            },
            car() {
                return this.researchData.car;
            },

        },
        watch:{
            category:{
                deep: true,
                handler() {
                    this.researchData.part='';
                }
            },
            car:{
                deep: true,
                handler() {
                    this.researchData.part='';
                }
            },
            $route(){
                window.scroll(0,0);
                this.visited.push(this.$route.name)
            }
        }

    }
</script>
