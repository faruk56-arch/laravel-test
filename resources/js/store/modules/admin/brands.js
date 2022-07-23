const state = {
    brands: [],
    loaded:false
}

const getters = {
    allBrands: state => state.brands,
    getBrand: (state) => (id) => {
        return state.brands.find((brand) => brand.id === id);
    },
    isLoaded: state=>state.loaded,
}

const actions = {
    fetchAllBrands({ commit,getters }) {
        if (!getters.isLoaded){
            return axios.get('/brand').then((res) => {
                commit('SET_BRANDS', res.data.data)
                commit('SET_LOADED', true)
            })
        }
    },
    fetchModelsFromBrand({commit}, [brand]){
        return axios.get('brand/'+brand+'/modele').then((res) => {
            return res.data;
        })
    },
    newBrand({commit}, [brand]){
        return axios.post('brand/',{
            name:brand
        }).then((res) => {
            commit('NEW_BRAND', res.data)
        })
    }


}

const mutations = {
    SET_BRANDS: (state, brands) => (state.brands = brands),
    SET_LOADED: (state, boolean) => (state.loaded = boolean),
     NEW_BRAND: (state, brand) => state.brands.unshift(brand),
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
