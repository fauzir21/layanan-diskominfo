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
                Kelola permohonan layanan yang masuk.
            </p>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mt-6">

            <StatCard
                title="Permohonan Baru"
                value="0"
                icon="📥"
            />

            <StatCard
                title="Diproses"
                value="0"
                icon="🔄"
            />

            <StatCard
                title="Perbaikan"
                value="0"
                icon="⚠️"
            />

            <StatCard
                title="Selesai"
                value="0"
                icon="✅"
            />

        </div>


        <div class="bg-white rounded-2xl border border-gray-100 p-6 mt-6">

            <div class="flex justify-between items-center">

                <div>
                    <h3 class="font-bold text-gray-800">
                        Permohonan Menunggu
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Permohonan yang membutuhkan tindakan helpdesk.
                    </p>
                </div>

                <router-link
                    to="/dashboard/permohonan"
                    class="text-sm text-[#005AA7] hover:underline"
                >
                    Lihat semua
                </router-link>

            </div>

            <div class="py-12 text-center text-gray-400 text-sm">
                Belum ada permohonan baru.
            </div>

        </div>

    </div>
</template>

<script setup>
import { useAuthStore } from '../../stores/auth'
import StatCard from '../../components/dashboard/StatCard.vue'

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