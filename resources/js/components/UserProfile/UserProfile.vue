<template>
    <div class="user-profil">

        <span class="d-block d-md-none">
            <top-nav></top-nav>
        </span>

        <div class="bg-white shadow container-fluid py-4 px-4 zindex-top">

            <div class="d-flex align-items-center justify-content-between">
                <h1 class="h2 text-blue mb-0 pt-3">{{ $t('my_account') }}</h1>
                <span class="d-none d-md-block">
                    <top-nav></top-nav>
                </span>
            </div>

        </div>

        <div class="user-profil-wrapper">
            <div class="user-profil-nav border-right bg-white">
                <ul class="list-unstyled pt-2">
                    <li class="border-bottom">
                        <localized-link :to="{ name: 'profilePersonalEdit' }" class="p-4 d-block"
                                        :class="$route.name === 'profilePersonalEdit' ? 'router-link-active' : ''">
                            <i class="fal fa-lg fa-user-alt fa-fw mr-2"></i>
                            {{ $t('personal_details') }}
                        </localized-link>
                    </li>
                    <li class="border-bottom">
                        <localized-link :to="{ name: 'profileMerchantEdit' }" class="p-4 d-block"
                                        :class="$route.name === 'profileMerchantEdit' ? 'router-link-active' : ''">
                            <i class="fal fa-lg fa-store fa-fw mr-2"></i>
                            {{ $t('vendor_parameters') }}
                        </localized-link>
                    </li>
                    <li class="border-bottom">
                        <localized-link :to="{ name: 'profilePasswordEdit' }" class="p-4 d-block"
                                        :class="$route.name === 'profilePasswordEdit' ? 'router-link-active' : ''">
                            <i class="fal fa-lg fa-lock-alt fa-fw mr-2"></i>
                            {{ $t('password_security') }}
                        </localized-link>
                    </li>
                </ul>
            </div>
            <div class="user-profil-content p-4">
                <router-view></router-view>
            </div>
        </div>

    </div>
</template>

<script>

const TopNav = () => import(/* webpackChunkName: "group-dashboard" */"../Shared/TopNav");

export default {
    name: "UserProfile",
    components: {TopNav},
    created() {
        console.log(this.$route.name)
    }
}
</script>
