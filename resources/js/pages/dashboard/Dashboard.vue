<template>
    <DashboardLayout>

        <AdminDashboard v-if="role === 'admin'" />

        <HelpdeskDashboard v-else-if="role === 'helpdesk'" />

        <PegawaiDashboard v-else-if="role === 'pegawai'" />

        <PemohonDashboard v-else-if="role === 'user'" />

        <div
            v-else
            class="bg-white rounded-2xl p-8 text-center"
        >
            <h2 class="text-xl font-bold text-gray-800">
                Role tidak dikenali
            </h2>

            <p class="text-gray-500 mt-2">
                Silakan hubungi administrator.
            </p>
        </div>

    </DashboardLayout>
</template>

<script setup>
import { computed } from 'vue'
import { useAuthStore } from '../../stores/auth'

import DashboardLayout from '../../components/dashboard/DashboardLayout.vue'

import AdminDashboard from './AdminDashboard.vue'
import HelpdeskDashboard from './HelpdeskDashboard.vue'
import PegawaiDashboard from './PegawaiDashboard.vue'
import PemohonDashboard from './PemohonDashboard.vue'

const authStore = useAuthStore()

const role = computed(() => authStore.user?.role)
</script>