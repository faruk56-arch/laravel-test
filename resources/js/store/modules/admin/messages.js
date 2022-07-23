const state = {
    messages: []
}

const getters = {
    allMessages: state => state.messages,
    getMessage: (state) => (id) => {
        return state.message.find((el) => el.id === id);
    }
}

const actions = {
    fetchAllMessages({ commit }) {
        return axios.get('/admin/message').then((res) => commit('SET_MESSAGES', res.data))
    },
    newMessage({commit}, message) {
        return axios.post('/user/' + Vue.auth.user().id + '/message', message).then(res => commit('NEW_MESSAGE', res))
    },
    // addCar({ commit }, id, name = null) {
    //
    // },
}

const mutations = {
    SET_MESSAGES: (state, messages) => state.messages = messages,
    NEW_MESSAGE: (state, message) => state.messages.unshift(message),
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
