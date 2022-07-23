const state = {
    userModels: [],
    loaded: false
}

const getters = {
    allUserModels: state => state.userModels,
    getUserModel: state => id => state.userModels.find(um => um.id === id),
    getCarName: state => id => {
        let car = state.userModels.find(um => um.id === id)
        if (car) {
            if (car.car_name) {
                return car.car_name
            } else if (car.modele && car.modele.brand) {
                return car.modele.brand.name + ' ' + car.modele.name;
            }
        }
        return null
    },
    isUserModelLoaded: state => state.loaded

}

const actions = {
    fetchUserModele({commit}, id) {
        return axios.get('/user/' + Vue.auth.user().id + '/userModel/' + id)
    },
    fetchAllUserModels({commit, getters},refresh=null) {
        if (!getters.isUserModelLoaded&&!refresh||getters.isUserModelLoaded&&refresh) {
            return axios.get('/user/' + Vue.auth.user().id + '/userModel').then((res) => {
                commit('SET_USERMODELS', res.data)
                commit('SET_LOADED', true)
            })
        }
    },
    removeUserModel({commit}, userModel) {
        axios.delete('/user/' + Vue.auth.user().id + '/userModel/' + userModel.id).then(() => {
            commit('REMOVE_USERMODEL', userModel)
            commit('researches/SET_RESEARCHES_LOADED', false,{root:true});
            commit('notifications/SET_LOADED', false,{root:true});

        })
    },
    newUserModel({commit}, [model, car_name = null,modele_year=null,version=null]) {
        axios.post('/user/' + Vue.auth.user().id + '/userModel', {
            model,
            ...(car_name ? {car_name} : {}),
            ...(modele_year ? {modele_year} : {}),
            ...(version ? {version} : {})
        }).then((res) => commit('NEW_USERMODEL', res.data))
    },
    updateUserModel({commit,dispatch}, [id, car_name = null, model_id = null,modele_year=null,version=null]) {
        axios.put('/user/' + Vue.auth.user().id + '/userModel/' + id, {
            ...(car_name ? {car_name} : {}),
            ...(model_id ? {model_id} : {}),
            ...(modele_year ? {modele_year} : {}),
            ...(version ? {version} : {})
        })
            .then((res) => {
                commit('UPDATE_USERMODEL', [id, model_id, car_name,modele_year,version])
                commit('researches/SET_RESEARCHES_LOADED', false,{root:true});
            })
    }
}

const mutations = {
    SET_USERMODELS: (state, userModels) => (state.userModels = userModels),
    NEW_USERMODEL: (state, userModel) => state.userModels.push(userModel),
    REMOVE_USERMODEL: (state, userModel) => {
        let index = state.userModels.findIndex(um => um.id === userModel.id);
        state.userModels.splice(index, 1);
    },
    SET_LOADED: (state, boolean) => state.loaded = boolean,
    UPDATE_USERMODEL: (state, [id, model, car_name,modele_year,version]) => {
        let um = state.userModels.find(um => um.id === id);
        if (model) {
            um.model_id = model
        }
        if (car_name) {
            um.car_name = car_name
        }
        um.year = modele_year
        um.version = version

    },
    REMOVE_CAR_RESEARCH: (state, car) => {
        state.researches = state.researches.filter((research) => {
            return research.terms.find((term) => term.id === car.id) === undefined
        })
    },
    NEW_CAR_RESEARCH: (state, [id, research]) => {
        let car = state.userModels.find(um => um.id === id);
        car.researches.push(research);
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
