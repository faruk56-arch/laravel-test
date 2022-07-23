import axios from '@websanova/vue-auth/drivers/http/axios.1.x'
import router from '@websanova/vue-auth/drivers/router/vue-router.2.x'
import bearer from '@websanova/vue-auth/drivers/auth/bearer';

// Auth base configuration some of this options
// can be override in method calls
const config = {
    auth: bearer,
    http: axios,
    router: router,
    tokenDefaultName: 'laravel-vue-spa',
    tokenStore: ['cookie'],
    rolesKey: 'role',
    registerData: {url: 'auth/register', method: 'POST', redirect: '/login'},
    loginData: {url: 'auth/login', method: 'POST', redirect: '/finder', fetchUser: true},
    logoutData: {url: 'auth/logout', method: 'DELETE', redirect: '/logout', makeRequest: true},
    fetchData: {url: 'me', method: 'GET', enabled: true},
    refreshData: {url: 'auth/refresh', method: 'GET', enabled: true, interval: 30}

}

export default config
