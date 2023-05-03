import Vue from 'vue'
import App from './App.vue'
import Vuelidate from 'vuelidate'
import VueMeta from 'vue-meta'
import Paginate from 'vuejs-paginate'
import axios from 'axios'

import UIkit from 'uikit';
import Icons from 'uikit/dist/js/uikit-icons';
UIkit.use(Icons);
import 'uikit/dist/css/uikit.min.css';

window.UIkit = UIkit;

Vue.use(Vuelidate)
Vue.use(VueMeta)
Vue.component('Paginate', Paginate)
Vue.directive('visible', function(el, binding ) {
  el.style.visibility = binding.value ? 'visible' : 'hidden';
});

// Custom components
Vue.component('UBtn', UBtn);

axios.defaults.baseURL = process.env.VUE_APP_SERVER;

import store from './store'
import router from './router'
import UBtn from "@/components/core/UBtn.vue";

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
