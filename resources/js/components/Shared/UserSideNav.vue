<template>
    <div class="user-side-nav bg-white" :class="{ 'user-side-nav-visible':$store.state.sideMenu}">

        <div class="main-nav bg-gold text-white">

            <div class="top-menu d-block d-md-flex flex-column align-items-center">
                <a href="https://classic-parts-finder.com" class="d-none d-md-block">
                    <img class="logo-white" :src="'/images/logo/logo_classic_parts_finder_white.svg'">
                </a>

                <ul class="mt-md-4">
                    <li>
                        <b-popover target="dashboard-item" triggers="hover"
                                   custom-class="menu-popover d-none d-md-block shadow" placement="right">{{
                                $t('my_dashboard')
                            }}
                        </b-popover>
                        <router-link id="dashboard-item" :to="{ name: 'dashboard' }" class="text-white">
                            <i class="fal fa-tachometer-alt-slow"></i>
                            <span class="d-inline d-md-none">{{ $t('my_dashboard') }}</span>
                        </router-link>
                    </li>
                    <hr class="w-50 border-white my-2">

                    <template v-if="isUserModelLoaded&&allUserModels.length>0">
                        <li>
                            <b-popover target="researchList-item" triggers="hover"
                                       custom-class="menu-popover d-none d-md-block shadow" placement="right">{{
                                    $t('my_researches')
                                }}
                            </b-popover>
                            <router-link id="researchList-item" :to="{ name: 'researchList' }" class="text-white">
                                <i class="fal fa-search"></i>
                                <span class="d-inline d-md-none">{{ $t('my_researches') }}</span>
                            </router-link>
                        </li>
                        <li>
                            <b-popover target="carList-item" triggers="hover"
                                       custom-class="menu-popover d-none d-md-block shadow" placement="right">{{
                                    $t('my_vehicles')
                                }}
                            </b-popover>
                            <router-link id="carList-item" :to="{'name':'carList'}" class="text-white">
                                <i class="fal fa-car"></i>
                                <span class="d-inline d-md-none">{{ $t('my_vehicles') }}</span>
                            </router-link>
                        </li>
                    </template>

                    <!--                    <li v-if="total>0">-->
                    <!--                        <b-popover target="orderList-item" triggers="hover" custom-class="menu-popover shadow" placement="right">Mes commandes</b-popover>-->
                    <!--                        <router-link id="orderList-item" :to='{name:"orders"}' class="text-white">-->
                    <!--                            <i class="fal fa-shopping-cart"></i>-->
                    <!--                        </router-link>-->
                    <!--                    </li>-->

                    <hr class="w-50 border-white my-2"
                        v-if="user && user.merchant_id &&isUserModelLoaded&&allUserModels.length>0">

                    <li v-if="user && user.merchant_id">
                        <b-popover target="researchAlert-item" triggers="hover"
                                   custom-class="menu-popover d-none d-md-block shadow" placement="right">{{
                                $t('my_simplified_announces')
                            }}
                        </b-popover>
                        <router-link id="researchAlert-item" :to="{ name: 'researchAlert' }" class="text-white">
                            <i class="fal fa-bullhorn"></i>
                            <span class="d-inline d-md-none">{{ $t('my_simplified_announces') }}</span>
                        </router-link>
                    </li>

                    <li v-if="user && user.merchant_id">
                        <b-popover target="catalog-item" triggers="hover"
                                   custom-class="menu-popover d-none d-md-block shadow" placement="right">{{
                                $t('my_products')
                            }}
                        </b-popover>
                        <router-link id="catalog-item" :to="{ name: 'catalog' }" class="text-white">
                            <i class="fal fa-store"></i>
                            <span class="d-inline d-md-none">{{ $t('my_products') }}</span>
                        </router-link>
                    </li>

                    <!--                    <li v-if="$auth.user().merchant_id">-->
                    <!--                        <b-popover target="sales-item" triggers="hover" custom-class="menu-popover shadow" placement="right">Mes ventes</b-popover>-->
                    <!--                        <router-link id="sales-item" :to="{ name: 'sales' }" class="text-white">-->
                    <!--                            <i class="fal fa-hands-helping"></i>-->
                    <!--                        </router-link>-->
                    <!--                    </li>-->

                    <template v-if="$auth.user().role !== 'admin'">
                        <hr class="w-50 border-white my-2">

                        <li>
                            <b-popover target="orders" triggers="hover" custom-class="menu-popover d-none d-md-block shadow"
                                       placement="right">Orders
                            </b-popover>

                            <!--
                            <router-link id="orders" :to="{ name: 'orders', params:{id:user.id} }" class="text-white">
                                <i class="fal fa-cart-plus"></i>
                                <span class="d-inline d-md-none">Orders</span>
                            </router-link>
                            -->

                            <router-link id="orders" :to="{ name: 'orders' }" class="text-white">
                                <i class="fal fa-cart-plus"></i>
                                <span class="d-inline d-md-none">Orders</span>
                            </router-link>
                        </li>

                    </template>

                    <template v-else>
                        <hr class="w-50 border-white my-2">
                        <li>
                            <b-popover target="admin-orders" triggers="hover" custom-class="menu-popover d-none d-md-block shadow"
                                       placement="right">Orders
                            </b-popover>

                            <!--
                            <router-link id="orders" :to="{ name: 'orders', params:{id:user.id} }" class="text-white">
                                <i class="fal fa-cart-plus"></i>
                                <span class="d-inline d-md-none">Orders</span>
                            </router-link>
                            -->

                            <router-link id="admin-orders" :to="{ name: 'allOrders' }" class="text-white">
                                <i class="fal fa-cart-plus"></i>
                                <span class="d-inline d-md-none">Orders</span>
                            </router-link>
                        </li>

                        <hr class="w-50 border-white my-2">
                        <li>
                            <b-popover target="admin-boxtal" triggers="hover" custom-class="menu-popover d-none d-md-block shadow"
                                       placement="right">Boxtal Dashboard
                            </b-popover>

                            <router-link id="admin-boxtal" :to="{ name: 'BoxtalDashboard' }" class="text-white">
                                <i class="fal fa-columns"></i>
                                <span class="d-inline d-md-none">Boxtal Dashboard</span>
                            </router-link>
                        </li>
                    </template>

                </ul>


            </div>

            <!-- <ul>
                <li class="link-inbox">
                    <b-popover target="inbox-item" triggers="hover" custom-class="menu-popover shadow" placement="right">Messagerie</b-popover>
                    <router-link id="inbox-item" href="" class="text-white py-1" :to="{name:'Inbox'}">
                        <i class="fal fa-inbox position-relative">
                            <div class="new-event-badge p-1 badge badge-danger rounded-circle font-weight-normal" v-if="nbMessages>0">
                                {{ nbMessages }}</div>
                        </i>
                    </router-link>
                </li>
                <li v-if="user && user.role=='admin'">
                    <b-popover target="admin-item" triggers="hover" custom-class="menu-popover shadow" placement="right">Administration</b-popover>
                    <router-link id="admin-item" :to="{name: 'admin-research-waiting'}" class="text-white py-1">
                        <i class="fal fa-users-crown"></i>
                    </router-link>
                </li> -->
            <!--                <li class="link-settings">-->
            <!--                    <b-popover target="settings-item" triggers="hover" custom-class="menu-popover shadow" placement="right">Paramètres</b-popover>-->
            <!--                    <router-link :to="{name:'Settings'}" id="settings-item" href="" class="text-white py-1">-->
            <!--                        <i class="fal fa-cog"></i>-->
            <!--                    </router-link>-->
            <!--                </li>-->
            <!-- <li>
                <b-popover target="disconect-item" triggers="hover" custom-class="menu-popover shadow" placement="right">Déconnexion</b-popover>
                <a id="disconect-item" class="basic text-white py-1" @click.prevent="logout">
                    <i class="fal fa-sign-out"></i>
                </a>
            </li>
            <li class="mt-2">
                <b-popover target="userProfil" triggers="hover" custom-class="menu-popover last-pop-menu shadow" placement="right">Paramètres</b-popover>
                <div class="link-account" id="userProfil">
                    <router-link :to="{ name: 'userProfil' }" class="bg-white">
                        {{ user && userInitials }}
                    </router-link> -->
            <!--                    <a class="bg-white" style="color:#33485D;">-->
            <!--                        {{ ($auth.user().firstname[0] + $auth.user().lastname[0]).toUpperCase() }}-->
            <!--                    </a>-->
            <!-- </div>
        </li>
    </ul> -->

        </div>

    </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';

import UserCarsList from '../Finder/UserCar/UserCarsList';

export default {
    name: 'UserSideNav',
    components: {UserCarsList},
    computed: {
        user() {
            return this.$auth.user()
        },
        ...mapGetters('messages', ['nbMessages']),
        ...mapGetters('messages', ['nbMessages']),
        ...mapGetters('researches', ['total', 'isCountLoaded']),
        ...mapGetters('userModels', ['allUserModels', 'isUserModelLoaded']),
    },
    created() {
        this.fetchAllResearchesCount();
        this.fetchAllMessagesCount();
        this.fetchAllUserModels();
    },
    methods: {
        ...mapActions('researches', ['fetchAllResearchesCount']),
        ...mapActions('messages', ['fetchAllMessagesCount']),
        ...mapActions('userModels', ['newUserModel', 'fetchAllUserModels'])
    }
};
</script>
