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
                Kelola sistem layanan Diskominfo Kota Bogor.
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
                    title="Total User"
                    :value="data?.system?.total_user ?? 0"
                    icon="👥"
                />

                <StatCard
                    title="Total Layanan"
                    :value="data?.system?.total_layanan ?? 0"
                    icon="📄"
                />

                <StatCard
                    title="Total Permohonan"
                    :value="data?.statistics?.total_pengajuan ?? 0"
                    icon="📋"
                />

                <StatCard
                    title="Permohonan Selesai"
                    :value="data?.statistics?.selesai ?? 0"
                    icon="✅"
                />
            </div>

            <!-- INFORMASI -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">

                <!-- STATISTIK PERMOHONAN -->
                <div
                    class="bg-white rounded-2xl border border-gray-100 p-6"
                >
                    <h3 class="font-bold text-gray-800">
                        Statistik Permohonan
                    </h3>

                    <div class="space-y-4 mt-6">

                        <!-- Menunggu -->
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">
                                    Menunggu Diproses
                                </span>

                                <span class="font-semibold text-gray-800">
                                    {{ data?.statistics?.menunggu_diproses ?? 0 }}
                                </span>
                            </div>

                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div
                                    class="bg-yellow-400 h-2 rounded-full"
                                    :style="{
                                        width: percentage(
                                            data?.statistics?.menunggu_diproses
                                        )
                                    }"
                                ></div>
                            </div>
                        </div>

                        <!-- Diproses -->
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">
                                    Diproses
                                </span>

                                <span class="font-semibold text-gray-800">
                                    {{ data?.statistics?.diproses ?? 0 }}
                                </span>
                            </div>

                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div
                                    class="bg-blue-500 h-2 rounded-full"
                                    :style="{
                                        width: percentage(
                                            data?.statistics?.diproses
                                        )
                                    }"
                                ></div>
                            </div>
                        </div>

                        <!-- Perbaikan -->
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">
                                    Perlu Perbaikan
                                </span>

                                <span class="font-semibold text-gray-800">
                                    {{ data?.statistics?.perbaikan ?? 0 }}
                                </span>
                            </div>

                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div
                                    class="bg-orange-400 h-2 rounded-full"
                                    :style="{
                                        width: percentage(
                                            data?.statistics?.perbaikan
                                        )
                                    }"
                                ></div>
                            </div>
                        </div>

                        <!-- Selesai -->
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">
                                    Selesai
                                </span>

                                <span class="font-semibold text-gray-800">
                                    {{ data?.statistics?.selesai ?? 0 }}
                                </span>
                            </div>

                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div
                                    class="bg-green-500 h-2 rounded-full"
                                    :style="{
                                        width: percentage(
                                            data?.statistics?.selesai
                                        )
                                    }"
                                ></div>
                            </div>
                        </div>

                        <!-- Ditolak -->
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">
                                    Ditolak
                                </span>

                                <span class="font-semibold text-gray-800">
                                    {{ data?.statistics?.ditolak ?? 0 }}
                                </span>
                            </div>

                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div
                                    class="bg-red-500 h-2 rounded-full"
                                    :style="{
                                        width: percentage(
                                            data?.statistics?.ditolak
                                        )
                                    }"
                                ></div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- AKTIVITAS TERBARU -->
                <div
                    class="bg-white rounded-2xl border border-gray-100 p-6"
                >
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-gray-800">
                            Aktivitas Terbaru
                        </h3>

                        <span class="text-xs text-gray-400">
                            5 terbaru
                        </span>
                    </div>

                    <div
                        v-if="!data?.pengajuan_terbaru?.length"
                        class="py-16 text-center text-gray-400 text-sm"
                    >
                        Belum ada pengajuan.
                    </div>

                    <div
                        v-else
                        class="mt-5 space-y-4"
                    >
                        <div
                            v-for="pengajuan in data.pengajuan_terbaru"
                            :key="pengajuan.id"
                            class="flex items-start justify-between gap-4 border-b border-gray-100 pb-4 last:border-0 last:pb-0"
                        >
                            <div class="min-w-0">
                                <p class="font-medium text-gray-800 truncate">
                                    {{ pengajuan.layanan || 'Layanan' }}
                                </p>

                                <p class="text-xs text-gray-500 mt-1">
                                    {{ pengajuan.nomor_tiket }}
                                </p>

                                <p class="text-xs text-gray-400 mt-1">
                                    {{ pengajuan.nama_pemohon || '-' }}
                                </p>
                            </div>

                            <span
                                class="shrink-0 px-2.5 py-1 rounded-full text-xs font-medium"
                                :class="statusClass(pengajuan.status)"
                            >
                                {{ statusLabel(pengajuan.status) }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- INFO SISTEM -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

                <div
                    class="bg-white rounded-2xl border border-gray-100 p-6"
                >
                    <p class="text-sm text-gray-500">
                        Total Tim Kerja
                    </p>

                    <p class="text-2xl font-bold text-gray-800 mt-2">
                        {{ data?.system?.total_tim_kerja ?? 0 }}
                    </p>
                </div>

                <div
                    class="bg-white rounded-2xl border border-gray-100 p-6"
                >
                    <p class="text-sm text-gray-500">
                        Total Pengajuan
                    </p>

                    <p class="text-2xl font-bold text-gray-800 mt-2">
                        {{ data?.statistics?.total_pengajuan ?? 0 }}
                    </p>
                </div>

            </div>
        </template>
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

function percentage(value) {
    const total = data.value?.statistics?.total_pengajuan ?? 0

    if (!total || !value) {
        return '0%'
    }

    return `${Math.min((value / total) * 100, 100)}%`
}

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

onMounted(() => {
    fetchDashboard()
})
</script>