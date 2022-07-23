const state = {
    notifications: [],
    loaded:false
}

const getters = {
    allNotifications: state => state.notifications,
    allUnreadNotifications: state => state.notifications.filter(el => el.read_at === null),
    getNotification: (state) => (id) => {
        return state.notifications.find((notification) => notification.id === id);
    },
    isNotifLoaded: state => state.loaded,

}

const actions = {
    fetchAllUserNotifications({commit,getters}) {
        if (!getters.isNotifLoaded){
            return axios.get('/user/' + Vue.auth.user().id + '/notification')
                .then((res) => {
                    commit('SET_NOTIFICATIONS', res.data)
                    commit('SET_LOADED', true)
                })
        }
    },
    markAsRead({commit}, [id]) {
        return axios.put('/user/' + Vue.auth.user().id + '/notification/' + id, {read_at: 1})
            .then((res) => commit('SET_READ_AT', res.data))
    }
}

const mutations = {
    SET_NOTIFICATIONS: (state, notifications) => state.notifications = notifications,
    SET_LOADED: (state, boolean) => state.loaded = boolean,
    SET_READ_AT: (state, notification) => state.notifications.find((el) => el.id === notification.id).read_at = notification.read_at
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
