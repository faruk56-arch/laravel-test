<template>
    <div class="research-alert">
        <div class="bg-white shadow container-fluid py-lg-4 py-3 px-4 mb-4">

            <div class="d-flex align-items-center justify-content-between">
                <h1 class="h2 text-blue mb-0 pt-lg-3 pt-2">Détails de la commande</h1>
                <top-nav></top-nav>
            </div>
        </div>

        <div class="container-fluid">
            <div class="col-md-12 p-0 mb-4">
                <div class="card">

                    <div class="card-header bg-gold text-white">
                        Distribution of profits on this order
                    </div>

                    <div class="card-body items">
                        <div class="item">
                            <span> Product price </span>
                            <span class="val">{{ order.price }} €</span>
                        </div>
                        <div class="item">
                            <span>Platform fees percent </span>
                            <span class="val">{{ order.platform_commission}} %</span>
                        </div>
                        <div class="item">
                            <span> Seller earnings </span>
                            <span class="val">{{ order.your_earnings }} €</span>
                        </div>
                        <div class="item">
                            <span> Platform earnings </span>
                            <span class="val">{{ order.platform_earnings }} €</span>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-gold text-white">
                            Order client details
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>Part name : </strong> <span>{{ order.id }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Price : </strong> <span>{{ order.firstname }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>description : </strong> <span>{{ order.lastname }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>created_at : </strong> <span>{{ order.email }}</span>
                            </li>

                            <li class="list-group-item">
                                <strong>Part name : </strong> <span>{{ order.address }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Price : </strong> <span>{{ order.country.name }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>description : </strong> <span>{{ order.region.name }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>created_at : </strong> <span>{{ order.city }}</span>
                            </li>

                            <li class="list-group-item">
                                <strong>Part name : </strong> <span>{{ order.phone }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Price : </strong> <span>{{ order.zip }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>description : </strong> <span>{{ order.status }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>created_at : </strong> <span>{{ order.created_at }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-gold text-white">
                            Order product details
                        </div>

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>Product name : </strong>
                                <span>{{ order.product.id }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Part Category : </strong>
                                <span>{{ order.product.original_name }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Part created_at : </strong>
                                <span>{{ order.product.original_description }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Part created_at : </strong>
                                <span>{{ order.product.manufacturer }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Part created_at : </strong>
                                <span>{{ order.product.reference }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Part created_at : </strong>
                                <span>{{ order.product.part.name }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Part created_at : </strong>
                                <span>{{ order.product.created_at }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Part created_at : </strong>
                                <span>{{ order.product.weight }} (Kg)</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Part created_at : </strong>
                                <span>{{ order.product.modele[0].name }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Part created_at : </strong>
                                <span>{{ order.product.modele[0].brand.name }}</span>
                            </li>

                        </ul>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card">

                        <div class="card-header bg-gold text-white">
                            Order merchant details
                        </div>

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>Part created_at : </strong>
                                <span>{{ order.product.merchant.merchant_name }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Part created_at : </strong>
                                <span>{{ order.product.merchant.merchant_address }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Part created_at : </strong>
                                <span>{{ order.product.merchant.merchant_city }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Part created_at : </strong>
                                <span>{{ order.product.merchant.merchant_zip }}</span>
                            </li>

                            <li class="list-group-item">
                                <strong>Part created_at : </strong>
                                <span>{{ order.product.merchant.merchant_type }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Part created_at : </strong>
                                <span>{{ order.product.merchant.created_at }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Part created_at : </strong>
                                <span>{{ order.product.merchant.country.name }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Part created_at : </strong>
                                <span>{{ order.product.merchant.region.name }}</span>
                            </li>

                            <li class="list-group-item">
                                <strong>Part created_at : </strong>
                                <span>{{ order.product.merchant.phone }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

    </div>
</template>

<style>
.list-group-flush > .list-group-item {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}
.card-body.items {
    display: flex;
    justify-content: space-around;
}

.card-body.items .item {
    display: flex;
    justify-content: flex-start;
    flex-direction: column;
    align-items: center;
    text-transform: uppercase;
}

.card-body.items .item span.val {
    font-size: 1.5em;
    font-weight: bold;
}
</style>

<script>

const TopNav = () => import(/* webpackChunkName: "group-dashboard" */"../../Shared/TopNav");
import {mapActions, mapGetters} from "vuex";

export default {
    //components: {OrdersItem, TopNav},
    components: {TopNav},
    data() {
        return {
            order: null,
        }
    },
    created() {
        this.fetchOrder(this.$route.params.id).then(res => {
            this.order = res.data.order
        }).catch(err => this.errored = true)
    },
    methods: {
        ...mapActions('orders', ['fetchOrder']),
    },
    computed: {
        ...mapGetters('orders', ['allOrders', 'isOrdersLoaded']),
    }
}
</script>
