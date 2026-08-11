<template>
    <div>

        <div
            class="bg-gradient-to-r from-[#005AA7] to-[#0078D4] rounded-2xl p-7 text-white"
        >
            <p class="text-blue-100 text-sm">
                Selamat datang,
            </p>

            <h1 class="text-2xl font-bold mt-1">
                {{ authStore.user?.name }} 👋
            </h1>

            <p class="text-blue-100 mt-2">
                Kelola sistem layanan Diskominfo Kota Bogor.
            </p>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mt-6">

            <StatCard
                title="Total User"
                value="0"
                icon="👥"
            />

            <StatCard
                title="Total Layanan"
                value="0"
                icon="📄"
            />

            <StatCard
                title="Total Permohonan"
                value="0"
                icon="📋"
            />

            <StatCard
                title="Permohonan Selesai"
                value="0"
                icon="✅"
            />

        </div>


        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">

            <div class="bg-white rounded-2xl border border-gray-100 p-6">

                <h3 class="font-bold text-gray-800">
                    Statistik Permohonan
                </h3>

                <div class="py-16 text-center text-gray-400 text-sm">
                    Grafik akan kita pasang setelah API statistik selesai.
                </div>

            </div>


            <div class="bg-white rounded-2xl border border-gray-100 p-6">

                <h3 class="font-bold text-gray-800">
                    Aktivitas Terbaru
                </h3>

                <div class="py-16 text-center text-gray-400 text-sm">
                    Belum ada aktivitas.
                </div>

            </div>

        </div>

    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useAuthStore } from '../../stores/auth'
import { useDashboard } from '../../composables/useDashboard'

const authStore = useAuthStore()

const {
    data,
    loading,
    error,
    fetchDashboard,
} = useDashboard()

onMounted(() => {
    fetchDashboard()
})
</script>