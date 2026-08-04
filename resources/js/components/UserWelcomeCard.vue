<template>
    <div
        class="w-[380px] rounded-[28px] overflow-hidden bg-white/25 backdrop-blur-xl border border-white/40 shadow-2xl px-8 py-10 text-center"
    >

        <p class="text-gray-700 text-sm">
            Selamat Datang di
        </p>

        <h2 class="mt-2 text-[30px] font-bold text-[#005AA7]">
            Layanan Diskominfo
        </h2>

        <h3 class="text-[28px] font-bold text-gray-800">
            Kota Bogor
        </h3>

        <div class="mt-6 bg-white rounded-2xl py-4 px-4">
            <p class="text-sm text-gray-500">Login sebagai</p>
            <p class="text-lg font-semibold text-gray-800">{{ authStore.user?.name }}</p>
            <p class="text-sm text-gray-500">{{ authStore.user?.email }}</p>
        </div>

        <router-link
            v-if="authStore.user?.role === 'admin'"
            to="/admin/layanan"
            class="mt-4 block w-full h-11 leading-[44px] rounded-xl border border-[#005AA7] text-[#005AA7] font-semibold hover:bg-[#005AA7]/10 transition text-center"
        >
            Kelola Layanan
        </router-link>

        <button
            @click="handleLogout"
            :disabled="loggingOut"
            class="mt-6 w-full h-11 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700 transition disabled:opacity-50"
        >
            {{ loggingOut ? 'Keluar...' : 'Logout' }}
        </button>

    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'

const authStore = useAuthStore()
const loggingOut = ref(false)

async function handleLogout() {
    loggingOut.value = true
    await authStore.logout()
    loggingOut.value = false
}
</script>