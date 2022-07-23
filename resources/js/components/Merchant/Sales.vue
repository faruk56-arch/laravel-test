<template>
    <div class="sales">

        <div class="bg-white shadow container-fluid py-4 px-4 mb-4">
            <h1 class="h2 text-blue mb-0 pt-3">{{ $t('mes_ventes') }}</h1>
        </div>

        <div v-if="isMerchantSalesLoaded&&allSales.length>0" class="container-fluid">

            <table class="table">
                <thead>
                <tr>
                    <th scope="col"><input type="checkbox"></th>
                    <th scope="col">{{ $t('reference') }}</th>
                    <th scope="col">{{ $t('date') }}</th>
                    <th scope="col">{{ $t('product') }}</th>
                    <th scope="col">{{ $t('amount') }}</th>
                    <th scope="col">{{ $t('state') }}</th>
                    <th scope="col">{{ $t('status') }}</th>
                    <th scope="col">{{ $t('option') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="sale in allSales">
                    <th scope="row"><input type="checkbox"></th>
                    <th scope="row">{{sale.product.reference}}</th>
                    <td>{{sale.updated_at}}</td>
                    <td>{{translation('name', sale.product.part)}} ({{sale.product.modele[0].brand.name}} {{sale.product.modele[0].name}})</td>
                    <td>{{sale.product.price}} {{sale.product.currency}}</td>
                    <td>{{sale.product.condition.label}}</td>
                    <td><span class="badge px-2" :class="sale.status.class">{{sale.status.label}}</span></td>
                    <td>


                        <div>
                            <b-dropdown right size="xs">
                                <template v-slot:button-content>
                                    {{ $t('options') }}
                                </template>
                                <b-dropdown-item v-if="sale.status.name === 'pending' || sale.status.name === 'refused'">
                                    <i class="fas fa-check-circle"></i> {{ $t('allow_sale') }}
                                </b-dropdown-item>

                                <b-dropdown-item v-if="sale.status.name === 'pending'">
                                    <i class="fas fa-do-not-enter"></i> {{ $t('refuse_sale') }}
                                </b-dropdown-item>

                                <b-dropdown-item v-if="sale.status.name === 'payment'">
                                    <i class="far fa-credit-card"></i> {{ $t('paiement_accept') }}
                                </b-dropdown-item>

                                <b-dropdown-item v-if="sale.status.name === 'accepted'">
                                    <i class="fas fa-truck"></i> {{ $t('order_sent') }}
                                </b-dropdown-item>

                                <b-dropdown-item v-if="sale.status.name === 'shipped'">
                                    <i class="fas fa-truck-loading"></i> {{ $t('commande_livr') }}
                                </b-dropdown-item>

                                <b-dropdown-item v-if="sale.status.name === 'delivered'">
                                    <i class="far fa-undo-alt"></i> {{ $t('reimburse_partiel') }}
                                </b-dropdown-item>

                                <b-dropdown-item v-if="sale.status.name === 'delivered'">
                                    <i class="far fa-undo-alt"></i> {{ $t('reimburse_total') }}
                                </b-dropdown-item>
                            </b-dropdown>
                        </div>


                    </td>
                </tr>
                </tbody>
            </table>


        </div>
        <div v-else-if="isMerchantSalesLoaded">{{ $t('vous_navez_pas_encore_de_vente') }}</div>
        <div v-else><span class="loader"></span></div>

    </div>
</template>
<script>
    import {mapActions, mapGetters} from "vuex";

    export default {
        data() {
            return {
                name: 'sales',
            }
        },
        created() {
            this.fetchAllSales()
        },
        methods: {
            ...mapActions('sales', ['fetchAllSales']),
        },
        computed: {
            ...mapGetters('sales', ['allSales', 'isMerchantSalesLoaded']),
        },
        watch: {}
    }
</script>
