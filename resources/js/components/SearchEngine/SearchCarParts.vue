<template>
    <div class="search-car-parts">
        <div class="form mb-3">
            <div class="form form-xl select-car-parts" v-if="!loading">
                <a class="basic text-bold-dark-grey d-inline-block mb-2 pointer cursor-p" @click="$router.go(-1)">
                    <i class="fal fa-long-arrow-left"></i>
                    {{ $t('back') }}</a>
                <label for="select-car-parts" class="text-blue pt-3">{{ $t('which_part_looking_for') }} <sup class="text-danger">*</sup></label>
                        <a class="w-100" :class="{'active':data.part.id ==null&& data.part.translation!=null}" @click="edited(msg)">
                            <not-found-form-research :placeholder="$t('part_name_etc')" @edit="edited"/>
                        </a>

            </div>
            <div v-else>
                {{ $t('loading') }}
            </div>
        </div>
        <router-link dusk="part-next" class="btn btn-lg bg-blue mb-2" :class="!data.part.translation?'disabled':''" :to="{ name: 'SearchDetails', params:{idCar:data.car.id,custom:data.car.custom,idCategory: data.category.id, idPart:partSelected.id}, query: {message: msg}}">{{ $t('next_step') }}</router-link>
    </div>
</template>
<script>
    import {mapActions, mapGetters} from 'vuex';

    const NotFoundFormResearch = () => import("../Shared/NotFoundFormResearch");

    export default {
        components: {NotFoundFormResearch},
        props: ['data'],
        data() {
            return {
                partSelected: {
                    id: 0
                },
                parts: [],
                loading: false,
                car: null,
                msg: '',
                initial:true,
            }
        },
        created() {
            let app = this
            axios.get('/modele/' + this.data.car.id).then(function (res) {
                app.car = res.data;
            })
        },
    computed: {
        ...mapGetters('parts', ['allModeleCategoryParts'])

    },
        methods: {
            searchPart(input) {
                if (input === '') {
                    this.initial = true;
                    return this.parts = this.allModeleCategoryParts.slice(0,9)
                }
                let app = this;
                this.initial = false;
                return axios.get(
                    '/modele/' + this.data.car.id +
                    '/category/' + this.data.category.id +
                    '/part?name=' + input
                ).then(res => app.parts = res.data)
            },
            getResultValue(result) {
                return result.translation;
            },

            edited(e) {
                this.partSelected = {
                    id: 0
                }
                this.msg = e;
                this.partSelected.translation = e;
                this.partSelected.id = null;
                this.$emit('data', this.partSelected);
            },
            selectPart(part) {
                this.partSelected = part;
                this.$emit('data', this.partSelected);
            }
        },
    }
</script>
