<template>
    <div class="w-100">
        <button class="btn btn-lg btn-obvy-pay border-0 py-2 w-100" v-b-modal="'modal-obvy'+product.id">Achat sécurisé avec
            <img src="/images/logo/logo_obvy_white.svg">
        </button>
        <b-modal :id="'modal-obvy'+product.id" hide-footer centered title="Avant de procéder à l'achat, retenez les informations ci-dessous" content-class="rounded border-0 p-2 shadow-lg" header-class="border-0 pb-0">
            <obvy-modal class="my-2" @submitted="onClick" :product="product" :negociableCategory="negociableCategory"/>
        </b-modal>
    </div>
</template>
<script>
const ObvyModal = () => import("../../Shared/ObvyModal");

export default {
    name: 'ObvyPaiement',
    props: {
        negociableCategory: {},
        product: {},
    },
    components: {ObvyModal},
    data() {
        return {

        }
    },
    methods: {
        onClick(e) {
            this.$emit('setProductState');
            this.$emit('setSale');
        }
    },
}
</script>
