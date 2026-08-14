<template>
    <div>

        <!-- HEADER -->
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
                Pantau status permohonan layanan Anda.
            </p>
        </div>


        <!-- LOADING -->
        <div
            v-if="loading"
            class="bg-white rounded-2xl border border-gray-100 p-8 mt-6 text-center"
        >
            <p class="text-gray-500">
                Memuat data dashboard...
            </p>
        </div>


        <!-- ERROR -->
        <div
            v-else-if="error"
            class="bg-white rounded-2xl border border-red-100 p-8 mt-6 text-center"
        >
            <p class="text-red-500 font-medium">
                {{ error }}
            </p>

            <button
                @click="fetchDashboard"
                class="mt-4 px-4 py-2 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700"
            >
                Coba Lagi
            </button>
        </div>


        <!-- DASHBOARD -->
        <template v-else>

            <!-- STATISTIK -->
            <div
                class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mt-6"
            >

                <StatCard
                    title="Total Pengajuan"
                    :value="data?.statistics?.total_pengajuan ?? 0"
                    icon="📋"
                />

                <StatCard
                    title="Menunggu"
                    :value="data?.statistics?.menunggu_diproses ?? 0"
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


            <!-- STATUS TAMBAHAN -->
            <div
                class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6"
            >

                <!-- PERBAIKAN -->
                <div
                    class="bg-white rounded-2xl border border-gray-100 p-6"
                >

                    <div class="flex items-center gap-4">

                        <div
                            class="w-11 h-11 rounded-xl bg-orange-100 flex items-center justify-center"
                        >
                            ⚠️
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Perlu Perbaikan
                            </p>

                            <p class="text-2xl font-bold text-gray-800">
                                {{ data?.statistics?.perbaikan ?? 0 }}
                            </p>
                        </div>

                    </div>

                    <p class="text-xs text-gray-400 mt-4">
                        Pengajuan yang membutuhkan perbaikan dokumen atau data.
                    </p>

                </div>


                <!-- DITOLAK -->
                <div
                    class="bg-white rounded-2xl border border-gray-100 p-6"
                >

                    <div class="flex items-center gap-4">

                        <div
                            class="w-11 h-11 rounded-xl bg-red-100 flex items-center justify-center"
                        >
                            ❌
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Ditolak
                            </p>

                            <p class="text-2xl font-bold text-red-600">
                                {{ data?.statistics?.ditolak ?? 0 }}
                            </p>
                        </div>

                    </div>

                    <p class="text-xs text-gray-400 mt-4">
                        Pengajuan yang tidak dapat diproses.
                    </p>

                </div>

            </div>


            <!-- RIWAYAT PENGAJUAN -->
            <div
                class="bg-white rounded-2xl border border-gray-100 p-6 mt-6"
            >

                <div class="flex items-center justify-between">

                    <div>
                        <h3 class="font-bold text-gray-800">
                            Pengajuan Terbaru
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Riwayat permohonan layanan Anda.
                        </p>
                    </div>

                    <router-link
                        to="/dashboard/riwayat"
                        class="text-sm text-[#005AA7] hover:underline"
                    >
                        Lihat semua
                    </router-link>

                </div>


                <!-- EMPTY -->
                <div
                    v-if="!latestApplications.length"
                    class="py-12 text-center"
                >

                    <div class="text-4xl mb-3">
                        📭
                    </div>

                    <p class="text-gray-500 text-sm">
                        Anda belum memiliki pengajuan.
                    </p>

                    <router-link
                        to="/layanan"
                        class="inline-block mt-4 px-4 py-2 rounded-lg bg-[#005AA7] text-white text-sm hover:bg-[#004b8d]"
                    >
                        Ajukan Layanan
                    </router-link>

                </div>


                <!-- DATA -->
                <div
                    v-else
                    class="mt-6 space-y-4"
                >

                    <div
                        v-for="pengajuan in latestApplications"
                        :key="pengajuan.id"
                        class="border border-gray-100 rounded-xl p-4"
                    >

                        <div
                            class="flex flex-col md:flex-row md:items-center md:justify-between gap-4"
                        >

                            <!-- INFORMASI -->
                            <div class="min-w-0">

                                <p class="font-semibold text-gray-800">
                                    {{ pengajuan.layanan || 'Layanan' }}
                                </p>

                                <p class="text-xs text-gray-500 mt-1">
                                    Tiket:
                                    {{ pengajuan.nomor_tiket }}
                                </p>

                                <p class="text-xs text-gray-400 mt-1">
                                    {{ formatDate(
                                        pengajuan.tanggal_pengajuan
                                    ) }}
                                </p>

                            </div>


                            <!-- STATUS -->
                            <div class="shrink-0">

                                <span
                                    class="inline-block px-3 py-1.5 rounded-full text-xs font-medium"
                                    :class="statusClass(pengajuan.status)"
                                >
                                    {{ statusLabel(pengajuan.status) }}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ACTION -->
            <div
                class="bg-blue-50 border border-blue-100 rounded-2xl p-6 mt-6"
            >

                <div
                    class="flex flex-col md:flex-row md:items-center md:justify-between gap-4"
                >

                    <div>

                        <h3 class="font-bold text-gray-800">
                            Butuh layanan lain?
                        </h3>

                        <p class="text-sm text-gray-600 mt-1">
                            Ajukan layanan baru melalui halaman layanan.
                        </p>

                    </div>

                    <router-link
                        to="/layanan"
                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-[#005AA7] text-white text-sm font-medium hover:bg-[#004b8d]"
                    >
                        Lihat Layanan
                    </router-link>

                </div>

            </div>

        </template>

    </div>
</template>


<script setup>
import { computed, onMounted } from 'vue'
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


/*
|--------------------------------------------------------------------------
| Pengajuan Terbaru
|--------------------------------------------------------------------------
|
| Backend sudah memfilter pengajuan berdasarkan user_id
| untuk role user/pemohon.
|
*/

const latestApplications = computed(() => {
    return data.value?.pengajuan_terbaru || []
})


/*
|--------------------------------------------------------------------------
| Status Label
|--------------------------------------------------------------------------
*/

function statusLabel(status) {
    const labels = {
        menunggu_diproses: 'Menunggu',
        diproses: 'Diproses',
        perbaikan: 'Perbaikan',
        selesai: 'Selesai',
        ditolak: 'Ditolak',
    }

    return labels[status] || status
}


/*
|--------------------------------------------------------------------------
| Status Class
|--------------------------------------------------------------------------
*/

function statusClass(status) {
    const classes = {
        menunggu_diproses:
            'bg-yellow-100 text-yellow-700',

        diproses:
            'bg-blue-100 text-blue-700',

        perbaikan:
            'bg-orange-100 text-orange-700',

        selesai:
            'bg-green-100 text-green-700',

        ditolak:
            'bg-red-100 text-red-700',
    }

    return classes[status] || 'bg-gray-100 text-gray-600'
}


/*
|--------------------------------------------------------------------------
| Format Tanggal
|--------------------------------------------------------------------------
*/

function formatDate(date) {
    if (!date) {
        return '-'
    }

    return new Date(date).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    })
}


/*
|--------------------------------------------------------------------------
| Load Dashboard
|--------------------------------------------------------------------------
*/

onMounted(() => {
    fetchDashboard()
})
</script>