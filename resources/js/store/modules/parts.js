const state = {
    parts: [],
    modeleCategoryParts: [],
    loaded: false
}

const getters = {
    allParts: state => state.parts,
    allModeleCategoryParts: state => state.modeleCategoryParts,
    getPart: state => id => state.parts.find(el => el.id === id),
    isLoaded: state => state.loaded,
}

const actions = {
    fetchAllModeleCategoryParts({commit}, [modele, category]) {
        return axios.get('/modele/' + modele + '/category/' + category + '/part').then((res) => {
            commit('SET_MODELE_CATEGORY_PARTS', res.data)
            return res.data;
        })
    },
    fetchPart({commit}, id) {
        return axios.get('/part/' + id).then(res => res.data)
    },
    fetchAllParts({commit, getters}) {
        if (!getters.isLoaded) {
            return axios.get('/part').then(res => {
                commit('SET_PARTS', res.data)
                commit('SET_LOADED', true)
            })
        }
    },
    newPart({commit}, params) {
        return axios.post('/part', params).then((res) => {
            commit('NEW_PART', res.data)
            // commit('categories/NEW_PART_CATEGORY', res.data, {root: true})
            return res.data;
        })
    },
    updatePart({commit}, [id, params]) {
        return axios.put('/part/' + id, params).then((res) => {
            commit('SET_PART', [id, res.data])
        })
    },
    assignPart({commit}, [research, part_id]) {
        return axios.post('/research/' + research.id + '/part', {
            part_id: part_id,
        }).then((res) => {
            commit('admin/ASSIGN_PART', [research.id, res.data.part], {root: true})
        })
    },
}

const mutations = {
    SET_MODELE_CATEGORY_PARTS: (state, modeleCategoryParts) => (state.modeleCategoryParts = modeleCategoryParts),
    NEW_PART: (state, part) => {
        state.parts.push(part);
    },
    SET_PARTS: (state, parts) => state.parts = parts,
    SET_PART: (state, [id, part]) => {
        Object.assign(state.parts.find(el => el.id === id), part)
    },
    SET_LOADED: (state, boolean) => (state.loaded = boolean),
    REMOVE_PART: (state, id) => {
        state.parts.splice(state.parts.findIndex(el => el.id === id), 1)
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
