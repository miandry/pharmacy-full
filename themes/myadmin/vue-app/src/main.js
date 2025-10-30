import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'

import App from './App.vue'
import Caisse from './pages/Caisse.vue'
import Clients from './pages/Clients.vue'
import UserList from './pages/user/UserList.vue'


const routes = [
  { path: '/', component: Caisse },
  { path: '/caisse', component: Caisse },
  { path: '/clients', component: Clients },
  { path: '/users', component: UserList }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

document.addEventListener("DOMContentLoaded", () => {
  const el = document.querySelector('#vue-app')
  if (el) {
    createApp(App).use(router).mount('#vue-app')
  }
})
