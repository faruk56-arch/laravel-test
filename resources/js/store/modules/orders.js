const state = {
    orders: [],
    loaded: false
};

const getters = {
    allOrders: state => state.orders,
    getOrder: (state) => (id) => {
        return state.orders.find((order) => order.id === id);
    },
    isOrdersLoaded: state => state.loaded,
    getLatestOrders: state => {
        let latest = [...state.orders];
        return latest.splice(0, 4)
    },
    isDismissedFunc: state => (order_id, research_id) => {
        let order = state.orders.find(el => el.id === order_id);
        if (order) {
            let research = order.researches.find(el => el.id === research_id);
            return research && research.pivot && research.pivot.dismiss
        }
    }

};

const actions = {


    /*
       fetchAllOrders({commit, getters}) {
            if (!getters.isOrdersLoaded) {
                return axios.get('/orders').then((res) => {
                    commit('SET_ORDERS', res.data.data)
                    commit('SET_LOADED', true)
                })
            }
        },
     */
    fetchAllOrders({commit, getters}) {
        if (!getters.isOrdersLoaded) {
            return axios.get('orders/merchant/' + Vue.auth.user().merchant.id).then((res) => {
                commit('SET_ORDERS', res.data.data)
                commit('SET_LOADED', true)
            })
        }
    },

    fetchAdminAllOrders({commit, getters}) {
        if (!getters.isOrdersLoaded) {
            return axios.get('all-orders').then((res) => {
                commit('SET_ORDERS', res.data.data)
                commit('SET_LOADED', true)
            })
        }
    },

    fetchOrder({commit}, id) {
        return axios.get('/order/' + id)
    },
    updateActiveState({commit}, [id, newState]) {
        return axios.put('/merchant/' + Vue.auth.user().merchant_id + '/researchOrder/' + id, {active: Boolean(newState)}).then(res => commit('UPDATE_STATE', [id, newState]))
    },
    newOrder({commit}, [models, categories, parts]) {
        return axios.post(
            '/merchant/' + Vue.auth.user().merchant_id + '/researchOrder',
            {
                models,
                categories,
                parts,
            }
        ).then(res => commit('NEW_ORDER', res.data))
    },
    removeOrder({commit}, order) {
        axios.delete('/merchant/' + Vue.auth.user().merchant_id + '/researchOrder/' + order.id).then(() => {
        })
        commit('REMOVE_ORDER', order);
        commit('notifications/SET_LOADED', false, {root: true});
    },
    toggleResearchDismiss({commit}, [order_id, research_id, bool = null]) {
        axios.put('/merchant/' + Vue.auth.user().merchant_id + '/researchOrder/' + order_id, {
            dismiss: research_id,
            bool
        }).then(
            res => commit(
                'UPDATE_ORDER',
                res.data
            )
        )
    },
    async batchRemoveOrders({commit}, ids) {
        try {
            await axios.post('/merchant/' + Vue.auth.user().merchant_id +
                '/researchOrder/batchDelete', {ids})
            ids.forEach(id => {
                commit('REMOVE_ORDER_ID', id)
            })
        } catch (e) {
            console.log(e);
        }
    }
};

const mutations = {
    SET_ORDERS: (state, orders) => (state.orders = orders),
    SET_LOADED: (state, boolean) => state.loaded = boolean,
    NEW_ORDER: (state, order) => order.forEach(el => state.orders.unshift(el)),
    REMOVE_ORDER: (state, order) => {
        let index = state.orders.findIndex(c => c.id === order.id);
        state.orders.splice(index, 1);
    },
    REMOVE_ORDER_ID: (state, id) => {
        let index = state.orders.findIndex(c => c.id === id);
        state.orders.splice(index, 1);
    },
    UPDATE_STATE: (state, [id, newState]) => state.orders.find(el => el.id === id).active = newState,
    UPDATE_ORDER(state, order) {
        let index = state.orders.findIndex(el => el.id === order.id);
        Vue.set(state.orders, index, order);
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
