import axios from 'axios'

export default {

  state: {
    token: localStorage.getItem('user-token') || "",
    authUser: {}
  },

  getters: {
    authUser: state => state.authUser,
    isAuthenticated: state => !!state.token,
  },

  actions: {

    // Авторизация пользователя

    sign_in({commit}, data) {
      return new Promise((resolve, reject) => {
        axios.post('/api/login', data).then(res => {
          console.log(res.data.response)
          commit('auth_success', res.data.response)
          resolve()
        }).catch(() => reject())
      })
    },

    // Регистрация пользователя

    register({commit}, credentials) {
      return new Promise((resolve, reject) => {
        axios.post('/api/register', credentials).then((res) => {
          commit('auth_success', res.data.response)
          resolve()
        }).catch(() => reject())
      })
    },

    // Обновление профиля

    update_profile({commit}, credentials) {
      return new Promise((resolve, reject) => {
        axios.post('/api/update_profile', credentials).then((res) => {
          commit('update_profile', res.data.response)

          resolve()
        }).catch(err => { reject(err) })
      })
    },

    // Выход из профиля

    logout({commit}) {
      return new Promise(resolve => {
        commit('logout')
        resolve()
      })
    }

  },

  mutations: {

    auth_success(state, data) {
      localStorage.setItem('user-token', data.token)
      axios.defaults.headers.common['Authorization'] = 'Bearer ' + data.token
      state.token = data.token
      state.authUser = data.user
    },

    update_profile(state, data) {
      state.authUser = data
    },

    logout(state) {
      state.token = ""
      state.authUser = {}
      delete axios.defaults.headers.common['Authorization']
      localStorage.removeItem('user-token')
    }

  }

}
