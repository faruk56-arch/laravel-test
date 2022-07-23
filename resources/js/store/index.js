import Vue from 'vue'
import Vuex from 'vuex'
import createLogger from '../plugins/logger'
import cars from './modules/cars';
import researches from './modules/researches';
import userModels from './modules/userModels';
import parts from './modules/parts';
import categories from "./modules/categories";
import brands from "./modules/brands";
import products from "./modules/products";
import alerts from "./modules/alerts";
import orders from "./modules/orders";
import merchant from "./modules/merchant";
import sales from "./modules/sales";
import admin from "./modules/admin";
import messages from "./modules/messages";
import notifications from "./modules/notifications";
import {createStore} from "vuex-extensions";
import coolLightBox from "./modules/coolLightBox";

Vue.use(Vuex);

const debug = false;

export default createStore(Vuex.Store, {
    state:{
      sideMenu:false,
    },
    actions: {
        logout() {
            this.reset()
        },
    },
    mutations:{
        toggleMenu(state){
            state.sideMenu = !state.sideMenu;
        },closeMenu(state){
            state.sideMenu = false;
        }
    },
    modules: {
        coolLightBox,
        parts,
        cars,
        researches,
        userModels,
        brands,
        products,
        categories,
        alerts,
        orders,
        merchant,
        sales,
        admin,
        messages,
        notifications
    },
    strict: debug,
    plugins: debug ? [createLogger()] : []
})
