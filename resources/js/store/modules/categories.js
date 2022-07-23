const state = {
    categories: [],
    categoriesWithParts: [],
    loaded:false
}

const getters = {
    allCategories: state => state.categories,
    allCategoriesWithParts: state => state.categoriesWithParts,
    getCategory: (state) => (id) => {
        return state.categories.find((category) => category.id === id);
    },
    getPart: state => id => {
        return state.categoriesWithParts.find(el => el.id === id)
    },
    isLoaded: state=>state.loaded,

}

const actions = {
    fetchAllCategoriesFromModel({ commit },[model]) {
        return axios.get('/modele/'+model+'/category').then((res) =>{ return res.data.data})
    },
    fetchAllCategories({ commit }) {
        return axios.get('/category').then((res) =>{ commit('SET_CATEGORIES',res.data)})
    },
    fetchAllCategoriesWithParts({ commit,getters }) {
        if (!getters.isLoaded){
            return axios.get('/category?parts').then((res) =>{
                commit('SET_CATEGORIES_WITH_PARTS',res.data)
                commit('SET_LOADED',true)
            })
        }
    },
    // newCategory({ commit }, name = null) {
    // },
    // addCategory({ commit }, id, name = null) {
    //
    // },
    removeCategory({ commit }, category) {
        if (category.parent !== null)
            return false;
        axios.delete('/term/' + category.id).then(() => {
            commit('researches/REMOVE_CATEGORY_RESEARCH', category, {root: true})
            commit('REMOVE_CATEGORY', category)
        })
    },
    updateCategory({ commit }, category, params) {
        axios.put('/term/' + category.id, {params: params}).then(() => commit('UPDATE_CATEGORY', category, params))
    },

}

const mutations = {
    SET_CATEGORIES: (state, categories) => (state.categories = categories),
    SET_CATEGORIES_WITH_PARTS: (state, categories) => {
        categories.forEach(c=>{
            c.parts = c.parts.sort(function(a, b){
                if(a.translation < b.translation) { return -1; }
                if(a.translation > b.translation) { return 1; }
                return 0;
            });
        })
        state.categoriesWithParts = categories;
    },
    SET_USER_CATEGORIES: (state, categories) => (state.userCategories = categories),
    NEW_CATEGORY: (state, category) => state.categories.unshift(category),
    NEW_PART_CATEGORY: (state, part) => {
        if(state.categoriesWithParts.length!=0)state.categoriesWithParts[state.categoriesWithParts.findIndex(a=>a.id==part.category_id)].parts.unshift(part);
    },
    SET_LOADED: (state, boolean) => (state.loaded = boolean),

    REMOVE_CATEGORY: (state, category) => {
        let index = state.categories.findIndex(c => c === category);
        state.categories.splice(index, 1);
    },
    UPDATE_CATEGORY: (state, category, params) => {
        Object.assign(state.categories.find((c) => c === category), params)
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
