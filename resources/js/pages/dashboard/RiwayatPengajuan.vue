<template>
    <div>

        <!-- HEADER -->
        <div
            class="bg-gradient-to-r from-[#005AA7] to-[#0078D4] rounded-2xl p-7 text-white"
        >
            <p class="text-blue-100 text-sm">
                Pengajuan Saya
            </p>

            <h1 class="text-2xl font-bold mt-1">
                Riwayat Pengajuan
            </h1>

            <p class="text-blue-100 mt-2">
                Lihat seluruh riwayat permohonan layanan yang telah Anda ajukan.
            </p>
        </div>


        <!-- LOADING -->
        <div
            v-if="loading"
            class="bg-white rounded-2xl border border-gray-100 p-8 mt-6 text-center"
        >
            <p class="text-gray-500">
                Memuat riwayat pengajuan...
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
                class="mt-4 px-4 py-2 rounded-lg bg-[#005AA7] text-white text-sm hover:bg-[#004b8d]"
            >
                Coba Lagi
            </button>
        </div>


        <!-- CONTENT -->
        <template v-else>

            <!-- SUMMARY -->
            <div
                class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-6"
            >

                <div
                    class="bg-white rounded-2xl border border-gray-100 p-5"
                >
                    <p class="text-sm text-gray-500">
                        Total Pengajuan
                    </p>

                    <p class="text-2xl font-bold text-gray-800 mt-2">
                        {{ data?.statistics?.total_pengajuan ?? 0 }}
                    </p>
                </div>


                <div
                    class="bg-white rounded-2xl border border-gray-100 p-5"
                >
                    <p class="text-sm text-gray-500">
                        Sedang Diproses
                    </p>

                    <p class="text-2xl font-bold text-blue-600 mt-2">
                        {{ data?.statistics?.diproses ?? 0 }}
                    </p>
                </div>


                <div
                    class="bg-white rounded-2xl border border-gray-100 p-5"
                >
                    <p class="text-sm text-gray-500">
                        Selesai
                    </p>

                    <p class="text-2xl font-bold text-green-600 mt-2">
                        {{ data?.statistics?.selesai ?? 0 }}
                    </p>
                </div>

            </div>


            <!-- RIWAYAT -->
            <div
                class="bg-white rounded-2xl border border-gray-100 mt-6 overflow-hidden"
            >

                <div
                    class="p-6 border-b border-gray-100"
                >
                    <h2 class="font-bold text-gray-800">
                        Daftar Pengajuan
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Pengajuan layanan yang terdaftar pada akun Anda.
                    </p>
                </div>


                <!-- EMPTY -->
                <div
                    v-if="!latestApplications.length"
                    class="p-12 text-center"
                >

                    <div class="text-4xl mb-3">
                        📭
                    </div>

                    <p class="text-gray-500">
                        Belum ada pengajuan.
                    </p>

                    <router-link
                        to="/layanan"
                        class="inline-block mt-4 px-5 py-2.5 rounded-lg bg-[#005AA7] text-white text-sm hover:bg-[#004b8d]"
                    >
                        Ajukan Layanan
                    </router-link>

                </div>


                <!-- TABLE -->
                <div
                    v-else
                    class="overflow-x-auto"
                >

                    <table class="w-full text-sm">

                        <thead>
                            <tr
                                class="bg-gray-50 border-b border-gray-100"
                            >
                                <th
                                    class="text-left px-6 py-4 font-semibold text-gray-600"
                                >
                                    No. Tiket
                                </th>

                                <th
                                    class="text-left px-6 py-4 font-semibold text-gray-600"
                                >
                                    Layanan
                                </th>

                                <th
                                    class="text-left px-6 py-4 font-semibold text-gray-600"
                                >
                                    Tanggal
                                </th>

                                <th
                                    class="text-left px-6 py-4 font-semibold text-gray-600"
                                >
                                    Status
                                </th>

                                <th
                                    class="text-right px-6 py-4 font-semibold text-gray-600"
                                >
                                    Aksi
                                </th>
                            </tr>
                        </thead>


                        <tbody>

                            <tr
                                v-for="pengajuan in latestApplications"
                                :key="pengajuan.id"
                                class="border-b border-gray-100 last:border-0 hover:bg-gray-50"
                            >

                                <td class="px-6 py-4">
                                    <span
                                        class="font-medium text-gray-800"
                                    >
                                        {{ pengajuan.nomor_tiket }}
                                    </span>
                                </td>


                                <td class="px-6 py-4">

                                    <p class="font-medium text-gray-800">
                                        {{ pengajuan.layanan || '-' }}
                                    </p>

                                </td>


                                <td class="px-6 py-4 text-gray-500">
                                    {{ formatDate(
                                        pengajuan.tanggal_pengajuan
                                    ) }}
                                </td>


                                <td class="px-6 py-4">

                                    <span
                                        class="inline-block px-3 py-1.5 rounded-full text-xs font-medium"
                                        :class="statusClass(
                                            pengajuan.status
                                        )"
                                    >
                                        {{
                                            statusLabel(
                                                pengajuan.status
                                            )
                                        }}
                                    </span>

                                </td>


                                <td class="px-6 py-4 text-right">

                                    <button
                                        type="button"
                                        @click="showDetail(pengajuan)"
                                        class="text-[#005AA7] hover:underline text-sm font-medium"
                                    >
                                        Detail
                                    </button>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </template>


        <!-- DETAIL MODAL -->
        <div
            v-if="selectedPengajuan"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
        >

            <!-- BACKDROP -->
            <div
                class="absolute inset-0 bg-black/40"
                @click="closeDetail"
            ></div>


            <!-- MODAL -->
            <div
                class="relative bg-white rounded-2xl w-full max-w-lg p-6 shadow-xl"
            >

                <div
                    class="flex items-start justify-between gap-4"
                >

                    <div>

                        <p class="text-xs text-gray-400">
                            Nomor Tiket
                        </p>

                        <h3 class="font-bold text-lg text-gray-800">
                            {{ selectedPengajuan.nomor_tiket }}
                        </h3>

                    </div>


                    <button
                        type="button"
                        @click="closeDetail"
                        class="text-gray-400 hover:text-gray-600 text-xl"
                    >
                        ×
                    </button>

                </div>


                <div class="space-y-4 mt-6">

                    <div>
                        <p class="text-xs text-gray-400">
                            Layanan
                        </p>

                        <p class="font-medium text-gray-800 mt-1">
                            {{ selectedPengajuan.layanan || '-' }}
                        </p>
                    </div>


                    <div>
                        <p class="text-xs text-gray-400">
                            Tanggal Pengajuan
                        </p>

                        <p class="font-medium text-gray-800 mt-1">
                            {{
                                formatDate(
                                    selectedPengajuan.tanggal_pengajuan
                                )
                            }}
                        </p>
                    </div>


                    <div>
                        <p class="text-xs text-gray-400">
                            Status
                        </p>

                        <span
                            class="inline-block mt-1 px-3 py-1.5 rounded-full text-xs font-medium"
                            :class="statusClass(
                                selectedPengajuan.status
                            )"
                        >
                            {{
                                statusLabel(
                                    selectedPengajuan.status
                                )
                            }}
                        </span>
                    </div>

                </div>


                <button
                    type="button"
                    @click="closeDetail"
                    class="w-full mt-6 px-4 py-2.5 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200"
                >
                    Tutup
                </button>

            </div>

        </div>

    </div>
</template>


<script setup>
import {
    computed,
    onMounted,
    ref,
} from 'vue'

import { useDashboard } from '../../composables/useDashboard'


const {
    data,
    loading,
    error,
    fetchDashboard,
} = useDashboard()


const selectedPengajuan = ref(null)


const latestApplications = computed(() => {
    return data.value?.pengajuan_terbaru || []
})


function showDetail(pengajuan) {
    selectedPengajuan.value = pengajuan
}


function closeDetail() {
    selectedPengajuan.value = null
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


onMounted(() => {
    fetchDashboard()
})
</script>