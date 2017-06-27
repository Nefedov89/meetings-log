
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import VueRouter from 'vue-router'

require('./bootstrap');

window.Vue = require('vue');

Vue.use(VueRouter);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const meeting = Vue.component('meeting', require('./components/MeetingForm.vue'));
const rate = Vue.component('rate', require('./components/EmptyRate.vue'));

const routes = [
    { path: '/empty-date-rate', name: 'Empty rate', component: rate },
    { path: '/', name: 'Meeting form', component: meeting }
];

const router = new VueRouter({
    routes, // short for routes: routes
    mode: 'history'
});

const app = new Vue({
    router
}).$mount('#app');