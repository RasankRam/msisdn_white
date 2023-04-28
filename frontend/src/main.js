import Vue from 'vue'
import App from './App.vue'
import Vuelidate from 'vuelidate'
import VueMeta from 'vue-meta'
import Paginate from 'vuejs-paginate'
import axios from 'axios'

Vue.use(Vuelidate)
Vue.use(VueMeta)
Vue.component('Paginate', Paginate)
Vue.directive('visible', function(el, binding ) {
  el.style.visibility = binding.value ? 'visible' : 'hidden';
});

axios.defaults.baseURL = process.env.VUE_APP_SERVER;

import store from './store'
import router from './router'

const token = localStorage.getItem('user-token')
if (token) {
  axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
}

Vue.config.productionTip = false

new Vue({
  render: h => h(App),
  store,
  router,
}).$mount('#app')
