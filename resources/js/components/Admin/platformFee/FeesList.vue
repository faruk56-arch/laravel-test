<template>

    <div class="fees-list">
        <div class="d-flex justify-content-between mb-4" v-if="$route.name === 'platformFee'">
            <h3 class="text-blue">List Fees</h3>
            <router-link :to="{name:'addFee'}" class="btn btn-sm bg-gold" tag="a">Add new fees</router-link>
        </div>

        <div>
            <vue-good-table :columns="columns" :rows="rows" compactMode
                            :search-options="{enabled: true}"
                            :pagination-options="{enabled: true, mode: 'records', perPage: 10}">
                <template slot="table-row" slot-scope="props">
                    <template v-if="props.column.field === 'id'">
                        {{ props.row.id }}
                    </template>

                    <template v-if="props.column.field === 'fee_type'">
                        {{ props.row.fee_type }}
                    </template>

                    <template v-if="props.column.field === 'ids'">
                        <span v-if="props.row.ids">{{ props.row.ids }}</span>
                        <span v-else>-</span>
                    </template>


                    <template v-if="props.column.field === 'percent'">
                        {{ props.row.percent }} %
                    </template>

                    <template v-if="props.column.field === 'user_type'">
                        {{ props.row.user_type }}
                    </template>

                    <template v-if="props.column.field === 'piece_status'">
                        <span v-if="props.row.piece_status">
                            {{ props.row.piece_status }}
                        </span>
                        <span v-else>
                            -
                        </span>
                    </template>

                    <template v-if="props.column.field === 'min_price'">

                        <span v-if="props.row.min_price">
                            {{ props.row.min_price }} €
                        </span>
                        <span v-else>
                            -
                        </span>
                    </template>
                    <template v-if="props.column.field === 'max_price'">
                        <span v-if="props.row.max_price">
                            {{ props.row.max_price }} €
                        </span>
                        <span v-else>-</span>

                    </template>


                    <template v-if="props.column.field === 'options'">
                        <router-link class="btn btn-xs btn-primary"
                                     :to="{ name: 'EditFee', params:{id:props.row.id} }">
                            Edit
                        </router-link>

                        <button class="btn btn-xs bg-gold" @click="deleteFee(props.row.id)">Delete</button>
                    </template>
                </template>
            </vue-good-table>
        </div>
    </div>
</template>

<script>
export default {
    name: "fees-list",
    data() {
        return {
            fees: [],
            columns: [
                {
                    label: 'ID',
                    field: 'id',
                    type: 'number',
                    tdClass: 'd-none',
                    thClass: 'd-none',
                },
                {
                    label: 'Fees Type',
                    field: 'fee_type',
                    tdClass: 'text-center text-capitalize',
                    thClass: 'text-center',
                },
                {
                    label: 'ID',
                    field: 'ids',
                    tdClass: 'text-center',
                    thClass: 'text-center',
                },
                {
                    label: 'Percentage',
                    field: 'percent',
                    type: 'number',
                    tdClass: 'text-center',
                    thClass: 'text-center',
                },
                {
                    label: 'User type',
                    field: 'user_type',
                    tdClass: 'text-center',
                    thClass: 'text-center',
                },
                {
                    label: 'Piece status',
                    field: 'piece_status',
                    type: 'number',
                    tdClass: 'text-center',
                    thClass: 'text-center',
                },
                {
                    label: 'Min price',
                    field: 'min_price',
                    type: 'number',
                    tdClass: 'text-center',
                    thClass: 'text-center',
                },
                {
                    label: 'Max price',
                    field: 'max_price',
                    type: 'number',
                    tdClass: 'text-center',
                    thClass: 'text-center',
                },
                {
                    label: 'Options',
                    field: 'options',
                    tdClass: 'text-center',
                    thClass: 'text-center',
                }
            ],
            rows: [],
            styleClass: "vgt-table striped"
        }
    },

    methods: {
        getFees: function () {
            axios.get('/all-fees').then(function (res) {
                this.rows = res.data.fees;
            }.bind(this));
        },
        splitJoin(theText) {
            return theText.split(',');
        },

        deleteFee(productId) {
            this.axios.post("delete-fee/" + productId, {"_method": "DELETE"})

                .then(() => {
                    this.$router.go(this.$router.currentRoute);
                })

                .catch((err) => {
                    if (err.response.status === 422) {
                        this.errors = err.response.data;
                    }
                })
        }
    },
    created: function () {
        this.getFees()
    }
}
</script>

<style scoped>

</style>
