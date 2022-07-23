import {toInteger} from "bootstrap-vue/esm/utils/number";

const state = {
    researches: [],
    total: 0,
    done: 0,
    loaded: false,
    allLoaded: false,
    countLoaded: false,


};

const getters = {
    allResearches: state => state.researches,
    total: state => state.total,
    done: state => state.done,
    getLatestResearches: state => {
        let latest = [...state.researches];
        return latest.splice(0, 4)
    },
    getResearch: state => (id) => state.researches.find((el) => el.id === id),
    isResearchLoaded: state => state.loaded,
    isAllResearchLoaded: state => state.allLoaded,
    isCountLoaded: state => {
        return state.countLoaded || state.total > 0
    },


};

const actions = {
        fetchAllResearches({commit, getters}, params = {}) {
            if ((!getters.isResearchLoaded && !params.getAll) || !getters.isAllResearchLoaded && params.getAll) {
                return axios.get('/user/' + Vue.auth.user().id + '/research', {params}).then(res => {
                    if (params.getAll === true) {
                        commit('SET_ALL_RESEARCHES', res.data)
                        commit('SET_ALL_RESEARCHES_LOADED', true)
                    } else {
                        commit('SET_RESEARCHES', res.data);
                        commit('SET_RESEARCHES_LOADED', true);
                        commit('SET_TOTAL', res.data.length);
                        commit('SET_DONE');
                    }
                    return res
                })
            }
        },
        fetchResearch({commit}, id) {
            return axios.get('/user/' + Vue.auth.user().id + '/research/' + id)
        },
        fetchResearchAdmin({commit}, id) {
            return axios.get('/user/' + Vue.auth.user().id + '/researchAdmin/' + id)
        },
        fetchAllResearchesCount({commit, getters}, params = {}) {
            if (!getters.isCountLoaded) {
                return axios.get('/user/' + Vue.auth.user().id + '/research?count', {params}).then((res) => {
                    commit('SET_TOTAL', res.data);
                    commit('SET_RESEARCHES_COUNT_LOADED', true);
                    return res
                })
            }
        },
        removeResearch({commit, dispatch}, research) {
            axios.delete('/user/' + Vue.auth.user().id + '/research/' + research.id).then(() => {
                commit('REMOVE_RESEARCH', research)
                commit('userModels/SET_LOADED', false, {root: true});
                dispatch('userModels/fetchAllUserModels', null, {root: true});
                commit('notifications/SET_LOADED', false,{root:true});
            })
        },
        newResearch({commit, dispatch}, [modele_id, part_id, user_id = null, custom, message = null, category = null, reference = null, details = null, images = null, modele_year = null, version = null, types = null, compatible_suggestion = null]) {
            let userId = user_id
            if (Vue.auth.check()) {
                userId = Vue.auth.user().id
            }
            let form = new FormData();
            for (var i = 0; i < images.length; i++) {
                let file = images[i];
                form.append('files[' + i + ']', file);
            }
            form.append('modele_id', modele_id);
            form.append('part_id', part_id);
            form.append('custom', custom);
            form.append('unknown_part', JSON.stringify({'category': Number(category), 'message': message}));
            form.append('reference', reference);
            form.append('details', details);
            form.append('modele_year', modele_year);
            form.append('version', version);
            form.append('types', types);
            form.append('compatible_suggestion', compatible_suggestion)
            axios.post(
                '/user/' + userId + '/research',
                form
                ,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            ).then((res) => {
                commit('NEW_RESEARCH', res.data)
            })
        },
        updateResearchSale({commit}, [research_id, sale_id, params]) {
            return axios.put('/user/' + Vue.auth.user().id + '/research/' + research_id + '/products/' + sale_id, {...params}).then(() => {
                commit('UPDATE_RESEARCH_SALE', [research_id, sale_id, params])
            });
        }
    }
;

const mutations = {
    SET_RESEARCHES: (state, researches) => state.researches = researches,
    SET_ALL_RESEARCHES: (state, researches) => state.researches = researches,
    SET_TOTAL: (state, total) => state.total = total,
    SET_DONE: state => state.done = state.researches.filter(el => el.products.length > 0).length,
    NEW_RESEARCH: (state, research) => {
        // TODO : add research to car in userModel store
        state.researches.unshift(research);
        state.total++;
    },
    SET_RESEARCHES_LOADED: (state, boolean) => state.loaded = boolean,
    SET_ALL_RESEARCHES_LOADED: (state, boolean) => state.allLoaded = boolean,
    SET_RESEARCHES_COUNT_LOADED: (state, boolean) => state.countLoaded = boolean,
    REMOVE_RESEARCH: (state, research) => {
        let index = state.researches.findIndex(r => r.id === research.id);
        state.total--;
        if (state.researches[index].status === 'finished') {
            state.done--;
        }
        state.researches.splice(index, 1);
    },
    REMOVE_CAR_RESEARCH: (state, car) => {
        state.researches = state.researches.filter((research) => {
            return research.terms.find((term) => term.id === car.id) === undefined
        })
    },
    UPDATE_RESEARCH_SALE: (state, [research_id, sale_id, params]) => {
        let research = state.researches.find((el) => el.id === research_id);
        let sale = research.products.find((el) => el.id === sale_id).pivot;
        Object.assign(sale, params)
    },

};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
