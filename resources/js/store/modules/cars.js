const state = {
    cars: [],
    userCars: [],
    loaded: false,
}

const getters = {
    allCars: state => state.cars,
    getCar: (state) => (id) => {
        return state.cars.find((car) => car.id === id);
    },
    isLoaded: state => state.loaded,

}

const actions = {
    fetchAllCars({commit, getters}) {
        if (!getters.isLoaded) {
            return axios.get('/modele').then((res) => {
                commit('SET_CARS', res.data)
                commit('SET_LOADED', true)
            })
        }
    }, fetchCar({commit}, id) {
        return axios.get('/modele/' + id).then(res => res.data)
    },
    newCar({commit}, [name, brand_id, year_start, year_end, img, compatible_modeles = null]) {
        axios.post('/modele', {
            name,
            brand_id,
            year_start,
            year_end,
            img,
            compatible_modeles,
        }).then((res) => {
            commit('NEW_CAR', res.data)
        })
    },
    // addCar({ commit }, id, name = null) {
    //
    // },
    removeCar({commit}, car) {
        if (car.parent !== null)
            return false;
        axios.delete('/term/' + car.id).then(() => {
            commit('researches/REMOVE_CAR_RESEARCH', car, {root: true})
            commit('REMOVE_CAR', car)
        })
    },
    updateCar({commit}, [id, params]) {
        axios.put('/modele/' + id, params).then((res) => commit('UPDATE_CAR', res.data))
    },

}

const mutations = {
    SET_CARS: (state, cars) => (state.cars = cars),
    SET_USER_CARS: (state, cars) => (state.userCars = cars),
    NEW_CAR: (state, car) => state.cars.push(car),
    SET_LOADED: (state, boolean) => (state.loaded = boolean),

    REMOVE_CAR: (state, id) => {
        let index = state.cars.findIndex(c => c.id === id);
        state.cars.splice(index, 1);
    },
    UPDATE_CAR: (state, car) => {
        Object.assign(state.cars.find(el => el.id === car.id), car)
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
