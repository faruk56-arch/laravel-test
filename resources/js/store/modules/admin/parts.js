const state = {
    parts: [],
    modeleCategoryParts: []
}

const getters = {
    allParts: state => state.parts,
    allModeleCategoryParts: state => state.modeleCategoryParts
}

const actions = {
    fetchAllModeleCategoryParts({ commit }, [modele, category]) {
        return axios.get('/modele/'+ modele + '/category/' + category + '/part').then((res) => {
            commit('SET_MODELE_CATEGORY_PARTS', res.data)
            return res.data;
        })
    },
}

const mutations = {
    SET_MODELE_CATEGORY_PARTS: (state, modeleCategoryParts) => (state.modeleCategoryParts = modeleCategoryParts)
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
