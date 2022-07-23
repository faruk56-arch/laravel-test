<template>

    <div class="admin-dashboard">

        <div class="bg-white shadow container-fluid py-lg-4 py-3 px-4 zindex-top">

            <div class="d-flex align-items-center justify-content-between">
                <h1 class="h2 text-blue mb-0 pt-lg-3 pt-2">Administration</h1>
                <top-nav></top-nav>
            </div>

        </div>

        <div class="content-wrapper position-relative" :class="{'hide-nav' : !viewNav}">
            <div class="admin-nav-wrapper border-right">

                <div class="switch-nav bg-white border cursor-p" @click="viewNav = !viewNav">
                    <i class="far fa-chevron-left"></i>
                </div>

                <div class="p-4 text-blue">
                    <p class="font-weight-normal mb-0">Bienvenue dans l'administration de Classic Parts Finder<strong class="text-gold"> {{ $auth.user().firstname + ' ' + $auth.user().lastname }} !</strong>
                    </p>
                </div>

                <ul class="list-unstyled">
                    <li class="border-bottom">
                        <router-link class="border-top d-flex justify-content-between align-items-center text-blue no-active" :to="{name: 'admin-research-waiting'}">
                            <p class="mb-0 pl-4 py-3">Recherches</p>
                            <div>
                                <span class="ml-1 mr-5 badge bg-gold text-white font-weight-normal" v-if="allResearches.filter(r=>!r.status&&r.user.firstname && !r.deleted_at).length != 0">{{ allResearches.filter(r => !r.status && r.user.firstname && !r.deleted_at).length }} à valider</span>
                                <i class="far fa-angle-down mr-4" :class="{'flip':viewSubResearch}"></i>
                            </div>
                        </router-link>
                        <ul class="list-unstyled bg-grey sub-menu" :class="{'active':viewSubResearch}">
                            <li>
                                <router-link class="d-block cursor-p" :to="{name: 'admin-research-waiting'}">
                                    <p class="mb-0 pl-4 py-2">À valider</p>
                                </router-link>
                            </li>
                            <li>
                                <router-link class="d-block cursor-p" :to="{name: 'admin-research-all'}">
                                    <p class="mb-0 pl-4 py-2">Toutes</p>
                                </router-link>
                            </li>
                        </ul>
                    </li>
                    <li class="border-bottom">
                        <router-link class="d-block no-active d-flex justify-content-between align-items-center" :to="{name: 'admin-product-waiting'}">

                            <p class="mb-0 pl-4 py-3">Annonces</p>

                            <div>
                                <span class="ml-1 mr-5 badge bg-gold text-white font-weight-normal" v-if="allCatalog.length != 0">{{ allCatalog.length }} à valider</span>
                                <i class="far fa-angle-down mr-4" :class="{'flip':viewSubCatalog}"></i>
                            </div>

                        </router-link>
                        <ul class="list-unstyled bg-grey sub-menu" :class="{'active':viewSubCatalog}">
                            <li>
                                <router-link class="d-block cursor-p" :to="{name: 'admin-product-waiting'}">
                                    <p class="mb-0 pl-4 py-2">À valider</p>
                                </router-link>
                            </li>
                            <li>
                                <router-link class="d-block cursor-p" :to="{name: 'admin-product-all'}">
                                    <p class="mb-0 pl-4 py-2">Toutes</p>
                                </router-link>
                            </li>
                        </ul>

                    </li>
                    <li>
                        <router-link class="border-bottom d-block cursor-p" :to="{name: 'AdminAllAlerts'}">
                            <p class="mb-0 pl-4 py-3">Annonces simplifiées</p>
                        </router-link>
                    </li>
                    <li>
                        <router-link class="border-bottom d-block cursor-p" :to="{name: 'MessagesAdmin'}">
                            <p class="mb-0 pl-4 py-3">Notifications</p>

                            <span class="ml-1 mr-4 badge bg-gold text-white font-weight-normal"
                                  v-if="countMessages > 0">{{ countMessages }}</span>
                        </router-link>
                    </li>
                    <li>
                        <router-link class="border-bottom d-block cursor-p" :to="{name: 'AdminUsersList'}">
                            <p class="mb-0 pl-4 py-3">Utilisateurs</p>
                        </router-link>
                    </li>
                    <li>
                        <router-link class="border-bottom d-block cursor-p" :to="{name: 'MessagesUser'}">
                            <p class="mb-0 pl-4 py-3">Modération</p>

                            <span class="ml-1 mr-4 badge bg-gold text-white font-weight-normal"
                                  v-if="allMessagesAdmin.filter(r=>r.template_id==4).length != 0">{{ allMessagesAdmin.filter(r => r.template_id == 4).length }}</span>
                        </router-link>
                    </li>
                    <li>
                        <router-link class="border-bottom d-block" :to="{name: 'admin-part'}">
                            <p class="mb-0 pl-4 py-3">Pièces</p>
                        </router-link>
                    </li>
                    <li>
                        <router-link class="border-bottom d-block" :to="{name: 'admin-modele'}">
                            <p class="mb-0 pl-4 py-3">Modèles</p>
                        </router-link>
                    </li>
                    <li>
                        <router-link class="border-bottom d-block" :to="{name: 'admin-brand'}">
                            <p class="mb-0 pl-4 py-3">Marques</p>
                        </router-link>
                    </li>

                    <!--
                    <li>
                        <router-link class="border-bottom d-block" :to="{name: 'allOrders'}">
                            <p class="mb-0 pl-4 py-3">Toutes les commandes</p>
                        </router-link>
                    </li>
                    -->

                    <li>
                        <router-link class="border-bottom d-block" :to="{name: 'platformFee'}">
                            <p class="mb-0 pl-4 py-3">Platform Fee</p>
                        </router-link>
                    </li>
                </ul>

            </div>

            <div class="admin-content-wrapper pt-5 px-5">
                <router-view :key="$route.fullPath"></router-view>
            </div>
        </div>

    </div>

</template>
<script>


import {mapGetters} from "vuex";

const TopNav = () => import(/* webpackChunkName: "group-dashboard" */"../Shared/TopNav");

export default {
    name: 'AdminDashboard',
    components: {TopNav},
    data() {
        return {
            viewNav: true,
        }
    },
    created() {
        if (!this.$auth.user()) {
            return this.$router.push({name: 'login'})
        }
        this.$store.dispatch('admin/fetchAllMessages');
        this.$store.dispatch('admin/fetchCatalog');
        this.$store.dispatch('messages/fetchAllMessagesAdmin')
    },
    computed: {
        ...mapGetters('admin', ['allResearches', 'allMessages', 'isMessagesLoaded', 'isResearchesLoaded', 'allProducts', 'allCatalog']),
        ...mapGetters('messages', ['allMessagesAdmin']),
        viewSubResearch() {
            return this.$route.name == "admin-research-waiting" ||
                this.$route.name == "admin-research-all" ||
                this.$route.name == "adminResearchDetails";
        },
        viewSubCatalog() {
            return this.$route.name == "admin-product-all" ||
                this.$route.name == "admin-product-waiting";
        },
        countMessages() {
            return this.allMessagesAdmin.filter(r => {
                return r.template_id !== 4 && r.read === 0
            }).length
        }
    },
    watch: {}
}
</script>
