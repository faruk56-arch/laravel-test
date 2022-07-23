<template>
    <div class="user-top-nav-container">
        <!-- <span class="d-inline-block d-md-none float-left pt-2 pl-3">
            <i class="far fa-bars fa-2x" @click="$store.commit('toggleMenu')" v-if="!$store.state.sideMenu"></i>
            <i class="far fa-times fa-2x" v-else @click="$store.commit('toggleMenu')"></i>
        </span> -->
        <a href="https://classic-parts-finder.com/" class="d-block d-md-none float-left pt-2 pl-2">
            <img class="logo" :src="'/images/logo/logo_classic_parts_finder_bicolor.svg'">
        </a>
        <ul class="list-unstyled user-top-nav mb-0">
            <li class="mr-3">
                <b-dropdown class="add-item-button-wrapper" menu-class="shadow border-0" variant="link" toggle-class="text-decoration-none add-item-button"  no-caret>
                    <template #button-content>
                        <button class="btn bg-gold btn-sm d-flex align-items-center rounded-pill">
                            <i class="fal fa-plus mr-md-2 fa-lg"></i>
                            <span>{{ $t('add') }}</span>
                        </button>
                    </template>
                    <template>
                        <router-link tag="b-dropdown-item" :to="{name:'searchCarModel'}"><i class="fal fa-search fa-fw mr-1"></i>{{ $t('research') }}
                        </router-link>
                        <router-link tag="b-dropdown-item" :to="{ name: 'editUserCar', params: { id: 0 }}"><i class="fal fa-car fa-fw mr-1"></i> {{ $t('vehicle') }}
                        </router-link>
                    </template>
                    <b-dropdown-divider v-if="user && user.merchant_id &&isUserModelLoaded&&allUserModels.length>0"></b-dropdown-divider>
                    <template v-if="user && user.merchant_id">
                        <router-link tag="b-dropdown-item" :to="{name:'addAlert'}"><i class="fal fa-bullhorn fa-fw mr-1"></i> {{ $t('simplified_announce') }}
                        </router-link>
                        <router-link tag="b-dropdown-item" :to="{name:'addProduct'}"><i class="fal fa-store fa-fw mr-1"></i> {{ $t('product') }}
                        </router-link>
                    </template>
                </b-dropdown>
            </li>
            <li class="mr-3" v-if="user && user.role=='admin'">
                <router-link id="admin-item-2" v-b-tooltip.hover :title="$t('administration')"
                             :to="{name: 'admin-research-waiting'}" class='icon-link'>
                    <i class="fal fa-users-crown fa-lg"></i>
                </router-link>
            </li>
            <li class="mr-3">
                <router-link id="inbox-item-2" class="icon-link" v-b-tooltip.hover :title="$t('inbox')"
                             :to="{name:'Inbox'}">
                    <i class="fal fa-lg fa-envelope position-relative">
                        <div class="new-event-badge p-1 badge badge-danger rounded-circle font-weight-normal"
                             v-if="nbMessages>0">
                            {{ nbMessages }}
                        </div>
                    </i>
                </router-link>
            </li>

            <li class="mr-md-3">
                <b-dropdown right variant="link" menu-class="w-100 shadow border-0" toggle-class="text-decoration-none"
                            no-caret>

                    <template #button-content>
                        <div class="account-link">
                            <div class="bg-dark-grey text-blue circle-account mr-2">
                                {{ userInitials }}
                            </div>
<!--                            <div class="text-left"><strong>{{ user.firstname+' '+user.lastname }}</strong> <i class="fas fa-caret-down"></i><br><span-->
<!--                                class="text-secondary">{{ user.region?user.region.name:'' }}</span></div>-->
                        </div>
                    </template>

                    <b-dropdown-item href="#">
                        <router-link :to="{ name: 'userProfile' }">
                            <i class="fal fa-fw fa-cog mr-1"></i>{{ $t('parameters') }}
                        </router-link>
                    </b-dropdown-item>
                    <b-dropdown-divider></b-dropdown-divider>
                    <b-dropdown-item @click.prevent="logout">
                        <i class="fal fa-sign-out fa-fw mr-1"></i>{{ $t('log_out') }}
                    </b-dropdown-item>
                </b-dropdown>
            </li>
        </ul>
    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";

export default {
    name: "TopNav",
    computed: {
        user() {
            return this.$auth.user()
        },
        ...mapGetters('messages', ['nbMessages']),
        ...mapGetters('researches', ['total', 'isCountLoaded']),
        ...mapGetters('userModels', ['allUserModels', 'isUserModelLoaded']),
        userInitials() {
            if (!this.user) {
                return ''
            }
            const initials = (this.user.firstname && this.user.firstname[0]) + (this.user.lastname && this.user.lastname[0])
            if (!initials)
                return null
            return initials.toUpperCase()
        },
    },
    created() {
        this.fetchAllResearchesCount();
        this.fetchAllMessagesCount();
        this.fetchAllUserModels();
    },
    methods:{
        logout() {
            this.$auth.logout()
        },
        ...mapActions('researches', ['fetchAllResearchesCount']),
        ...mapActions('messages', ['fetchAllMessagesCount']),
        ...mapActions('userModels', ['newUserModel', 'fetchAllUserModels'])
    }
}
</script>

<style scoped>

</style>
