const state = {
    sales: [],
    salesUser: [],
    loadedUserSales:false,
    loadedMerchantSales:false,
}

const getters = {
    allSales: state => state.sales,
    allSalesUser: state => state.salesUser,
    getSale: (state) => (id) => {
        return state.sales.find((sale) => sale.id === id);
    },
    getSaleUser: (state) => (id) => {
        return state.sales.find((sale) => sale.id === id);
    },
    getLatestSales: state => {
        let latest = [...state.sales];
        return latest.splice(0, 4)
    },
    isUserSalesLoaded: state => state.loadedUserSales,
    isMerchantSalesLoaded: state => state.loadedMerchantSales,

}

const actions = {
    fetchAllSales({commit,getters}) {
        if (!getters.isMerchantSalesLoaded) {
            return axios.get('/merchant/' + Vue.auth.user().merchant_id + '/sales').then((res) => {
                commit('SET_SALES', res.data.data)
                commit('SET_LOADED_MERCHANT', true)
            })
        }
    },
    fetchAllUserSales({commit,getters}) {
        if (!getters.isUserSalesLoaded) {
            return axios.get('/user/' + Vue.auth.user().id + '/sales').then((res) => {
                commit('SET_SALES_USER', res.data.data)
                commit('SET_LOADED_USER', true)
            })
        }
    },
    updateSale({commit}, [research_id, sale_id, params]) {
        return axios.put('/user/' + Vue.auth.user().id + '/research/' + research_id + '/products/' + sale_id, {...params}).then(() => {
            commit('UPDATE_SALE', [sale_id, params])
        })
    }
};

const mutations = {
    SET_SALES: (state, sales) => (state.sales = sales),
    SET_SALES_USER: (state, sales) => (state.salesUser = sales),
    SET_LOADED_USER: (state, boolean) => state.loadedUserSales = boolean,
    SET_LOADED_MERCHANT: (state, boolean) => state.loadedMerchantSales = boolean,
    UPDATE_SALE: (state, [id, params]) => {
        let sale = state.sales.find((el) => el.product_id === id);
        Object.assign(sale, params)
    },
    NEW_SALE: (state, sale) => state.sales.unshift(sale),
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
