const state = {
    items: [],
    index: null
}

const getters = {
    allItems: state => state.items,
    index: state => state.index
}

const actions = {
    showBox({commit}, [items, options = {}]) {
        commit('SET_ITEMS', items)
    },
    setIndex({commit}, index) {
        if (index === null) {
            commit('SET_ITEMS', [])
        }
        commit('SET_INDEX', index)
    }
}

const mutations = {
    SET_ITEMS: (state, items) => state.items = items,
    SET_INDEX: (state, index) => state.index = index
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
