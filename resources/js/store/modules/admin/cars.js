const state = {
    cars: [],
    userCars: [],
    loaded:false,
}

const getters = {
    allCars: state => state.cars,
    getCar: (state) => (id) => {
        return state.cars.find((car) => car.id === id);
    },
    isLoaded: state=>state.loaded,

}

const actions = {
    fetchAllCars({ commit,getters }) {
        if (!getters.isLoaded){
            return axios.get('/modele').then((res) => {
                commit('SET_CARS', res.data.data)
                commit('SET_LOADED', true)
            })
        }
    },
    newCar({ commit },[name,brand_id,year]) {
        axios.post('/modele',{
            name:name,
            brand_id:brand_id,
            year:year,
            img:'public/img/models/'+name+'.png'
        }).then((res) => {
            commit('NEW_CAR', res.data)
        })
    },
    // addCar({ commit }, id, name = null) {
    //
    // },
    removeCar({ commit }, car) {
        if (car.parent !== null)
            return false;
        axios.delete('/term/' + car.id).then(() => {
            commit('researches/REMOVE_CAR_RESEARCH', car, {root: true})
            commit('REMOVE_CAR', car)
        })
    },
    updateCar({ commit }, car, params) {
        axios.put('/term/' + car.id, {params: params}).then(() => commit('UPDATE_CAR', car, params))
    },

}

const mutations = {
    SET_CARS: (state, cars) => (state.cars = cars),
    SET_USER_CARS: (state, cars) => (state.userCars = cars),
    NEW_CAR: (state, car) => state.cars.unshift(car),
    SET_LOADED: (state, boolean) => (state.loaded = boolean),

    REMOVE_CAR: (state, car) => {
        let index = state.cars.findIndex(c => c === car);
        state.cars.splice(index, 1);
    },
    UPDATE_CAR: (state, car, params) => {
        Object.assign(state.cars.find((c) => c === car), params)
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
