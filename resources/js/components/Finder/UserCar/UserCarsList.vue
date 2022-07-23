<template>
    <div class="user-car-list">

        <div class="bg-white shadow container-fluid py-lg-4 py-3 px-4 mb-4">

            <div class="d-flex align-items-center justify-content-between">
                <h1 class="h2 text-blue mb-0 pt-lg-3 pt-2">{{ $t('my_vehicles') }}</h1>
                <top-nav></top-nav>
            </div>

        </div>

        <div class="container-fluid">

            <!--                                        <div class="col-12 col-md-6 col-xl-4 col-xxl-3 pb-4"  v-for="car in allUserModels">-->
            <!--                                            <user-car-item :car="car"></user-car-item>-->
            <!--                                        </div>-->

            <div class="table-wrapper" :class="$auth.user().vehicles_view || 'table-grid'" v-if="isUserModelLoaded">

                <div class="filters pb-4 pt-2">
                    <div class="research w-50">
                        <i class="fal fa-search"></i>
                        <input type="text" class="form-control w-100" :placeholder="$t('find_by_brand_year_etc')" v-model="search">
                    </div>
                    <div class="view-mode">
                        <div class="view-item">
                            <input @change="setView($event,'vehicles_view')" type="radio" name="view-mode"
                                   id="table-grid" :value="'table-grid'"
                                   :checked="$auth.user().vehicles_view == 'table-grid' || $auth.user().vehicles_view == null"
                                   class="d-none">
                            <label for="table-grid" v-b-tooltip.hover :title="$t('grid_display')"><i
                                class="fal fa-grip-horizontal"></i></label>
                        </div>
                        <div class="view-item">
                            <input @change="setView($event,'vehicles_view')" type="radio" name="view-mode"
                                   id="table-banner" :value="'table-banner'"
                                   :checked="$auth.user().vehicles_view == 'table-banner'" class="d-none">
                            <label for="table-banner" v-b-tooltip.hover :title="$t('line_display')"><i
                                class="fal fa-bars"></i></label>
                        </div>
                        <div class="view-item">
                            <input @change="setView($event,'vehicles_view')" type="radio" name="view-mode"
                                   id="table-base" :value="'table-base'"
                                   :checked="$auth.user().vehicles_view == 'table-base'" class="d-none">
                            <label for="table-base" v-b-tooltip.hover :title="$t('table_display')"><i
                                class="fal fa-table"></i></label>
                        </div>
                    </div>
                </div>

                <vue-good-table
                    :columns="columns"
                    :rows="allUserModels"
                    styleClass="vgt-table striped"
                    :pagination-options="{
                enabled: allUserModels.length>15,
                mode:'pages',
                perPage:15,
                perPageDropdownEnabled: true,
                rowsPerPageLabel: 'Véhicules par page',
                ofLabel:'sur',
                allLabel:$t('tous'),
                nextLabel:$t('next'),
                prevLabel:$t('prcdent'),
                 }"
                    :search-options="{
                        enabled: true,
                        externalQuery: search
                        }">
                    <template slot="table-row" slot-scope="props">
                        <template v-if="props.column.field == 'field'">
                            <div :class="$auth.user().vehicles_view != 'table-grid' ?'col-md-6 px-md-1':'col-12 mb-2 px-0' ">
                                <router-link class="btn btn-sm btn-full btn-center bg-gold"
                                             :to="{ name: 'searchCarPartsCategories', params:{idCarPerso:props.row.id } }">
                                    {{ $t('new_research') }}
                                </router-link>
                            </div>

                            <div :class="$auth.user().vehicles_view != 'table-grid' ?'col-md-6 px-md-1':'col-12 px-0' ">
                                <router-link class="btn btn-sm btn-full btn-center bg-dark-grey"
                                             :to="{'name': 'carDetail', params: {id: props.row.id}}">{{ $t('see_vehicle') }}
                                </router-link>
                            </div>
                        </template>
                        <template v-else-if="props.column.field=='deletion'">
                            <i class="fal fa-trash-alt text-danger" v-b-modal="'modal-center'+props.row.id" v-if="props.row.researches_count"></i>
                            <span @click="removal(props.row)" v-else class="cursor-p">
                            <i class="fal fa-trash-alt edit text-danger"></i>
                            </span>
                            <b-modal :id="'modal-center'+props.row.id" centered hide-footer hide-header content-class="rounded border-0 shadow-lg">
                                <div class="py-3 text-center">
                                    <div class="position-relative text-blue">
                                        <i class="fal fa-car fa-5x pb-3"></i>
                                        <h3 class="text-center">{{ $t('confirm_vehicle_removal') }}</h3>
                                    </div>
                                    <i18n tag="p" path="all_research_removal" class="h6"><br></i18n>
                                    <a class="btn bg-gold btn-sm mt-3" @click="$bvModal.hide('modal-center'+props.row.id)">{{ $t('do_not_remove') }}</a>
                                    <button class="btn bg-grey btn-sm mt-3" @click="removeUserModel(props.row);$bvModal.hide('modal-center'+props.row.id)">{{ $t('remove') }}</button>
                                </div>
                            </b-modal>
                        </template>

                        <template v-else-if="props.column.field == 'modele.brand.name'">
                            <div class="img-wrapper" v-if="$auth.user().vehicles_view == 'table-banner'">
                                <img v-if="props.row.modele.img" class="little-car"
                                     :src="'/images/Cars/'+props.row.modele.img+'.png'">
                                <img v-else :src="'/images/Cars/emptycar.png'" class="little-car">
                            </div>
                            <span>
                                {{ props.row.modele.brand.name }} <span
                                v-if="$auth.user().vehicles_view != 'table-base'">{{
                                    props.row.modele.name
                                }} {{ props.row.version }} <small
                                    class="">{{ props.row.year }}</small></span>
                            </span>

                            <div class="nb-piece" v-if="$auth.user().vehicles_view == 'table-grid'">
                                <template v-if="props.row.researches_count&&props.row.researches_count!=0">
                                    {{ $tc('searching_x_products', props.row.researches_count, {'0': props.row.researches_count}) }}
                                </template>
                                <template v-else>
                                    {{ $t('no_research_currently') }}
                                </template>
                            </div>

                        </template>

                        <template v-else-if="props.column.field == 'researches_count'">
                            <template v-if="$auth.user().vehicles_view != 'table-base'">
                                <template v-if="props.row.researches_count!=0">
                                    {{ $tc('searching_x_products', props.row.researches_count, {'0': props.row.researches_count}) }}
                                </template>
                                <template v-else>
                                    {{ $t('no_research_currently') }}
                                </template>
                            </template>
                        </template>


                        <template v-else-if="props.column.field == 'modele.img'">
                            <img v-if="props.row.modele.img != null" class="little-car"
                                 :src="'/images/Cars/'+props.row.modele.img+'.png'">
                            <img v-else :src="'/images/Cars/emptycar.png'" class="little-car">
                        </template>

                    </template>
                    <div slot="emptystate">
                        {{ $t('no_vehicles') }}
                    </div>
                </vue-good-table>
            </div>

            <div v-else><span class="loader"></span></div>

        </div>
    </div>
