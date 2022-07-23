const state = {
    messagesAdmin: [],
    messages: [],
    nbMessages: 0,
}

const getters = {
    allMessagesAdmin: state => state.messagesAdmin,
    allMessages: state => state.messages,
    nbMessages: state => state.nbMessages,
    getMessage: (state) => (id) => {
        return state.message.find((el) => el.id === id);
    }
}

const actions = {
    fetchAllMessagesAdmin({commit}) {
        return axios.get('/admin/' + Vue.auth.user().id + '/message').then((res) => commit('SET_MESSAGES_ADMIN', res.data))
    },
    fetchAllMessagesCount({commit}) {
        return axios.get('/messageCount/' + Vue.auth.user().id).then((res) => commit('SET_MESSAGES_COUNT', res.data))
    },
    fetchAllMessages({commit}) {
        return axios.get('/user/' + Vue.auth.user().id + '/message').then((res) => commit('SET_MESSAGES', res.data))
    },
    newMessage({commit}, message) {
        return axios.post('/user/' + Vue.auth.user().id + '/message', message).then(res => {
            if (res) commit('NEW_MESSAGE', res)
        })
    },
    newMessageNotAuth({commit}, message) {
        return axios.post('/message', message).then(res => {
            // if(res)commit('NEW_MESSAGE', res)
        })
    },
    markAsRead({commit}, message) {
        return axios.put('/user/' + Vue.auth.user().id + '/message/' + message.id, {
            read: 1
        }).then(res => {
            commit('MARK_AS_READ', res.data)
        })
    },
    removeAdminMessage({commit}, message) {
        return axios.delete('/admin/' + Vue.auth.user().id + '/message/' + message.id).then(res => commit('REMOVE_MESSAGE_ADMIN', message.id))
    },
    markAsReadAdmin({commit}, message) {
        return axios.put('/admin/' + Vue.auth.user().id + '/message/' + message.id, {
            read: 1
        }).then(res => {
            commit('MARK_AS_READ_ADMIN', res.data)
        })
    },
    // addCar({ commit }, id, name = null) {
    //
    // },
}

const mutations = {
    SET_MESSAGES_ADMIN: (state, messages) => state.messagesAdmin = messages,
    SET_MESSAGES: (state, messages) => state.messages = messages,
    NEW_MESSAGE: (state, message) => {
        if (state.messagesAdmin&&state.messages.products&&state.messages.researches){
            state.messagesAdmin.unshift(message.data)
            let i = state.messages.products.findIndex(p => p.id == message.data.product_id);
            if (i !== -1) {
                state.messages.products[i].messages.push(message.data);
                let product = state.messages.products[i];
                state.messages.products.splice(i, 1);
                state.messages.products.unshift(product);
            } else {
                i = state.messages.researches.findIndex(p => p.id === message.data.research_id)
                if (i !== -1) {
                    state.messages.researches[i].messages.push(message.data);
                    let research = state.messages.researches[i];
                    state.messages.researches.splice(i, 1);
                    state.messages.researches.unshift(research);
                }
            }
        }
    },

    EDIT_MESSAGE_ADMIN: (state, message) => {
        let i = state.messagesAdmin.findIndex(m => m.id === message.id)
        Vue.set(state.messagesAdmin, i, message);
    },
    SET_MESSAGES_COUNT: (state, count) => {
        state.nbMessages = count
    },
    REMOVE_MESSAGE_ADMIN: (state, id) => {
        let index = state.messagesAdmin.findIndex(c => c.id === id);
        state.messagesAdmin.splice(index, 1);
    },
    MARK_AS_READ: (state, message) => {
        let i = state.messages.products.findIndex(p => p.id == message.product_id);
        state.nbMessages -= 1;
        if (i !== -1) {
            let j = state.messages.products[i].messages.findIndex(p => p.id == message.id);
            Vue.set(state.messages.products[i].messages, j, message);
        }
        let k = state.messages.products.findIndex(p => p.id == message.product_id);
        state.nbMessages -= 1;
        if (k !== -1) {
            let l = state.messages.products[k].messages.findIndex(p => p.id == message.id);
            Vue.set(state.messages.products[k].messages, l, message);
        }
    },
    MARK_AS_READ_ADMIN: (state, message) => {
        let item = state.messagesAdmin.find(el => el.id === message.id)
        item.read = 1
    },
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
