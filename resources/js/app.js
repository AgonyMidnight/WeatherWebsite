import App from "./App.vue";

require('./bootstrap');
import Vue from 'vue'
import VueRouter from 'vue-router'
import routes from "./routes";
import axios from 'axios'
import VueAxios from 'vue-axios'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'


import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(VueRouter)
Vue.use(VueAxios, axios)
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)

const router = new VueRouter({
  routes
})

const app = new Vue({
  el: '#app',
  router: router,
  components:{'app': App}
});