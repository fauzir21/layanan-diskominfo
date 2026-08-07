import { createRouter, createWebHistory } from 'vue-router'
import Home from '../pages/Home.vue'
import Register from '../pages/Register.vue'
import Services from '../pages/Services.vue'
import LayananDetail from '../pages/LayananDetail.vue'
import AdminLayanan from '../pages/AdminLayanan.vue'
import LacakPermohonan from '../pages/LacakPermohonan.vue'

const routes = [
    { path: '/', component: Home },
    { path: '/register', component: Register },
    { path: '/layanan', component: Services },
    { path: '/layanan/:slug', component: LayananDetail },
    { path: '/admin/layanan', component: AdminLayanan },
    { path: '/lacak-permohonan', component: LacakPermohonan },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router