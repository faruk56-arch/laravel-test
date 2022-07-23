const state = {
    merchant: [],
}

const getters = {
    merchant: state => state.merchant,
}

const actions = {
    fetchMerchant({ commit }) {
        return axios.get('/user/'+Vue.auth.user().id+'/merchant').then((res) => commit('SET_MERCHANT', res.data.data))
    },
    updateMerchant({ commit },form) {
        return axios.put('/merchant/'+Vue.auth.user().merchant_id,form)
    },
    newMerchant({ commit },form) {
        return axios.post('/merchant',form)
    },
}

const mutations = {
    SET_MERCHANT: (state, merchant) => (state.merchant = merchant),
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
