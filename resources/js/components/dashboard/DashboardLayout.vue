<template>
    <div class="min-h-screen bg-gray-50 flex">

        <!-- Sidebar -->
        <aside
            class="w-64 bg-white border-r border-gray-200 flex flex-col fixed inset-y-0 left-0 z-30"
        >

            <!-- Logo -->
            <div class="h-20 flex items-center px-6 border-b border-gray-100">
                <div>
                    <h1 class="text-lg font-bold text-[#005AA7]">
                        Layanan Diskominfo
                    </h1>

                    <p class="text-xs text-gray-500">
                        Kota Bogor
                    </p>
                </div>
            </div>

            <!-- User -->
            <div class="px-5 py-5 border-b border-gray-100">
                <p class="text-sm font-semibold text-gray-800">
                    {{ authStore.user?.name }}
                </p>

                <p class="text-xs text-gray-500 mt-1">
                    {{ roleLabel }}
                </p>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-5 space-y-1 overflow-y-auto">

                <router-link
                    to="/dashboard"
                    class="menu-item"
                    active-class="menu-active"
                >
                    <span>🏠</span>
                    <span>Dashboard</span>
                </router-link>

                <!-- PEMOHON -->
                <template v-if="authStore.user?.role === 'user'">

                    <router-link
                        to="/dashboard/permohonan"
                        class="menu-item"
                        active-class="menu-active"
                    >
                        <span>📋</span>
                        <span>Permohonan Saya</span>
                    </router-link>

                    <router-link
                        to="/layanan"
                        class="menu-item"
                    >
                        <span>📄</span>
                        <span>Layanan</span>
                    </router-link>

                    <router-link
                        to="/lacak-permohonan"
                        class="menu-item"
                    >
                        <span>🔍</span>
                        <span>Lacak Permohonan</span>
                    </router-link>

                </template>

                <!-- HELPDESK -->
                <template v-if="authStore.user?.role === 'helpdesk'">

                    <router-link
                        to="/dashboard/permohonan"
                        class="menu-item"
                        active-class="menu-active"
                    >
                        <span>📥</span>
                        <span>Permohonan Masuk</span>
                    </router-link>

                    <router-link
                        to="/dashboard/laporan"
                        class="menu-item"
                        active-class="menu-active"
                    >
                        <span>📊</span>
                        <span>Laporan</span>
                    </router-link>

                </template>

                <!-- PEGAWAI -->
                <template v-if="authStore.user?.role === 'pegawai'">

                    <router-link
                        to="/dashboard/permohonan"
                        class="menu-item"
                        active-class="menu-active"
                    >
                        <span>📥</span>
                        <span>Permohonan Tim</span>
                    </router-link>

                    <router-link
                        to="/dashboard/riwayat"
                        class="menu-item"
                        active-class="menu-active"
                    >
                        <span>📋</span>
                        <span>Riwayat</span>
                    </router-link>

                </template>

                <!-- ADMIN -->
                <template v-if="authStore.user?.role === 'admin'">

                    <router-link
                        to="/dashboard/users"
                        class="menu-item"
                        active-class="menu-active"
                    >
                        <span>👥</span>
                        <span>User</span>
                    </router-link>

                    <router-link
                        to="/dashboard/tim-kerja"
                        class="menu-item"
                        active-class="menu-active"
                    >
                        <span>👨‍💻</span>
                        <span>Tim Kerja</span>
                    </router-link>

                    <router-link
                        to="/admin/layanan"
                        class="menu-item"
                    >
                        <span>📄</span>
                        <span>Layanan</span>
                    </router-link>

                    <router-link
                        to="/dashboard/persyaratan"
                        class="menu-item"
                        active-class="menu-active"
                    >
                        <span>📑</span>
                        <span>Persyaratan</span>
                    </router-link>

                    <router-link
                        to="/dashboard/permohonan"
                        class="menu-item"
                        active-class="menu-active"
                    >
                        <span>📋</span>
                        <span>Permohonan</span>
                    </router-link>

                    <router-link
                        to="/dashboard/survei"
                        class="menu-item"
                        active-class="menu-active"
                    >
                        <span>📝</span>
                        <span>Survei</span>
                    </router-link>

                    <router-link
                        to="/dashboard/laporan"
                        class="menu-item"
                        active-class="menu-active"
                    >
                        <span>📊</span>
                        <span>Laporan</span>
                    </router-link>

                </template>

            </nav>

            <!-- Logout -->
            <div class="p-4 border-t border-gray-100">

                <button
                    @click="handleLogout"
                    :disabled="loggingOut"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 transition text-sm font-medium"
                >
                    <span>🚪</span>

                    <span>
                        {{ loggingOut ? 'Keluar...' : 'Logout' }}
                    </span>
                </button>

            </div>

        </aside>


        <!-- Main Content -->
        <main class="ml-64 flex-1 min-h-screen">

            <!-- Topbar -->
            <header
                class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-8 sticky top-0 z-20"
            >

                <div>
                    <h2 class="text-xl font-bold text-gray-800">
                        {{ pageTitle }}
                    </h2>

                    <p class="text-sm text-gray-500">
                        Kelola layanan dan permohonan Anda
                    </p>
                </div>

                <div class="flex items-center gap-3">

                    <div
                        class="w-10 h-10 rounded-full bg-[#005AA7] text-white flex items-center justify-center font-semibold"
                    >
                        {{ initial }}
                    </div>

                </div>

            </header>

            <!-- Page -->
            <div class="p-8">
                <slot />
            </div>

        </main>

    </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

const authStore = useAuthStore()
const router = useRouter()

const loggingOut = ref(false)

const roleLabel = computed(() => {
    const roles = {
        admin: 'Administrator',
        helpdesk: 'Helpdesk',
        pegawai: 'Pegawai',
        user: 'Pemohon',
    }

    return roles[authStore.user?.role] || 'Pengguna'
})

const pageTitle = computed(() => {
    const roles = {
        admin: 'Dashboard Administrator',
        helpdesk: 'Dashboard Helpdesk',
        pegawai: 'Dashboard Pegawai',
        user: 'Dashboard Pemohon',
    }

    return roles[authStore.user?.role] || 'Dashboard'
})

const initial = computed(() => {
    return authStore.user?.name?.charAt(0)?.toUpperCase() || 'U'
})

async function handleLogout() {
    loggingOut.value = true

    try {
        await authStore.logout()
        router.push('/')
    } finally {
        loggingOut.value = false
    }
}
</script>

<style scoped>
.menu-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border-radius: 0.75rem;
    font-size: 0.875rem;
    color: #4b5563;
    transition: all 0.2s ease;
}

.menu-item:hover {
    background-color: #f3f4f6;
    color: #111827;
}

.menu-active {
    background-color: #eff6ff;
    color: #005aa7;
    font-weight: 600;
}
</style>