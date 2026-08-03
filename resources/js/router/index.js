import { createRouter, createWebHistory } from 'vue-router'
import Home from '../pages/Home.vue'
import Register from '../pages/Register.vue'
import Services from '../pages/Services.vue'

const routes = [
    { path: '/', component: Home },
    { path: '/register', component: Register },
    { path: '/layanan', component: Services },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router