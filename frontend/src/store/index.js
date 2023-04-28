import Vue from 'vue'
import auth from './auth'
import device from './device'
import content from './content'
//import router from '../router'
import Vuex from 'vuex'
import createPersistedState from "vuex-persistedstate"
import $ from "jquery";
import axios from 'axios'

Vue.use(Vuex)

export default new Vuex.Store({

  state: {
    loading: true
  },

  getters: {
    loading: s => s.loading
  },

  actions: {

    reset() {
      return new Promise((resolve, reject) => {
        axios.get('/api/reset').then(() => {
          resolve()
        }).catch(() => reject())
      })
    },

    change_loading({commit}, loading_status) {
      commit('change_loading', loading_status)
    },

    collapse_table_handler() {

      $('.collapsable').click(function (e) {
        if (e.target.nodeName === "svg") return
        if ($(this).hasClass("collapsed")) {
          $(this).nextUntil('tr.collapsed')
            .find('td')
            .parent()
            .find('td > div')
            .slideDown("fast", function () {
              var $set = $(this);
              $set.replaceWith($set.contents());
            });
          $(this).removeClass("collapsed");
        } else {
          $(this).nextUntil('tr.collapsed')
            .find('td')
            .wrapInner('<div style="display: block;" />')
            .parent()
            .find('td > div')
            .slideUp("fast");
          $(this).addClass("collapsed");
        }
      });
    },
  },


  mutations: {
    change_loading(state, loading_status) {
      state.loading = loading_status
    }
  },


  plugins: [createPersistedState()],
  modules: {
    auth, device, content
  }
})
