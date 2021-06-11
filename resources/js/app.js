import App from "./App.vue";

require('./bootstrap');
import Vue from 'vue'
import VueRouter from 'vue-router'
import routes from "./routes";
import axios from 'axios'
import VueAxios from 'vue-axios'

Vue.use(VueRouter)
Vue.use(VueAxios, axios)

const router = new VueRouter({
  routes
})

const app = new Vue({
  el: '#app',
  router: router,
  components:{'app': App}
});