<template>

    <div class="add-fee">

        <div class="card py-4 px-4 mb-4">

            <router-link class="pl-1 pr-3" :to="{ name: 'platformFee' }">
                <i class="fal fa-long-arrow-left pr-1"></i> {{ $t('back') }}
            </router-link>


            <template v-if="errors.length !== 0">
                <div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
                    <strong>{{ errors.message }}</strong>
                    <div v-for="error in errors.errors" class="ml-4">
                        <p class="m-0">{{ error[0] }}</p>
                    </div>
                </div>
            </template>

            <h3 class="mb-4 mt-2 text-blue">Add new fees</h3>
            <form @submit.prevent="addNewFee">
                <div class="row">

                    <div class="col-md-6 form-group form-sm">
                        <label for="type">Fee Type <sup class="text-danger">*</sup></label>
                        <select v-model="fee.selectedType" id="type" @change="onChange($event)" class="form-control">
                            <option disabled value="">Please select one</option>
                            <option value="products">Products</option>
                            <option value="merchants">Merchants</option>
                            <option value="all">All</option>
                        </select>
                    </div>

                    <template v-if="fee.selectedType === 'products'">
                        <div class="col-md-6 form-group form-sm">
                            <label for="product">Select Product <sup class="text-danger">*</sup></label>
                            <select v-model="fee.selectedId" id="product" class="form-control">
                                <option disabled value="">Please select one</option>
                                <option v-for="product in products" v-bind:value="product.id">
                                    {{ product.name }}
                                </option>
                            </select>
                        </div>
                    </template>

                    <template v-else-if="fee.selectedType === 'merchants'">
                        <div class="col-md-6 form-group form-sm">
                            <label for="merchant">Select Merchant <sup class="text-danger">*</sup></label>
                            <select v-model="fee.selectedId" id="merchant" class="form-control">
                                <option disabled value="">Please select one</option>
                                <option v-for="merchant in merchants" v-bind:value="merchant.id">
                                    {{ merchant.merchant_name }}
                                </option>
                            </select>
                        </div>
                    </template>

                    <div class="col-md-6 form-group form-sm">
                        <label for="user_type">User Type <sup class="text-danger">*</sup></label>
                        <select v-model="fee.user_type" id="user_type" class="form-control">
                            <option disabled value="">Please select one</option>
                            <option value="acheteur">Acheteur</option>
                            <option value="vendeur">Vendeur</option>
                        </select>
                    </div>


                    <div class="col-md-6 form-group form-sm">
                        <label for="filter">Price & Piece status Filter </label>
                        <select v-model="fee.filter_type" id="filter" class="form-control">
                            <option value="none">None one</option>
                            <option value="price">Price</option>
                            <option value="piece_status">Piece status</option>
                            <option value="price_piece_status">Price & Piece status</option>
                        </select>
                    </div>


                    <template v-if="fee.filter_type === 'piece_status' || fee.filter_type === 'price_piece_status'">
                        <div class="col-md-6 form-group form-sm">
                            <label for="piece_status">Piece Status <sup class="text-danger">*</sup></label>
                            <select v-model="fee.piece_status" id="piece_status" class="form-control">
                                <option disabled value="">Please select one</option>
                                <option v-for="status in piecesStatus" v-bind:value="status.id">
                                    {{ status.label }}
                                </option>
                            </select>
                        </div>
                    </template>


                    <template v-if="fee.filter_type === 'price' ||fee. filter_type === 'price_piece_status'">
                        <div class="col-md-6 form-group form-sm">
                            <label for="min_price">Min Price <sup class="text-danger">*</sup></label>
                            <input id="min_price" class="form-control" v-model="fee.min_price" type="number">
                        </div>

                        <div class="col-md-6 form-group form-sm">
                            <label for="max_price">Max Price <sup class="text-danger">*</sup></label>
                            <input id="max_price" class="form-control" v-model="fee.max_price" type="number">
                        </div>
                    </template>

                    <div class="col-md-6 form-group form-sm">
                        <label for="percent">Percentage <sup class="text-danger">*</sup></label>
                        <input id="percent" class="form-control" v-model="fee.percent" type="text">
                    </div>

                    <div class="col-md-12 form-group form-sm">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>


                </div>

            </form>

        </div>

    </div>

</template>

<script>
export default {
    data() {
        return {
            products: null,
            merchants: null,
            piecesStatus: null,
            check: false,
            errors: [],
            fee: {
                filter_type: "none",
            },
        }
    },

    methods: {
        getProducts: function () {
            axios.get('/all-products').then(function (res) {
                this.products = res.data.products;
            }.bind(this));
        },
        getMerchants: function () {
            axios.get('/all-merchants').then(function (res) {
                this.merchants = res.data.merchants;
            }.bind(this));
        },
        getPiecesStatus: function () {
            axios.get('/all-pieces-status').then(function (res) {
                this.piecesStatus = res.data;
            }.bind(this));
        },
        addNewFee: function () {
            this.axios.post('/add-fee',
                {
                    "fee_type": this.fee.selectedType,
                    "ids": this.fee.selectedId,
                    "percent": this.fee.percent,
                    "user_type": this.fee.user_type,
                    "piece_status": this.fee.piece_status,
                    "min_price": this.fee.min_price,
                    "max_price": this.fee.max_price,
                    "filter_type": this.fee.filter_type,
                }
            )
                .then(response => (
                    this.$router.push({name: 'platformFee'})
                ))

                .catch((err) => {
                    if (err.response.status === 422) {

                        this.errors = err.response.data;
                        /*
                        this.errors.push(err.response.data.errors);



                        $.each(err.response.data.errors, function (key, value) {
                            if (key === 'id') {
                                this.emailError = err.response.data.errors.email[0]; //NB: emailError is registered in Vue data
                            }
                            if (key === 'ids') {
                                this.passwordError = err.response.data.errors.password[0]; //NB: passwordError is registered in Vue data
                            }
                        });
*/


                    }
                })
        },
        onChange(event) {
            //this.fee.selectedId = null;
        }
    },
    created: function () {
        this.getProducts()
        this.getMerchants()
        this.getPiecesStatus()
    }
}
</script>

<style scoped>

</style>
