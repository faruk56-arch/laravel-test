<template>
    <div class="notification-list col-md-4 col-xl-3 custom-scroll">

        <div class="pb-2 mb-3 d-flex justify-content-between align-items-center">
            <p class="lead mb-0 font-weight-normal text-blue">{{ $t('notifications') }}</p>
            <router-link :to="{name: 'notifications'}" class="font-weight-normal all-notifications-btn text-gold">
                <i class="fal fa-bell pr-1"></i>{{ $t('all') }}
            </router-link>
        </div>

        <div class="notification-item-wrapper">
            <div v-if="isNotifLoaded&&allUnreadNotifications.length === 0">
                {{ $t('no_unread_notifications') }}
            </div>
            <div v-else-if="isNotifLoaded" v-for="notification in allUnreadNotifications" :key="notification.id">
                <notification-item :notification="notification"/>
            </div>
            <div v-else>
                <span class="loader"></span>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from "vuex";

    const NotificationItem = () => import("./NotificationItem");

    export default {
        name: "NotificationList",
        components: {NotificationItem},
        computed: mapGetters('notifications', ['allUnreadNotifications', 'isNotifLoaded']),
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
