import axios from "axios";
import router from '../router'

export default {

  state: {
    content: []
  },

  getters: {
    content: s => s.content
  },

  actions: {
    fetch_content({commit}, page_name) {
      return new Promise((resolve, reject) => {
        axios.get('/api/pages/'+page_name).then((res) => {
          commit('set_content',res.data.response)
          resolve()
        }).catch(() => reject())
      })
    },

    set_content_block_query({commit}, {block, content}) {
      return new Promise((resolve, reject) => {
        axios.patch('/api/pages',{page: router.currentRoute.name, block, content}).then(() => {
          commit('set_content_block', {block, content})
          resolve()
        }).catch(() => reject())
      })
    },

    set_content_block({commit}, {block, content}) {
      return new Promise((resolve) => {
        commit('set_content_block', {block, content})
        resolve()
      })

    }
  },

  mutations: {
    set_content(state, content) {
      state.content = content
    },

    set_content_block(state, { block, content}) {
      state.content.find(item => item.block === block).content = content
    }
  }
}
