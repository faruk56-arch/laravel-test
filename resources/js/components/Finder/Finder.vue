<template>

    <div class="finder">
        <user-side-nav></user-side-nav>

        <CoolLightBox
            :items="allItems"
            :index="index"
            use-zoom-bar
            @close="setIndex(null)">
        </CoolLightBox>
        <div class="finder-content">
            <router-view :key="$route.fullPath"></router-view>
        </div>

    </div>
</template>
<script>
import {mapActions, mapGetters} from "vuex";

const UserSideNav = () => import(/* webpackChunkName: "group-finder" */"../Shared/UserSideNav");

export default {
    name: 'Finder',
    components: {UserSideNav},
    data() {
        return {
            indexLightBox: null,
        }
    },
    methods: {
        ...mapActions('coolLightBox', ['setIndex'])
    },
    computed: {
        ...mapGetters('coolLightBox', ['allItems', 'index'])
    }
}
</script>
