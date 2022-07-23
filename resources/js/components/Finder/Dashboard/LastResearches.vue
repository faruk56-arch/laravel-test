<template>
    <div class="col-xl-6 pt-md-3 pt-lg-0">
        <div class="row" >
            <div class="col-6 col-md-6 text-blue pb-4 px-2 px-md-3">
                <router-link :to="{ name: 'researchList' }" class="bg-white shadow rounded d-block dashboard-section-link text-center py-lg-5 py-4">
                    <i class="fal fa-search fa-5x text-gold"></i>
                    <p class="lead font-weight-normal text-center mb-0 mt-3">{{ $t('my_researches') }}</p>
                </router-link>
            </div>

            <div class="col-6 col-md-6 text-blue pb-4 px-2 px-md-3">
                <router-link :to="{ name: 'carList' }" class="bg-white shadow rounded d-block dashboard-section-link text-center py-lg-5 py-4">
                    <i class="fal fa-car fa-5x text-gold"></i>
                    <p class="lead font-weight-normal text-center mb-0 mt-3">{{ $t('my_vehicles') }}</p>
                </router-link>
            </div>

            <div class="col-12 text-blue pb-4 pb-md-0 d-none d-sm-block">
                <div class="bg-white shadow rounded p-4">
                    <p class="lead font-weight-normal">
                        <i class="fal fa-search pr-2"></i>
                        {{ $t('my_last_researches') }}
                    </p>
                    <hr>
                    <table class="table table-striped py-3 last-research" v-if="isResearchLoaded">
                        <tbody>
                        <tr v-if="getLatestResearches == 0">
                            <td class="border-0" scope="row">
                                                    <span class="py-1 pb-2 d-block">
                                                        {{ $t('no_researches_for_now') }}
                                                    </span>
                            </td>
                        </tr>
                        <tr v-for="research in getLatestResearches">
                            <th class="border-0" scope="row">
                                <img v-if="research.modele.modele.img" :src="'/images/Cars/'+research.modele.modele.img+'.png'" class="car py-1 pr-2" @error="research.modele.modele.img=null">
                                <img v-else :src="'/images/Cars/emptycar.png'" class="car py-1 pr-2">
                                <span>
                                    {{research.modele.year?research.modele.modele.brand.name +' '+ research.modele.modele.name+' ('+research.modele.year+')':
                                    research.modele.modele.brand.name +' '+ research.modele.modele.name+' ('+research.modele.modele.year_start+'-'+research.modele.modele.year_end+')'}}
                                </span>
                            </th>
                            <td class="border-0 ">
                                {{ research.part ? translation('name', research.part) : $t('pice_suggre') }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                        <div v-else>
                            <span dusk="research-loading" class="loader"></span>
                        </div>
                    <router-link :to="{ name: 'researchList' }" class="btn bg-blue btn-sm btn-center mt-1">
                        {{ $t('all_my_researches') }}
                    </router-link>
                </div>
            </div>
        </div>
        <div class="not-activated rounded" v-if="isResearchLoaded&&total===0&&isUserModelLoaded&&allUserModels.length===0">
            <p class="h3 text-center px-3">{{ $t('no_research_made') }}</p>
            <router-link tag="a" :to="{ name: 'searchCarModel' }" class="btn bg-gold btn-sm mt-2">{{ $t('new_research') }}</router-link>
        </div>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from "vuex";

    export default {
        name: "LastResearches",
        created(){
            this.fetchAllResearches();
            this.fetchAllUserModels();
        },
        methods:{
            ...mapActions('researches', ['fetchAllResearches']),
            ...mapActions('userModels', ['newUserModel','fetchAllUserModels'])
        },
        computed: {
            ...mapGetters('researches', ['getLatestResearches', 'allResearches', 'total', 'done','isResearchLoaded']),
            ...mapGetters('userModels', ['allUserModels','isUserModelLoaded']),

        }
    }
</script>

<style scoped>

</style>
