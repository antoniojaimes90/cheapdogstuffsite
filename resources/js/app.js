import Vue from 'vue'
import VueRouter from 'vue-router'
import VueGoodTablePlugin from 'vue-good-table';

// import the styles 
import 'vue-good-table/dist/vue-good-table.css'
import VueResource from 'vue-resource';

Vue.use(VueResource)
Vue.use(VueGoodTablePlugin);
Vue.use(VueRouter)

import App from "./App.vue"
import Welcome from './Welcome'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Welcome
        },
    ],
});

const app = new Vue({
    el: '#app',
    components: { App },
    router,
});