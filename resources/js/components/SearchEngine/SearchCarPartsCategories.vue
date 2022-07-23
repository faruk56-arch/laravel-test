<template>
    <div class="search-car-parts-categories row mx-0">
        <div class="col-md-7 mb-3 mb-md-0 col-12 categories-img px-0">
            <img class="w-100" :src="'/images/SearchCarPartsCategories/simple_car_classic_parts_finder.png'" :class="{active : categoryHovered==='simple'}">
            <img v-for="category in allCategories" class="w-100" :src="category.url" :class="{active : categoryHovered===category.hover}">
        </div>
        <div class="col-md-5 pr-md-0 offset-md-7 col-12 categories-list">

            <a class="basic text-bold-dark-grey d-inline-block mb-2 pointer cursor-p" @click="$router.go(-1)">
                <i class="fal fa-long-arrow-left"></i>
                {{ $t('back') }}</a>

            <div class="form form-xl">
                <label class="text-blue px-0 pb-4 pt-2 d-block">{{ $t('which_kind_of_part') }} <sup class="text-danger">*</sup></label>
            </div>
            <ul>
                <li v-for="category in allCategories" class="cursor-p">
                    <a @click="selectCategory(category)" @mouseover="categoryHovered = category.hover" @mouseleave="mouseLeave()" :class="{'active shadow': categorySelected.hover===category.hover}">{{ translation('name', category) }}</a>
                </li>
            </ul>

            <router-link dusk="category-next" tag="a" :class="categorySelected.id==0?'disabled':''" class="btn btn-lg bg-blue mb-2" :to="{ name: 'searchCarParts'}">
                {{ $t('next_step') }}
            </router-link>

        </div>

    </div>
</template>

<script>
    import mxCars from "../../mixins/Cars";
    import {mapGetters} from "vuex";

    export default {
        data() {
            return {
                categorySelected: {
                    id: 0
                },
                categoryHovered: 'simple',
            }
        },
        props: ['data'],
        mixins: [mxCars],
        created() {
        },
        mounted() {
            let app = this;
            if (this.data.category.id) {
                this.categorySelected = this.data.category;
                this.categoryHovered = this.categorySelected.hover;
            }
        },
        methods: {
            mouseLeave(category) {
                if (this.categorySelected.id === 0) {
                    this.categoryHovered = 'simple';
                } else {
                    this.categoryHovered = this.categorySelected.hover;
                }
            },
            selectCategory(category) {
                this.categorySelected = category;
                this.$emit('data', category);
            }
        },
        computed: {
            ...mapGetters('categories', ['allCategories']),

        }
    }
</script>
