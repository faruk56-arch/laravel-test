<template>
    <div class="user-car-detail">

        <div class="bg-white shadow container-fluid py-md-4 py-3 px-4 mb-4">

            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <a class="cursor-p pl-1 pr-3" @click="$router.go(-1)"><i class="fal fa-2x fa-arrow-left"></i></a>
                    <h1 class="h2 d-inline-block text-blue mb-0 pt-md-3 pt-2">{{ $t('my_vehicles') }}</h1>
                </div>
                <top-nav></top-nav>
            </div>

        </div>

        <div v-if="car" class="container-fluid position-relative text-blue h-100 pt-5">

            <div class="row pl-3 w-max-900">

                <div class="col-12 col-md-6 order-3 order-md-2" v-if="car">
                    <h3 class="pb-3">{{ $t('vehicle_details') }}</h3>
<!--                    <div class="row">-->
<!--                        <div class="col">-->
<!--                            <p class="lead mb-1">Surnom</p>-->
<!--                        </div>-->
<!--                        <div class="col">-->
<!--                            <click-to-edit @input="updateUserModel([car.id, $event]); car.car_name = $event" class="mb-1" can-edit placeholder="Ajouter un surnom" :value="car.car_name || ''"/>-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="row">
                        <div class="col">
                            <p class="lead mb-1">{{ $t('brand_modele_etc') }}</p>
                        </div>
                        <div class="col">
                            <p class="mb-1">{{car.modele.brand.name+" "+car.modele.name}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="lead mb-1">{{ $t('version') }}</p>
                        </div>
                        <div class="col">
                            <click-to-edit @input="updateUserModel([car.id, null,null,car.year,$event]); car.version = $event" class="mb-1" can-edit :placeholder="$t('add_version')" :value="car.version || $t('non_renseign')"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="lead mb-1">{{ $t('year') }}</p>
                        </div>
                        <div class="col" :key="refresh">
                            <p class="mb-1">
                                <select :value="car.year" @change="updateUserModel([car.id,null,null,$event.target.value,car.version]); car.year = $event.target.value">
                                    <option value="">{{car.modele.year_start}}-{{car.modele.year_end}}</option>
                                    <option v-for="year in years">{{year}}</option>
                                </select>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 order-2 order-md-3">
                    <img v-if="car.modele.img" :src="'/images/Cars/'+car.modele.img+'.png'" class="w-100" @error="car.modele.img=null">
                    <img v-else :src="'/images/Cars/emptycar.png'" class="w-100">
                </div>
            </div>

            <div class="row pl-3 w-max-900 pt-2" v-if="car.researches_count !== 0">
                <div class="col-6">
                    <h3 class="pb-3">{{ $t('associated_researches') }}</h3>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12" v-for="research in car.researches">
                            <!-- Si trouvaille il y a, ajouter la class 'research-active' tu devras -->
                            <router-link :to="{name: 'researchProposalList', params: {id: research.id}}" :class="{'research-active': research.products.length > 0}" class="item bg-white py-3 px-4 shadow mb-3 rounded d-flex justify-content-between position-relative overflow-hidden">
                                <p class="mb-0 lead py-3 font-weight-normal">{{ research.part && translation('name', research.part) || $t('produit_suggr') }}</p>
                                <!-- Si trouvaille il y a ! -->
                                <div class="d-flex align-items-center" v-if="research.products.length > 0">
                                    <a href="" v-if="research.products.length>1" class="btn bg-gold d-block btn-sm">{{ $t('see_x_findings', {"length": research.products.length}) }}</a>
                                    <a href="" v-else class="btn bg-gold d-block btn-sm">{{ $t('see_my_finding') }}</a>
                                </div>

                                <!-- Si validation il y a ! -->
                                <div class=" d-flex justify-content-center flex-column" v-else>

                                    <p class="mb-2" v-if="!research.status">
                                        {{ $t('research_awaiting_validation') }}
                                    </p>
                                    <p class="mb-2" v-else>
                                        {{ $t('experts_are_looking') }}
                                    </p>

                                    <div class="progress w-100">
                                        <div class="progress-bar" :class="!research.status ? 'w-25' : 'w-50'" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </router-link>

                        </div>
                    </div>
                </div>
            </div>
            <div class="w-max-900 pl-3 pt-2" v-if="car">
                <new-research :select="false" :car="car"/>
            </div>
        </div>
        <div v-else><span class="loader"></span></div>
        <!-- {{ getCarName($route.params.id) }} -->

    </div>

</template>

<script>
    import {mapActions, mapGetters} from "vuex";
    import ClickToEdit from "../../Shared/ClickToEdit";

    const NewResearch = () => import(/* webpackChunkName: "group-dashboard" */"../Dashboard/NewResearch");

    const TopNav = () => import(/* webpackChunkName: "group-dashboard" */"../../Shared/TopNav");

    export default {
        name: "UserCarDetail",
        components: {ClickToEdit, NewResearch, TopNav},
        data() {
            return {
                car: null,
                years:[],
                refresh:0,
            }
        },
        async created() {
            await this.fetchAllUserModels();
            await this.fetchUserModele(this.$route.params.id).then(res => {
                this.car = res.data
                setTimeout(() =>{
                    this.refresh+=1;
                },20)
            });
            for ( let i = this.car.modele.year_start; i <= this.car.modele.year_end;i++){
                this.years.push(i);
            }
        },
        methods: mapActions('userModels', ['updateUserModel', 'fetchUserModele', 'fetchAllUserModels']),
        computed: mapGetters('userModels', ['getCarName'])
    }
</script>

<style scoped>

</style>
