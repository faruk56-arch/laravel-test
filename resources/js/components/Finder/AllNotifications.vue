<template>
    <div class="all-notifications">

        <div class="bg-white shadow container-fluid py-4 px-4 mb-4">

            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <a class="cursor-p pl-1 pr-3" @click="$router.go(-1)"><i class="fal fa-2x fa-arrow-left"></i></a>
                    <h1 class="h2 d-inline-block text-blue mb-0 pt-3">{{ $t('notifications') }}</h1>
                </div>
                <top-nav></top-nav>
            </div>

        </div>

        <div class="container pt-2">
            <div class="row">
                <div class="col-12" v-if="isNotifLoaded&& allNotifications.length === 0">
                    {{ $t('no_notification') }}
                </div>
                <div class="col-md-12" v-else-if="isNotifLoaded" v-for="notification in allNotifications" :key="notification.id">
                    <notification-item :notification="notification"/>
                </div>
                <div v-else>
                    <span class="loader"></span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from "vuex";

    const NotificationItem = () => import("./Dashboard/NotificationItem");
    const TopNav = () => import(/* webpackChunkName: "group-dashboard" */"../Shared/TopNav");

    export default {
        name: "AllNotifications",
        components: {NotificationItem, TopNav},
        computed: mapGetters('notifications', ['allNotifications', 'isNotifLoaded']),
        created() {
            this.fetchAllUserNotifications();
        },
        methods: {
            ...mapActions('notifications', ['fetchAllUserNotifications']),
        },
    }
</script>

<style scoped>

</style>
