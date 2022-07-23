const state = {
    researches: [],
    messages: [],
    researchesLoaded: false,
    messagesLoaded: false,
    isProductsLoaded: false,
    products: [],
    catalog: [],
    isCatalogLoaded: false,
    isUsersLoaded: false,
    isAlertsLoaded: false,
    allUsers: [],
    allAlerts: [],
}

const getters = {
    allProducts: state => state.products,
    allCatalog: state => state.catalog,
    allResearches: state => state.researches,
    allMessages: state => state.messages,
    getAlert: (state) => (id) => {
        return state.researches.find((research) => research.id === id);
    },
    isResearchesLoaded: state => state.researchesLoaded,
    isMessagesLoaded: state => state.messagesLoaded,
    isProductsLoaded: state => state.isProductsLoaded,
    isCatalogLoaded: state => state.isCatalogLoaded,
    isUsersLoaded: state => state.isUsersLoaded,
    isAlertsLoaded: state => state.isAlertsLoaded,
    allUsersList: state => state.allUsers,
    allAlertsList: state => state.allAlerts

}

const actions = {

    fetchCatalog({commit, getters}) {
        if (!getters.isCatalogLoaded) {
            return axios.get('/productsToEdit').then((res) => {
                commit('SET_CATALOG', res.data)
                commit('SET_CATALOG_LOADED', true)
            })
        }
    }, fetchAllResearches({commit, getters}) {
        if (!getters.isResearchesLoaded) {
            return axios.get('/admin/' + Vue.auth.user().id + '/researches').then((res) => {
                commit('SET_RESEARCHES', res.data)
                commit('SET_RESEARCHES_LOADED', true)
            })
        }
    },
    fetchAllUsers({commit, getters}) {
        if (!getters.isUsersLoaded) {
            return axios.get('/admin/' + Vue.auth.user().id + '/users').then((res) => {
                commit('SET_USERS', res.data)
                commit('SET_USERS_LOADED', true)
            })
        }
    },
    fetchAllAlerts({commit, getters}) {
        if (!getters.isAlertsLoaded) {
            return axios.get('/admin/' + Vue.auth.user().id + '/alerts').then((res) => {
                commit('SET_ALERTS', res.data)
                commit('SET_ALERTS_LOADED', true)
            })
        }
    },
    assignPartProduct({commit}, [product, part_id]) {
        axios.post('/product/' + product.id + '/part', {
            part_id: part_id,
        }).then((res) => {
            commit('ASSIGN_PART_PRODUCT', [product.id, res.data.part])
        })
    },
    fetchAllProducts({commit, getters}) {
        if (!getters.isProductsLoaded) {
            return axios.get('/admin/' + Vue.auth.user().merchant_id + '/product').then((res) => {
                commit('SET_PRODUCTS', res.data)
                commit('SET_PRODUCTS_LOADED', true)
            })
        }
    },
    fetchResearch({commit}, id) {
        return axios.get('/admin/' + Vue.auth.user().id + '/researches/' + id)
    },
    fetchAllMessages({commit, getters}) {
        if (!getters.isMessagesLoaded) {
            return axios.get('/admin/' + Vue.auth.user().id + '/message').then((res) => {
                commit('SET_MESSAGES', res.data)
                commit('SET_MESSAGES_LOADED', true)
            })
        }
    },
    changeResearchStatus({commit}, [research]) {
        axios.put('user/' + Vue.auth.user().id + '/research/' + research.id, {
            status: 'in_progress'
        }).then(function (res) {
            commit('CHANGE_STATUS', research)
        })
    },
    async changeProductStatus({commit}, [product]) {
        await axios.put('/product/' + product, {
            sent: 1
        }).then(function (res) {
            commit('CHANGE_STATUS_PRODUCT', product)
            commit('SET_CATALOG_LOADED', false)
            commit('SET_PRODUCTS_LOADED', false)
        })
    },
    async editResearch({commit},[form,id]){
        await axios.post('research/adminEdit/' + id, form,{
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }).then((res) => {
            commit('CHANGE_RESEARCH', res.data)
        })
    }
}

const mutations = {
    SET_PRODUCTS: (state, products) => (state.products = products),
    SET_PRODUCTS_LOADED: (state, boolean) => state.isProductsLoaded = boolean,

    SET_CATALOG: (state, catalog) => (state.catalog = catalog),
    SET_CATALOG_LOADED: (state, boolean) => state.isCatalogLoaded = boolean,

    SET_USERS: (state, users) => (state.allUsers = users),
    REMOVE_USER: (state, id) => {
        state.allUsers.splice(state.allUsers.findIndex(el => el.id === id), 1)
    },
    SET_USERS_LOADED: (state, boolean) => state.isUsersLoaded = boolean,

    SET_ALERTS: (state, alerts) => (state.allAlerts = alerts),
    SET_ALERTS_LOADED: (state, boolean) => state.isAlertsLoaded = boolean,

    SET_RESEARCHES: (state, researches) => (state.researches = researches),
    NEW_RESEARCH: (state, research) => state.researches.unshift(research),
    REMOVE_RESEARCH: (state, research) => {
        const index = state.researches.findIndex(el => el.id === research.id)
        state.researches.splice(index, 1)
    },
    REMOVE_PRODUCT: (state, product) => {
        const index = state.catalog.findIndex(el => el.id === product.id)
        state.catalog.splice(index, 1)
    },
    SET_MESSAGES: (state, messages) => state.messages = messages,
    SET_RESEARCHES_LOADED: (state, boolean) => state.researchesLoaded = boolean,
    SET_MESSAGES_LOADED: (state, boolean) => state.messagesLoaded = boolean,
    CHANGE_STATUS: (state, research) => {
        state.researches.find(a => a.id == research.id).status = 'in_progress'
    },
    CHANGE_RESEARCH: (state, research) => {
        let index = state.researches.findIndex(a => a.id == research.id);
        state.researches[index]=research;
    },
    ASSIGN_PART: (state, [research_id, part]) => {
        state.researches.find(a => a.id === research_id).part_id = part.id;
        state.researches.find(a => a.id === research_id).part = part;
        state.researches.find(a => a.id === research_id).unknown_part = null;
    },
    ASSIGN_PART_PRODUCT: (state, [product_id, part]) => {
        state.catalog.find(a => a.id === product_id).part_id = part.id;
        state.catalog.find(a => a.id === product_id).suggestion = null;
        state.catalog.find(a => a.id === product_id).part = part;
    },
    CHANGE_STATUS_PRODUCT: (state, product_id) => {
        state.catalog.splice(state.catalog.findIndex(a => a.id === product_id), 1);
    },
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
