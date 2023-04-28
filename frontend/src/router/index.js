import vueRouter from 'vue-router'
import Vue from 'vue';
import store from '../store'

Vue.use(vueRouter)

const router = new vueRouter({
  base: process.env.BASE_URL,
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'index',
      component: () => import('../views/communication_network')
    }
  ]
})
// eslint-disable-next-line no-unused-vars
router.beforeEach((to,from,next) => {
  store.dispatch('fetch_content',to.name ?? 'index')
  next()
})



export default router
