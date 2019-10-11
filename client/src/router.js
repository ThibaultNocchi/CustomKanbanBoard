import Vue from 'vue'
import Router from 'vue-router'
import Login from './views/Login.vue'

Vue.use(Router)

export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/',
      name: 'login',
      component: Login
    },
    {
      path: '/home',
      name: 'home',
      component: function () { 
        return import('./views/Home.vue')
      }
    },
    {
      path: '/login/:code',
      name: 'login_with_code',
      component: Login
    }
  ]
})
