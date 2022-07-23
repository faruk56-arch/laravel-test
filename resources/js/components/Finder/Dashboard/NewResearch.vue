<template>
    <div class="col-12 py-5 text-center dash-research" v-if="allUserModels.length>0">
        <p class="mb-0 h3">{{ $t('new_part_for_your') }}
            <select v-if="allUserModels.length>1 && select" v-model="carSelected" class="pl-1">
                <option v-for="(model,i) in allUserModels" :value="i">
                    {{model && model.car_name ? model.modele.brand.name+' '+model.modele.name:model.modele.brand.name+' '+model.modele.name}}
                </option>
            </select>
            <span v-else-if="car" class="text-blue"> {{ car.car_name ? car.modele.brand.name+' '+car.modele.name+'('+car.car_name+')':car.modele.brand.name+' '+car.modele.name}}</span>
            <span v-else-if="!car"> {{ allUserModels[0] && allUserModels[0].car_name ? allUserModels[0].modele.brand.name+' '+allUserModels[0].modele.name+'('+allUserModels[0].car_name+')':allUserModels[0].modele.brand.name+' '+allUserModels[0].modele.name}}</span>
            ?
        </p>
        <p class="lead font-weight-normal mb-2 mt-2">{{ $t('we_look_for_you') }}</p>
        <router-link class="btn bg-gold btn-sm mt-2" v-if="allUserModels.length>1 && select" :to="{ name: 'searchCarPartsCategories', params:{idCarPerso:allUserModels[carSelected].id } }">
            {{ $t('new_research') }}
        </router-link>
        <router-link class="btn bg-gold btn-sm mt-2" v-else-if="car" :to="{ name: 'searchCarPartsCategories', params:{idCarPerso:car.id } }">
            {{ $t('new_research') }}
        </router-link> <router-link class="btn bg-gold btn-sm mt-2" v-else-if="!car" :to="{ name: 'searchCarPartsCategories', params:{idCarPerso:allUserModels[0].id } }">
            {{ $t('new_research') }}
        </router-link>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "NewResearch",
        props: ['select', 'car'],
        computed: {
            ...mapGetters('userModels', ['allUserModels']),
        },
        data() {
            return {
                carSelected: 0
            }
        }
    }
</script>
