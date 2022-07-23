import Vue from "vue";
import Litiges from './litiges.vue';
import axios from 'axios';
window.axios = axios;

Vue.component('litiges', Litiges)

const litiges = new Vue({
    el: '#litiges',
});
