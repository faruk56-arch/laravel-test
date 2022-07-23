<template>
    <div class="dashboard">
        <div class="bg-white shadow container-fluid py-lg-4 py-3 px-4 mb-4">

            <div class="d-flex align-items-center justify-content-between">
                <h1 class="h2 text-blue mb-0 pt-lg-3 pt-2">{{ $t('my_dashboard') }}</h1>
                <top-nav></top-nav>
            </div>

        </div>
        <div class="container-fluid px-4">
            <div class="row pt-2">
                <div class="col-md-8 col-xl-9">
                    <p class="lead font-weight-normal text-blue pb-2 d-md-block d-none">{{ $t('welcome_aboard') }}
                        <strong class="text-gold">{{ user && (user.firstname) }} !</strong>
                    </p>

                    <div class="row" v-if="user">
                        <last-researches/>
                        <last-sales/>
<!--                        <new-research :select="true"/>-->
                    </div>

                </div>
                <notification-list/>
            </div>
        </div>
    </div>
</template>
<script>

import {mapActions} from "vuex";

const NotificationList = () => import(/* webpackChunkName: "group-dashboard" */"./Dashboard/NotificationList");
const LastResearches = () => import(/* webpackChunkName: "group-dashboard" */"./Dashboard/LastResearches");
const LastSales = () => import(/* webpackChunkName: "group-dashboard" */"./Dashboard/LastSales");
const NewResearch = () => import(/* webpackChunkName: "group-dashboard" */"./Dashboard/NewResearch");
const TopNav = () => import(/* webpackChunkName: "group-dashboard" */"../Shared/TopNav");

export default {
    name: 'Dashboard',
    components: {LastSales, LastResearches, NotificationList, NewResearch,TopNav},
    created() {
        this.fetchAllUserModels();
    },
    methods: {
        ...mapActions('userModels', ['fetchAllUserModels'])
    },
    computed: {
        user() {
            return this.$auth.user()
        }
    }
}
</script>
