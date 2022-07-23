<template>
    <div class="user-car-item bg-white shadow rounded p-4 text-blue">
        <div class="delete pr-4">
            <span v-b-modal="'modal-center'+car.id" v-if="car.researches_count">
                <i class="fal fa-trash-alt edit text-danger"></i>
            </span>
            <span @click="removal(car)" v-else class="cursor-p">
                <i class="fal fa-trash-alt edit text-danger"></i>
            </span>
        </div>
        <div class="row">

            <div class="col-md-7 pr-0">
                <strong class="h3" @click="showSearch = !showSearch">
                    <span>{{
                            car.year ? car.modele.brand.name + ' ' + car.modele.name + ' (' + car.year + ')' :
                                car.modele.brand.name + ' ' + car.modele.name + ' (' + car.modele.year_start + '-' + car.modele.year_end + ')'
                        }}</span>
                </strong>
                <p class="mb-0">{{ car.version ? car.version : $t('version_non_renseigne') }}</p>
                <ul class="list-unstyled mb-0 mt-3">
                    <li class="pb-2 pb-md-3">
                        <i class="far fa-search pr-1"></i>
                        <span><strong>{{ car.researches_count ? car.researches_count : '0' }}</strong> Produits(s) recherché(s)</span>
                    </li>
                    <!--                    <li class="pb-2 pb-md-3">-->
                    <!--                        <i class="far fa-store pr-2"></i>-->
                    <!--                        <span><strong>0</strong> Pièces en vente</span>-->
                    <!--                    </li>-->
                </ul>
            </div>
            <div class="col-md-5 px-0 pr-3">
                <img v-if="car.modele.img" @click="showSearch = !showSearch" :src="'/images/Cars/'+car.modele.img+'.png'" class="w-100 img-fluid py-2 px-4" @error="car.modele.img=null">
                <img v-else @click="showSearch = !showSearch" :src="'/images/Cars/emptycar.png'" class="w-100 img-fluid py-2 px-4">
            </div>

        </div>

        <!--         <div class="row">
                    <div class="col pr-0 d-flex align-items-center">
                        <ul class="list-unstyled mb-0">
                            <li class="pb-2 pb-md-3">
                                <i class="far fa-search pr-2"></i>
                                <span><strong>{{car.researches_count}}</strong> Pièce(s) recherchée(s)</span>
                            </li>
                                <li class="pb-2 pb-md-3">
                                <i class="far fa-store pr-2"></i>
                                <span><strong>0</strong> Pièces en vente</span>
                            </li>
                        </ul>
                    </div>

                </div> -->

        <div class="row">
            <div class="col-md-6 pr-1">
                <router-link class="btn btn-sm btn-full btn-center bg-gold" :to="{ name: 'searchCarPartsCategories', params:{idCarPerso:car.id } }">{{ $t('new_research') }}</router-link>
            </div>

            <div class="col-md-6 pl-1">
                <router-link class="btn btn-sm btn-full btn-center bg-grey" :to="{'name': 'carDetail', params: {id: car.id}}">{{ $t('see_vehicle') }}</router-link>
            </div>
        </div>

        <!--         <router-link :to="{ name: 'editUserCar', params: { id: car.id }}">-->
        <!--            <i class="fal fa-pencil-alt edit"></i>-->
        <!--        </router-link>-->


        <!--
                <i v-if="!showSearch&&car.researches.length>0" class="fal fa-chevron-down collapse cursor-p" @click="showSearch = true"></i>
                <i v-if="showSearch&&car.researches.length>0" class="fal fa-chevron-up collapse cursor-p" @click="showSearch = false"></i> -->

        <!-- <ul v-if="showSearch" class="research-list" >
            <li v-for="research in car.researches">
                <router-link tag="span" class="text-blue" :to="{name: 'researchProposalList', params:{id: research.id}}">
                     {{ research.part.translation }}
                 </router-link>
            </li>
        </ul> -->

    </div>
</template>

<script>
import {mapActions} from 'vuex';

export default {
    name: 'UserCarItem',
    props: {
        car: {
            type: Object,
            default: {}
        }
    },
    data() {
        return {
            researches: null,
            loading: true,
            errored: false,
            showSearch: false
        }
    },
    methods: {
        removal(car) {
            const h = this.$createElement;
            const body = [h('div', {class: ['position-relative', 'text-blue']}, [
                h('i', {class: 'fal fa-car fa-5x pb-3'}),
                h('h3', {class: 'text-center'}, [
                    this.$t('sure_supprimer_vehicule')
                ])
            ]), h('p', {class: 'h3'}, [
            ])];
            this.$removalConfirmation(body,null).then(removal => removal ? this.removeUserModel(car) : null)
        },
        ...mapActions('userModels', ['removeUserModel'])
    }
}
</script>
