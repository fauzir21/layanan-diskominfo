import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

import Home from '../pages/Home.vue'
import Register from '../pages/Register.vue'
import Services from '../pages/Services.vue'
import LayananDetail from '../pages/LayananDetail.vue'
import AdminLayanan from '../pages/AdminLayanan.vue'
import LacakPermohonan from '../pages/LacakPermohonan.vue'

import Dashboard from '../pages/dashboard/Dashboard.vue'
import DashboardPermohonan from '../pages/dashboard/DashboardPermohonan.vue'
import PermohonanDetail from '../pages/dashboard/PermohonanDetail.vue'
import RiwayatPengajuan from '../pages/dashboard/RiwayatPengajuan.vue'
import Permohonan from '../pages/dashboard/Permohonan.vue'

const routes = [
    {
        path: '/',
        component: Home,
    },

    {
        path: '/register',
        component: Register,
    },

    {
        path: '/layanan',
        component: Services,
    },

    {
        path: '/layanan/:slug',
        component: LayananDetail,
    },

    {
        path: '/lacak-permohonan',
        component: LacakPermohonan,
    },

    {
        path: '/admin/layanan',
        component: AdminLayanan,
        meta: {
            requiresAuth: true,
            roles: ['admin'],
        },
    },

    {
        path: '/dashboard',
        component: Dashboard,
        meta: {
            requiresAuth: true,
            roles: ['admin', 'helpdesk', 'pegawai', 'user'],
        },
    },

    {
        path: '/dashboard/riwayat',
        component: RiwayatPengajuan,
        meta: {
            requiresAuth: true,
            roles: ['user'],
        },
    },

    {
        path: '/dashboard/permohonan',
        component: Permohonan,
        meta: {
            requiresAuth: true,
            roles: ['admin', 'helpdesk', 'pegawai'],
        },
    },

    {
        path: '/dashboard/permohonan',
        component: DashboardPermohonan,
        meta: {
            requiresAuth: true,
            roles: ['admin', 'helpdesk', 'pegawai', 'user'],
        },
    },

    {
        path: '/dashboard/permohonan/:id',
        component: PermohonanDetail,
        meta: {
            requiresAuth: true,
            roles: ['admin', 'helpdesk', 'pegawai', 'user'],
        },
    },

    {
        path: '/dashboard/permohonan/:id',
        component: PermohonanDetail,
        meta: {
            requiresAuth: true,
            roles: ['admin', 'helpdesk', 'pegawai'],
        },
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach(async (to) => {
    const authStore = useAuthStore()

    if (authStore.isLoading) {
        await authStore.fetchUser()
    }

    const requiresAuth = to.meta.requiresAuth
    const allowedRoles = to.meta.roles

    if (requiresAuth && !authStore.user) {
        return '/'
    }

    if (
        requiresAuth &&
        allowedRoles &&
        !allowedRoles.includes(authStore.user?.role)
    ) {
        return '/dashboard'
    }

    return true
})

export default router