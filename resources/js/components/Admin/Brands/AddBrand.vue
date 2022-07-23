<template>

    <div class="add-brand">

        <div class="card add-bloc py-4 px-4 mb-4">

            <router-link class="pl-1 pr-3" :to="{ name: 'admin-brand' }">
                <i class="fal fa-long-arrow-left pr-1"></i> {{ $t('back') }}
            </router-link>

            <h3 class="mb-4 mt-2 text-blue">{{id?"Éditer une marque":"Ajouter une marque"}}</h3>

            <form>
                <div class="form-group form-sm">
                    <label for="brand">Nom de la marque <sup class="text-danger">*</sup></label>
                    <input id="brand" class="form-control" type="text" v-model="brand">
                </div>
                <router-link tag="button" :class="brand!=''?'':'disabled'" class="btn btn-sm btn-primary"
                             :to="{name:'admin-brand'}" @click.native="id ?editBrand([id,brand]):newBrand([brand])">
                    {{id ? 'Mettre à jour' : 'Ajouter'}}</router-link>
            </form>

        </div>

    </div>

</template>
<script>

import {mapActions, mapGetters} from "vuex";

    export default {
        data(){
            return{
                brand:''
            }
        },
        props: {
            id: {
                required: false
            }
        },
        methods: {
            ...mapActions('brands', ['newBrand','editBrand']),

        },
        created() {
            if (this.id) {
                this.brand = this.getBrand(this.id).name
            }
        },
        computed:{
            ...mapGetters('brands', ['getBrand']),
        }
    }
</script>
