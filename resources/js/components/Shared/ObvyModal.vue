<template>
    <div class="form-sm obvy-modal">

        <!-- <div class="alert alert-warning d-flex">
            <i class="fal fa-exclamation-circle pt-1 mr-2"></i>
            Avant de procéder à l'achat, retenez les informations ci-dessus.
        </div>

        <p class="lead text-blue">
            Avant de procéder à l'achat, retenez les informations ci-dessus.
        </p> -->


        <table class="table text-blue table-striped py-3">

            <tbody>
                <tr>
                    <td class="border-0">
                        <strong class="d-inline-block mr-2">Poids du produit </strong>
                        {{ product.weight }} Kg
                    </td>
                </tr>
                <tr>
                    <td class="border-0" v-if="product.width&&product.height&&product.depth">
                        <strong class="d-inline-block mr-2">Dimensions du produit </strong>
                        {{ product.width }}cm x {{ product.height }}cm x {{ product.depth }}cm <small class="text-secondary"><i>(L x H x P)</i></small>
                    </td>
                </tr>
            </tbody>
        </table>

        <form :action="obvyUrl"
              method="post" accept-charset="UTF-8" target="_blank">
            <!-- Payment variables. -->
            <input type="hidden" name="amount" :value="product.price">
            <input type="hidden" name="currency_code" value="EUR">
            <input type="hidden" name="locale" value="FR">

            <!-- Item variables. -->
            <input type="hidden" name="item_name" :value="translation('name', product)">
            <input type="hidden" name="item_number" :value="product.id + ',' + product.pivot.research_id">
            <input type="hidden" name="item_description" :value="translation('description', product)">
            <input type="hidden" name="item_category" :value="negociableCategory">
            <input type="hidden" name="item_picture" :value="url + product.img[0]">

            <!-- Seller variables. -->
            <input type="hidden" name="seller_id" :value="product.merchant.id">
            <input type="hidden" name="seller_email" :value="product.merchant.user[0].obvy_email?product.merchant.user[0].obvy_email:product.merchant.user[0].email">

            <!-- Buyer variables. -->
            <input type="hidden" name="buyer_id" :value="$auth.user().id">
            <input type="hidden" name="buyer_first_name" :value="$auth.user().firstname">
            <input type="hidden" name="buyer_last_name" :value="$auth.user().lastname">
            <input type="hidden" name="buyer_email" :value="$auth.user().email">

            <!-- Url  -->
            <input type="hidden" name="url_return" :value="url + '/finder/research/'+product.pivot.research_id">
            <input type="hidden" name="url_cancel" :value="url + '/finder/research/'+product.pivot.research_id">

            <!-- Submit button  -->

            <button type='submit' class="btn btn-lg btn-obvy-pay border-0 py-2 w-100" @click="submit">
                Continuer avec <img src="/images/logo/logo_obvy_white.svg">
            </button>
            <router-link class='text-center  mt-2 d-block' target="_blank" :to="{ name: 'ObvyConnection' }">
                <i class="fal fa-question-circle"></i>
                En savoir plus sur Obvy
            </router-link>

            <!-- <input type="image" name="submit" src="https://cdn.obvy-app.com/partners/fr/payb.svg" @click="setSale; setProductState('interested')"> -->
        </form>
    </div>
</template>

<script>
import {mapActions} from "vuex";

/**
 * @emits submitted the message was entered by the user, contains message as payload
 */
export default {
    name: "NotFoundForm",
    props:['product','negociableCategory'],
    data() {
        return {
            url: process.env.MIX_APP_URL,
            api: process.env.MIX_OBVY_APIKEY,
            env: process.env.NODE_ENV,
        }
    },
    methods: {
        ...mapActions('messages', ['newMessage']),
        submit() {
            this.$emit('submitted');
        }
    },
    computed: {
        obvyUrl() {
            let sandbox = process.env.NODE_ENV === 'production' ? '' : 'sandbox'
            return 'https://api' + sandbox + 'partner.obvy-app.com/api/payment?api_key=' +
                process.env.MIX_OBVY_APIKEY
        }
    }
}
</script>

<style scoped>

</style>
