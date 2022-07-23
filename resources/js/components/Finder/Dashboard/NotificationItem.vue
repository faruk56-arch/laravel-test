<template>

    <div class="notification-item bg-white shadow rounded d-block py-3 px-3 pl-4 mb-3" :class="notification.data.state.class" v-if="notification.data">

        <span class="float-right cursor-p" @click.prevent="markAsRead([notification.id])" v-if="$route.name!='notifications'">
            <i class="fal fa-times"></i>
        </span>

        <p class="mb-0 line-height-100">

            <i class="fal fa-bullhorn mr-1" v-if="modelName === 'alert_subscriptions'"></i>
            <i class="fal fa-search mr-1" v-if="modelName === 'researches'"></i>
            <i class="fal fa-store mr-1" v-if="modelName === 'products'"></i>

            <template v-if="modelName === 'alert_subscriptions'">
                <template v-if="notification.data.state.id===16">{{ $t('someone_research_your_part') }}</template>
                <template v-if="notification.data.state.id===17">{{ $t('you_created_simplified_announce') }}</template>
            </template>
            <template v-if="modelName === 'researches'">
                <template v-if="notification.data.state.id===16">{{ $t('we_found_product_for_you') }}</template>
                <template v-if="notification.data.state.id===17">{{ $t('you_created_research') }}</template>
                <template v-if="notification.data.state.id===18">{{ $t('part_attributed_to_research') }}</template>
            </template>
            <template v-if="modelName === 'products'">{{ $t('part_attributed_to_product') }}</template>
        </p>

        <hr class="my-2">

        <div v-if="modelName === 'alert_subscriptions'">
            <div class="d-flex align-items-center">
                <template v-if="model.modele">
                <img v-if="model.modele.img" class="little-car pl-2 pr-3 py-3" :src="'/images/Cars/tn/'+model.modele.img+'.png'" @error="model.modele.img=null">
                <img v-else class="little-car pl-2 pr-3 py-3" :src="'/images/Cars/tn/emptycar.png'">
                <span v-if="!model.year">
                    {{ model.brand.name }}<br>
                    {{ model.modele.name }} - ({{ model.modele.year_start }}-{{ model.modele.year_end }})
                </span>
                <span v-else>
                     {{ model.brand.name }}<br>
                    {{ model.modele.name }} - ({{ model.year}})+
                </span>
                </template>
            </div>

            <div class="row mb-2">
                <div class="col-md-6 col-xxl-4 pr-0">
                    <strong class="text-blue d-block">{{ $t('date') }}</strong>
                    {{  moment(notification.created_at).format("DD/MM/YYYY")}}
                </div>
                <div class="col-md-6 col-xxl-4 line-height-100">
                    <strong class="text-blue d-block mb-1">{{ $t('part') }}</strong>
                    <template v-if="model.part != null">{{ translation('name', model.part) }}</template>
                    <template v-else>{{ translation('name', model.category) }}</template>
                </div>
                <div class="col-12 col-xxl-4 pl-xxl-0 mt-2 mt-xxl-0">
                    <router-link div="button" class="btn btn-xs bg-blue w-100" :class="'bg-'+notification.data.state.class" :to="{name: modelRoute, params: {id: model.id}}">{{ $t('see_simplified_announce') }}</router-link>
                </div>
            </div>


        </div>

        <div v-if="modelName === 'researches'">
            <div class="d-flex align-items-center">
                <template v-if="model.modele">
                <img v-if="model.modele.modele.img" class="little-car pl-2 pr-3 py-3" :src="'/images/Cars/tn/'+model.modele.modele.img+'.png'" @error ="model.modele.modele.img=null" >
                <img v-else class="little-car pl-2 pr-3 py-3" :src="'/images/Cars/tn/emptycar.png'">
                <span>
                    {{ model.modele.modele.brand.name }}<br>
                    {{ model.modele.modele.name }} - ({{ model.modele.modele.year_start }}-{{ model.modele.modele.year_end }})
                </span>
                </template>
            </div>
            <div class="row mb-2">
                <div class="col-md-6 col-xxl-4 pr-0">
                    <strong class="text-blue d-block">{{ $t('date') }}</strong>
                    {{ moment(notification.created_at).format("DD/MM/YYYY")}}
                </div>
                <div class="col-md-6 col-xxl-4 line-height-100">
                    <strong class="text-blue d-block mb-1">{{ $t('part') }}</strong>
                    {{ translation('name', model.part) }}
                </div>
                <div class="col-12 col-xxl-4 mt-2 mt-xxl-0">
                    <router-link div="button" class="btn btn-xs bg-blue w-100" :class="'bg-'+notification.data.state.class" :to="{name: modelRoute, params: {id: model.id}}">{{ $t('details') }}</router-link>
                </div>
            </div>

        </div>

        <div v-if="modelName === 'products'">
            <div class="d-flex align-items-center">
                <img v-if="model.modele[0].img" class="little-car pl-2 pr-3 py-3" :src="'/images/Cars/tn/'+model.modele[0].img+'.png'" @error="model.modele[0].img=null">
                <img v-else class="little-car pl-2 pr-3 py-3" :src="'/images/Cars/tn/emptycar.png'">
                <span>
                    {{ model.modele[0].brand.name }}<br>
                    {{ model.modele[0].name }} - ({{ model.modele[0].year_start }}-{{ model.modele[0].year_end }})
                </span>
            </div>
            <span>{{ moment(notification.created_at).format("DD/MM/YYYY")}}</span>
            <span v-if="model.part">{{ translation('name', model.part) }}</span>
        </div>

    </div>

</template>

<script>
    import {mapActions} from "vuex";

    export default {
        name: "NotificationItem",
        props: {
            notification: {
                type: Object,
                default: {}
            }
        },
        methods: mapActions('notifications', ['markAsRead']),
        computed: {
            model() {
                return this.notification.data[this.modelName]
            },
            modelName() {
                return Object.keys(this.notification.data)[0]
            },
            modelRoute() {
                switch (this.modelName) {
                    case 'researches':
                        return 'researchProposalList'
                    case 'alert_subscriptions':
                        return 'ResearchAlertProposalList'
                    case 'products':
                        return 'catalog'
                }
            }
        }
    }
</script>

<style scoped>

</style>
