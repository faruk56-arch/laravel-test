import Vue from 'vue';
import VueRouter from 'vue-router';
import VueAuth from '@websanova/vue-auth'
import axios from 'axios';
import VueAxios from 'vue-axios';
import App from './App.vue';
import router from './router';
import auth from './auth'
import store from './store'
import Autocomplete from '@trevoreyre/autocomplete-vue'
import '@trevoreyre/autocomplete-vue/dist/style.css'
import BootstrapVue from 'bootstrap-vue'
import RouterPrefetch from 'vue-router-prefetch'
import vueDebounce from 'vue-debounce'
import './plugins/removalConfirmation'
import FlagIcon from 'vue-flag-icon'
import ZoomOnHover from "vue-zoom-on-hover";
import CoolLightBox from 'vue-cool-lightbox'
import 'vue-cool-lightbox/dist/vue-cool-lightbox.min.css'
import moment from 'moment'
Vue.prototype.moment = moment
import vClickOutside from 'v-click-outside'
import VueGoodTablePlugin from 'vue-good-table';
import { i18n } from "vue-lang-router";
import Bugsnag from '@bugsnag/js'
import BugsnagPluginVue from '@bugsnag/plugin-vue'

// import the styles
import 'vue-good-table/dist/vue-good-table.css'
import translation from "./mixins/translation";

Vue.use(VueGoodTablePlugin);

Vue.use(vClickOutside)
Vue.use(CoolLightBox)
Vue.use(Autocomplete);
Vue.use(VueRouter);
Vue.use(RouterPrefetch)
Vue.router = router;
Vue.use(vueDebounce)
Vue.use(FlagIcon);
Vue.use(ZoomOnHover);

Vue.use(VueAxios, axios);
axios.defaults.baseURL = `/api`;
axios.defaults.headers.common['X-localization'] = navigator.language;

// Set Vue authentication
Vue.use(VueAuth, auth);

Vue.use(BootstrapVue);
window.Vue = Vue;
window.axios = axios;

require('./bootstrap');

/*
Bugsnag.start({
    appVersion: "1",
    apiKey: process.env.MIX_BUGSNAG_KEY,
    plugins: [new BugsnagPluginVue()],
    releaseStage: process.env.NODE_ENV,
})
*/
Vue.component('app', App)
axios.interceptors.response.use(function (response) {
    axios.defaults.headers.common['X-localization'] = i18n.locale
    return response
}, function (error) {
    if (error.response) {
        if (error.response.statusText === "Unauthenticated.") {
            return router.push({name:'login'});
        }
        if (error.response.config.url === 'auth/refresh') {
            return router.push({name:'login'});
        }
    }
    // if (error.response.status === 403) {
    //     return window.location = '/';
    // }
    return Promise.reject(error)
})
router.afterEach((to,from) => {
    store.commit('closeMenu')
})

Vue.mixin(translation)

const app = new Vue({
    i18n,
    store,
    router,
    el: '#app',
});
