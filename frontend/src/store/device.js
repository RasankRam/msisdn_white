import axios from "axios"
import store from '../store'

export default {

  state: {
    pagination_response: {}
  },

  getters: {
    pagination_response: s => s.pagination_response
  },

  actions: {

    fetch_all_msisdns() {
      return new Promise ((resolve, reject) => {
        axios.get('/api/all_msisdns').then(res => {
          resolve(res.data.response)
        }).catch(() => reject())
      })
    },

    fetch_devices({commit}, query_object) {
      return new Promise ((resolve, reject) => {

        axios.get(`/api/devices`, {params: query_object}).then(res => {
          const pagination_response = res.data.response

          pagination_response.data = pagination_response.data.map(item => {
            item.is_collapsed = true
            item.is_collapsable = item.black_list.length !== 0;
            return item
          })

          commit('set_pagination_response', pagination_response)

          resolve(pagination_response)
        }).catch(() => reject())
      })
    },

    fetch_my_devices() {
      return new Promise ((resolve, reject) => {
        axios.get('/api/my_devices').then(res => {
          resolve(res.data.response)
        }).catch(() => reject())
      })
    },

    // eslint-disable-next-line no-unused-vars
    call({commit},data) {
      return new Promise ((resolve, reject) => {
        axios.post('/api/call', data).then(() => {
          resolve()
        }).catch(() => reject())
      })
    },

    set_pagination_response({commit}, pagination_response) {
      commit('set_pagination_response', pagination_response)
    },

    create_device({commit}, data_input) {
      let data = Object.assign({}, data_input)
      return new Promise((resolve, reject) => {
        commit('add_init_device', data)
        axios.post('api/devices', data).then((res) => {
          commit('add_success_device', res.data.response.id)
          resolve()
        }).catch(() => {
          commit('add_failed_device')
          reject()
        })
      })
    },

    update_device({commit}, data) {
      commit('update_init_device', data)

      return new Promise((resolve, reject) => {
        axios.patch('/api/devices', data).then(() => {
          commit('update_success_device', data.id)
          resolve()
        }).catch(() => {
          commit('update_failed_device', data.id)
          reject()
        })
      })
    },

    remove_device({commit}, id) {
      return new Promise((resolve, reject) => {
        axios.delete(`/api/devices/${id}`).then(() => {
          commit('remove_device', id)
          resolve()
        }).catch(() => reject())
      })
    },

    create_device_msisdn({commit}, data) {
      return new Promise((resolve, reject) => {
        axios.post(`/api/devices/${data.device_id}/msisdn`, {msisdn: data.msisdn }).then(() => {
          commit('create_device_msisdn', data)
          resolve()
        }).catch(() => reject())
      })

    },

    remove_device_msisdn({commit}, data) {
      return new Promise((resolve, reject) => {
        axios.delete(`/api/devices/${data.device_id}/${data.msisdn}`).then(() => {
          commit('remove_device_msisdn', data)
          resolve()
        }).catch(() => reject())
      })
    }
  },

  mutations: {

    add_init_device(state, data) {
      let data_push = Object.assign({}, data)
      if (state.pagination_response.data.length > 10) state.pagination_response.data.pop()
      data_push.creator = store.getters.isAuthenticated ? this.getters.authUser : null
      data_push.imsi = { imsi: data_push.imsi }
      data_push.msisdn = { msisdn: data_push.msisdn }
      data_push.is_collapsable = false
      data_push.is_collapsed = true
      data_push.created_at = new Date()
      data_push.updated_at = new Date()
      data_push.status = ''
      data_push.black_list = []
      state.pagination_response.data.unshift(data_push)
    },

    add_success_device(state, id) {
      let device = state.pagination_response.data[0]
      device.id = id
      device.status = 'success'
      setTimeout(() => device.status = '', 400)
    },

    add_failed_device(state) {
      let device = state.pagination_response.data[0]
      device.status = "failed"
      setTimeout(() => {
        setTimeout(() => {
          state.pagination_response.data.shift()
        }, 200)
        device.status = ''
      }, 100)
    },

    update_init_device(state, data) {
      let device = state.pagination_response.data.find(item => +item.id === +data.id)

      for (let key in data) {
        if (typeof (device[key]) === 'object') device[key][key] = data[key]
        else device[key] = data[key]
      }

      device.status = 'init'
      device.updated_at = new Date()
    },

    update_success_device(state, id) {
      let device = state.pagination_response.data.find(item => item.id === id)
      device.status = 'updated'
      setTimeout(() => device.status = '', 100)
    },

    update_failed_device(state, id) {
      let device = state.pagination_response.data.find(item => item.id === id)
      device.status = 'failed'
      setTimeout(() => device.status = '', 100)
    },

    set_pagination_response(state, pagination_response) {
      state.pagination_response = pagination_response
    },

    create_device(state, data) {
      state.pagination_response.data.pop();
      state.pagination_response.data.unshift(data)
    },

    remove_device(state, id) {
      state.pagination_response.data = state.pagination_response.data.filter(item => +item.id !== +id)
    },

    create_device_msisdn(state, data) {
      const device = state.pagination_response.data.find(item => +item.id === +data.device_id)
      device.is_collapsable = true
      device.black_list.unshift(data.msisdn)
    },

    remove_device_msisdn(state, data) {
      const device = state.pagination_response.data.black_list.find(item => +item.id === +data.device_id)
      device.black_list = device.black_list.filter(item => item !== data.msisdn)
      if (device.black_list.length === 0) device.is_collapsable = false
    },

  }

}
