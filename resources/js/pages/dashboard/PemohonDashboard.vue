<template>
    <div>

        <!-- Welcome -->
        <div
            class="bg-gradient-to-r from-[#005AA7] to-[#0078D4] rounded-2xl p-7 text-white"
        >
            <p class="text-blue-100 text-sm">
                Selamat datang kembali,
            </p>

            <h1 class="text-2xl font-bold mt-1">
                {{ authStore.user?.name }} 👋
            </h1>

            <p class="text-blue-100 mt-2">
                Pantau dan kelola permohonan layanan Anda.
            </p>
        </div>


        <!-- Statistik -->
            <div
            v-if="loading"
            class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mt-6"
            >
            <div
                v-for="i in 4"
                :key="i"
                class="bg-white rounded-2xl border border-gray-100 p-5 animate-pulse"
            >
                <div class="h-4 bg-gray-200 rounded w-24"></div>
                <div class="h-8 bg-gray-200 rounded w-16 mt-3"></div>
            </div>
        </div>

        <div
            v-else
            class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mt-6"
        >
            <StatCard
                title="Total Permohonan"
                :value="data?.statistics?.total ?? 0"
                icon="📋"
            />

            <StatCard
                title="Menunggu"
                :value="data?.statistics?.menunggu ?? 0"
                icon="⏳"
            />

            <StatCard
                title="Diproses"
                :value="data?.statistics?.diproses ?? 0"
                icon="🔄"
            />

            <StatCard
                title="Selesai"
                :value="data?.statistics?.selesai ?? 0"
                icon="✅"
            />
        </div>


        <!-- Action -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

            <div
                class="lg:col-span-1 bg-white rounded-2xl border border-gray-100 p-6"
            >
                <h3 class="font-bold text-gray-800">
                    Butuh Layanan?
                </h3>

                <p class="text-sm text-gray-500 mt-2">
                    Ajukan layanan Diskominfo sesuai kebutuhan Anda.
                </p>

                <router-link
                    to="/layanan"
                    class="mt-5 inline-flex items-center justify-center w-full h-11 rounded-xl bg-[#005AA7] text-white font-semibold hover:bg-[#004b8c] transition"
                >
                    Ajukan Permohonan
                </router-link>
            </div>


            <!-- Permohonan terbaru -->
            <div
                class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-6"
            >

                <div class="flex items-center justify-between">

                    <h3 class="font-bold text-gray-800">
                        Permohonan Terbaru
                    </h3>

                    <router-link
                        to="/dashboard/permohonan"
                        class="text-sm text-[#005AA7] font-medium hover:underline"
                    >
                        Lihat semua
                    </router-link>

                </div>

                <div
                    class="mt-6 text-center py-10 text-gray-400 text-sm"
                >
                    Belum ada permohonan.
                </div>

            </div>

        </div>

    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useAuthStore } from '../../stores/auth'
import StatCard from '../../components/dashboard/StatCard.vue'
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