</template>

<script>
import mxView from "../../../mixins/View";

const UserCarItem = () => import(/* webpackChunkName: "group-finder" */'./UserCarItem');
const TopNav = () => import(/* webpackChunkName: "group-dashboard" */"../../Shared/TopNav");

import {mapActions, mapGetters} from 'vuex';

export default {
    name: 'UserCarsList',
    components: {UserCarItem, TopNav},
    mixins: [mxView],
    data() {
        return {
            columns: [
                {
                    label: 'Image',
                    field: 'modele.img',
                    thClass: 'img-wrapper',
                    tdClass: 'img-wrapper banner-none',
                },
                {
                    label: 'Marque',
                    field: 'modele.brand.name',
                    tdClass: 'font-weight-bold title',
                },
                {
                    label: 'Modèle',
                    field: 'modele.name',
                    tdClass: 'grid-none banner-none',
                },
                {
                    label: 'Année',
                    field: 'year',
                    type: 'number',
                    thClass: 'text-left',
                    tdClass: 'text-left grid-none banner-none',
                },
                {
                    label: 'Version',
                    field: 'version',
                    tdClass: 'grid-none banner-none',
                }, {
                    label: 'Nb. de recherches',
                    field: 'researches_count',
                    type: 'number',
                    thClass: 'text-center',
                    tdClass: 'text-center nb-piece grid-none',
                },
                {
                    label: 'Action',
                    field: 'field',
                    sortable: false,
                    tdClass: 'row mx-0 action',
                },
                {
                    label: '',
                    field: 'deletion',
                    sortable: false,
                    tdClass: 'deletion text-center',

                },
            ],
            search: '',
        }
    },
    created() {
        this.fetchAllUserModels();
    },
    computed: mapGetters('userModels', ['allUserModels', 'isUserModelLoaded']),
    methods: {
        removal(car) {
            const h = this.$createElement;
            const body = [h('div', {class: ['position-relative', 'text-blue']}, [
                h('i', {class: 'fal fa-car fa-5x pb-3'}),
                h('h3', {class: 'text-center'}, [
                    'Êtes-vous sûr de vouloir supprimer la véhicule ?'
                ])
            ]), h('p', {class: 'h3'}, [
            ])];
            this.$removalConfirmation(body,null).then(removal => removal ? this.removeUserModel(car) : null)
        },
        ...mapActions('userModels', ['newUserModel', 'fetchAllUserModels','removeUserModel']),

    }
}

</script>


