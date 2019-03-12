import $ from "jquery";

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import './jquery/jquery-logic';

// window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#app'
// });
import Vue from 'vue'
import Main from './Main.vue'
import VueRouter from 'vue-router'
import Vuex from 'vuex'
import {routes} from './routes'

Vue.use(VueRouter)
Vue.use(Vuex)

const store = new Vuex.Store(StoreData)
const router = new VueRouter({
  routes,
  mode: 'history'
})

//router.beforeEach((to, from, next) =>{
//  const cart = store.state.currentCart;
//  if(to.path == '/empty' && cart != null){
//    next('/');
//  }else if(to.path != '/empty' && cart == null){
//    next('/empty');
//  }else{
//    next();
//  }
//});

new Vue({
  render: h => h(Main),
  router,
}).$mount('#app')
