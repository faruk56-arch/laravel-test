<template>
    <div class="research-alert">
        <div class="bg-white shadow container-fluid py-lg-4 py-3 px-4 mb-4">

            <div class="d-flex align-items-center justify-content-between">
                <h1 class="h2 text-blue mb-0 pt-lg-3 pt-2">All orders</h1>
                <top-nav></top-nav>
            </div>
        </div>

        <div>
            <vue-good-table :columns="columns" :rows="rows" compactMode
                            :search-options="{enabled: true}"
                            :pagination-options="{enabled: true, mode: 'records', perPage: 5}">
                <template slot="table-row" slot-scope="props">
                        <span v-if="props.column.field === 'id'">
                            <router-link class="btn btn-xs bg-gold"
                                         :to="{ name: 'showProduct', params:{id:props.row.id} }">
                                {{ props.row.id }}
                            </router-link>
                        </span>
                </template>
            </vue-good-table>
        </div>
    </div>
</template>

<script>
const TopNav = () => import(/* webpackChunkName: "group-dashboard" */"../../Shared/TopNav");
export default {
    name: "Index",
    components: {TopNav},
    data() {
        return {
            columns: [
                {
                    label: 'Id',
                    field: 'id',
                    type: 'number',
                    tdClass: 'text-center',
                    thClass: 'text-center',
                },
                {
                    label: 'First Name',
                    field: 'firstname',
                    tdClass: 'text-center',
                    thClass: 'text-center',
                },
                {
                    label: 'Last Name',
                    field: 'lastname',
                    tdClass: 'text-center',
                    thClass: 'text-center',
                },
                {
                    label: 'Address',
                    field: 'address',
                    tdClass: 'text-center',
                    thClass: 'text-center',
                },
                {
                    label: 'Product Price',
                    field: 'price',
                    formatFn: this.formatFn,
                    type: 'number',
                    tdClass: 'text-center',
                    thClass: 'text-center',
                }
                ,
                {
                    label: 'Platform Commission',
                    field: 'platform_commission',
                    type: 'number',
                    formatFn: this.PlatformCommission,
                    tdClass: 'text-center',
                    thClass: 'text-center',
                }
                ,
                {
                    label: 'Seller Earnings',
                    field: 'your_earnings',
                    type: 'number',
                    formatFn: this.formatFn,
                    tdClass: 'text-center',
                    thClass: 'text-center',
                }
                ,
                {
                    label: 'Platform Earnings',
                    field: 'platform_earnings',
                    type: 'number',
                    formatFn: this.formatFn,
                    tdClass: 'text-center',
                    thClass: 'text-center',
                }
            ],
            rows: [],
            styleClass: "vgt-table striped"
        }
    },

    methods: {
        showBlogs: function () {
            axios.get('/all-orders').then(function (res) {
                this.rows = res.data.orders;
            }.bind(this));
        },
        formatFn: function (value) {
            if (value > 0) {
                return value + ' €';
            } else {
                return '-';
            }
        },
        PlatformCommission: function (value) {
            if (value > 0) {
                return value + ' %';
            } else {
                return '-';
            }
        },
    },
    created: function () {
        this.showBlogs()
    }
}
</script>

<style scoped>

</style>
