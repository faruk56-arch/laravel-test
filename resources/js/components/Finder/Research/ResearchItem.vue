<template>
    <router-link :to="{ name: 'researchProposalList', params: { id: research.id } }" tag="div" class="research-item bg-white shadow rounded px-4 py-3 text-blue cursor-p d-flex flex-column justify-content-between" :class="{active : research.status === 'in_progress'}">

        <div>
            <div class="d-flex align-items-center float-right delete">
                <i class="fal fa-trash-alt text-danger pt-1" @click.prevent="remove"></i>
            </div>

            <div class="d-flex align-items-center">
                <img v-if="research.modele.modele.img" :src="'/images/Cars/'+research.modele.modele.img+'.png'" class="little-car py-2 pr-3 pl-1" @error="research.modele.modele.img=null">
                <img v-else :src="'/images/Cars/emptycar.png'" class="little-car py-2 pr-3 pl-1">
                <span class="mb-0">
                    {{
                        research.modele.year ? research.modele.modele.brand.name + ' ' + research.modele.modele.name + ' (' + research.modele.year + ')' :
                            research.modele.modele.brand.name + ' ' + research.modele.modele.name + ' (' + research.modele.modele.year_start + '-' + research.modele.modele.year_end + ')'
                    }}
                </span>
            </div>

            <!-- <p class="mb-0" v-if="research.modele.car_name">{{research.modele.modele.brand.name+' '+research.modele.modele.name}} - {{research.modele.modele.year}}</p> -->

            <p class="lead font-weight-normal mb-2 mt-n1">
                {{ research.part && translation('name', research.part) || $t('pice_suggre') }}
            </p>

            <hr class="my-2">

        </div>

        <div>
            <div v-if="!research.status">
                <p class="mb-2">{{ $t('research_awaiting_validation') }}</p>
                <div class="progress mb-4">
                    <div class="progress-bar w-25" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div v-if="research.status === 'in_progress'">
                <p class="mb-2">{{ $t('nos_experts_sont_en_cours_de_recherche') }}</p>
                <div class="progress" :class="{'mb-4' : research.products.length == 0}">
                    <div class="progress-bar" :class="research.products.length!==0 ? 'w-75' : 'w-50' " role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div v-if="research.status === 'archived'">
                <p class="mb-2">{{ $t('la_recherche_a_t_archive') }}</p>
                <div class="progress mb-4">
                    <div class="progress-bar w-0" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div v-if="research.status === 'finished'">
                <p class="mb-2">{{ $t('recherche_termine') }}</p>
                <div class="progress mb-4">
                    <div class="progress-bar w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div v-if="research.status === 'in_progress'&&research.products.length!==0">
                <span class="btn btn-sm btn-full btn-center bg-gold mt-3">
                    {{ $tc('part_found', research.products.length, {"length": research.products.length}) }} !
                </span>
            </div>
        </div>
    </router-link>
</template>

<script>
import {mapActions} from 'vuex';

export default {
    name: 'ResearchItem',
    props: {
        research: {
            type: Object,
            default: null
        }
    },
    methods: {
        remove() {
            const h = this.$createElement;
            const body = [h('div', {class: ['position-relative', 'text-blue']}, [
                h('h3', {class: 'text-center'}, [
                    this.$t('sure_supprimer_research')
                ])
            ]), h('p', {class: 'h3'}, [
            ])];
            this.$removalConfirmation(body,null).then((removal) => removal ? this.removeResearch(this.research) : null)
        },
        ...mapActions('researches', ['removeResearch']),

    },
}
</script>
