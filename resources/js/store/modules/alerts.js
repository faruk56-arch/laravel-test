const state = {
    alerts: [],
    loaded: false
};

const getters = {
    allAlerts: state => state.alerts,
    getAlert: (state) => (id) => {
        return state.alerts.find((alert) => alert.id === id);
    },
    isAlertsLoaded: state => state.loaded,
    getLatestAlerts: state => {
        let latest = [...state.alerts];
        return latest.splice(0, 4)
    },
    isDismissedFunc: state => (alert_id, research_id) => {
        let alert = state.alerts.find(el => el.id === alert_id);
        if(alert){
            let research = alert.researches.find(el => el.id === research_id);
            return research && research.pivot && research.pivot.dismiss
        }
    }

};

const actions = {
    fetchAllAlerts({commit, getters}) {
        if (!getters.isAlertsLoaded) {
            return axios.get('/merchant/' + Vue.auth.user().merchant_id + '/researchAlert').then((res) => {
                commit('SET_ALERTS', res.data.data)
                commit('SET_LOADED', true)
            })
        }
    },
    fetchAlert({commit}, id) {
        return axios.get('/merchant/' + Vue.auth.user().merchant_id + '/researchAlert/' + id)
    },
    updateActiveState({commit}, [id, newState]) {
        return axios.put('/merchant/' + Vue.auth.user().merchant_id + '/researchAlert/' + id, {active: Boolean(newState)}).then(res => commit('UPDATE_STATE', [id, newState]))
    },
    newAlert({commit}, [models, categories, parts]) {
        return axios.post(
            '/merchant/' + Vue.auth.user().merchant_id + '/researchAlert',
            {
                models,
                categories,
                parts,
            }
        ).then(res => commit('NEW_ALERT', res.data))
    },
    removeAlert({commit}, alert) {
        axios.delete('/merchant/' + Vue.auth.user().merchant_id + '/researchAlert/' + alert.id).then(() => {
        })
        commit('REMOVE_ALERT', alert);
        commit('notifications/SET_LOADED', false, {root: true});
    },
    toggleResearchDismiss({commit}, [alert_id, research_id, bool = null]) {
        axios.put('/merchant/' + Vue.auth.user().merchant_id + '/researchAlert/' + alert_id, {
            dismiss: research_id,
            bool
        }).then(
            res => commit(
                'UPDATE_ALERT',
                res.data
            )
        )
    },
    async batchRemoveAlerts({commit}, ids) {
        try {
            await axios.post('/merchant/' + Vue.auth.user().merchant_id +
                '/researchAlert/batchDelete', {ids})
            ids.forEach(id => {
                commit('REMOVE_ALERT_ID', id)
            })
        } catch (e) {
            console.log(e);
        }
    }
};

const mutations = {
    SET_ALERTS: (state, alerts) => (state.alerts = alerts),
    SET_LOADED: (state, boolean) => state.loaded = boolean,
    NEW_ALERT: (state, alert) => alert.forEach(el => state.alerts.unshift(el)),
    REMOVE_ALERT: (state, alert) => {
        let index = state.alerts.findIndex(c => c.id === alert.id);
        state.alerts.splice(index, 1);
    },
    REMOVE_ALERT_ID: (state, id) => {
        let index = state.alerts.findIndex(c => c.id === id);
        state.alerts.splice(index, 1);
    },
    UPDATE_STATE: (state, [id, newState]) => state.alerts.find(el => el.id === id).active = newState,
    UPDATE_ALERT(state, alert) {
        let index = state.alerts.findIndex(el => el.id === alert.id);
        Vue.set(state.alerts, index, alert);
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
