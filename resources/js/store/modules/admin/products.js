import get from "bootstrap-vue/esm/utils/get";

const state = {
    productsAdmin: [],
    loaded: false
}

const getters = {
    allProducts: state => state.productsAdmin,
    getProduct: (state) => (id) => {
        return state.productsAdmin.find((product) => product.id === id);
    },
    getLatestProducts: state => {
        let latest = [...state.productsAdmin];
        return latest.splice(0, 4)
    },
    isProductsLoaded: state => state.loaded
};

const actions = {
    fetchAllProducts({commit, getters}) {
        if (!getters.isProductsLoaded) {
            return axios.get('/admin/' + Vue.auth.user().merchant_id + '/product').then((res) => {
                commit('SET_PRODUCTS', res.data.data)
                commit('SET_LOADED', true)
            })
        }
    },
    fetchProduct({commit}, id) {
        return axios.get('/merchant/' + Vue.auth.user().merchant_id + '/product/' + id)
    },
    removeProduct({commit}, product) {
        axios.delete('/merchant/' + Vue.auth.user().merchant_id + '/product/' + product.id).then(() => {
        })
        commit('REMOVE_PRODUCT', product)
    },
    newProduct({commit}, [product]) {
        return axios.post(
            '/merchant/' + Vue.auth.user().merchant_id + '/product',
            product,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
        )
            .then(res => commit('NEW_PRODUCT', res.data.data))
    },editProduct({commit}, [product]) {
        return axios.post(
            'updateProduct',
            product,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
        )
            .then(res => commit('EDIT_PRODUCT', res.data.data))
    }
};

const mutations = {
    SET_PRODUCTS: (state, products) => (state.productsAdmin = products),
    REMOVE_PRODUCT: (state, product) => {
        let index = state.productsAdmin.findIndex(c => c.id === product.id);
        state.productsAdmin.splice(index, 1);
    },
    NEW_PRODUCT: (state, product) => state.productsAdmin.unshift(product),
    EDIT_PRODUCT: (state, product) => {
        Object.assign(state.productsAdmin.find((p) => p.id === product.id),product)
    },
    SET_LOADED: (state, boolean) => state.loaded = boolean,
    SET_STATUS:(state,obj)=> {
        state.productsAdmin.find((p) => p.id === obj.product.id).status = obj.status;
    }
}

export default {
    namespaced: true,
        state,
        getters,
        actions,
        mutations
    }
