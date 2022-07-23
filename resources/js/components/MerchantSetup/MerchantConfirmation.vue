<template>
    <div class="merchant-confirmation row">

		<div class="col-12 py-5 mt-3">
			<div class="h1 text-center">
				<i class="far fa-check text-gold"></i>
			</div>
			<h1 class="h2 h2-carter font-family-reset text-center text-blue">
				<template v-if="!$auth.user().obvy_email"> {{ $t('vendor_account_ready') }} </template>
				<template v-else> {{ $t('account_secured_obvy') }} </template>
			</h1>
			<h3 class="text-center h3-carter text-blue pt-2 font-weight-light">
				<template v-if="!$auth.user().obvy_email"> {{ $t('and_now') }} </template>
				<template v-else> {{ $t('can_sell_products') }} </template>

			</h3>
		</div>


		<div class="col-md-10 offset-md-1">
			<div class="row">
				<div class="col-md-4 mb-4 merchant-confirmation-item" v-if="!$auth.user().obvy_email">
					<div class="bg-white shadow rounded px-3 py-4">
						<div class="h1 text-center text-gold position-relative pb-3">
							<span class="h3 font-family-reset position-absolute w-100 text-center l-0">€</span>
							<i class="fal fa-hand-holding"></i>
						</div>
						<h4 class="text-center text-blue pb-2">{{ $t('accept_paiements') }}</h4>
						<p class="text-center">{{ $t('sell_with_obvy') }}</p>
						<router-link class="btn btn-sm btn-full btn-center bg-gold" :to="{name:'ObvyConnection'}">{{ $t('secure_my_account') }}</router-link>
					</div>
				</div>
				<div class="col-md-4 mb-4 merchant-confirmation-item">
					<div class="bg-white shadow rounded px-3 py-4">
						<div class="h1 text-center pb-3">
							<i class="fal fa-bullhorn text-gold"></i>
						</div>
						<h4 class="text-center text-blue pb-2">{{ $t('go_further') }}</h4>
						<p class="text-center">{{ $t('tell_part_category') }}</p>
						<router-link class="btn btn-sm btn-full btn-center bg-gold" :to="{name:'addAlert'}">{{ $t('create_simplified_announce') }}</router-link>
					</div>
				</div>
				<div class="col-md-4 mb-4 merchant-confirmation-item" :class="{'offset-md-2':$auth.user().obvy_email}">
					<div class="bg-white shadow rounded px-3 py-4">
						<div class="h1 text-center pb-3">
							<i class="fal fa-store text-gold"></i>
						</div>
						<h4 class="text-center text-blue pb-2">{{ $t('add_product') }}</h4>
						<p class="text-center">{{ $t('create_first_product') }}</p>
						<router-link class="btn btn-sm btn-full btn-center bg-gold" :to="{name:'addProduct'}">{{ $t('add_product') }}</router-link>
					</div>
				</div>
			</div>

			<div class="col-12 text-center">
				<router-link :to="{name:'dashboard'}" tag="a" class="basic text-bold-dark-grey" href="">{{ $t('complete_later') }} <i class="fal fa-long-arrow-right"></i></router-link>
			</div>

        </div>

    </div>
</template>

<script>
    export default {
        name: "MerchantConfirmation",
        data() {
            return {
            }
        },
    }
</script>

<style scoped>

</style>
