<template>
    <div>

        <!-- BACK -->
        <button
            type="button"
            @click="goBack"
            class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#005AA7] mb-5"
        >
            ← Kembali ke Permohonan
        </button>


        <!-- LOADING -->
        <div
            v-if="loading"
            class="bg-white rounded-2xl border border-gray-100 p-10 text-center"
        >
            <p class="text-gray-500">
                Memuat detail permohonan...
            </p>
        </div>


        <!-- ERROR -->
        <div
            v-else-if="error"
            class="bg-white rounded-2xl border border-red-100 p-8 text-center"
        >
            <div class="text-4xl mb-3">
                ⚠️
            </div>

            <p class="text-red-500 font-medium">
                {{ error }}
            </p>

            <button
                type="button"
                @click="loadDetail"
                class="mt-4 px-4 py-2 rounded-lg bg-[#005AA7] text-white text-sm"
            >
                Coba Lagi
            </button>
        </div>


        <!-- CONTENT -->
        <template v-else-if="pengajuan">

            <!-- HEADER -->
            <div
                class="bg-white rounded-2xl border border-gray-100 p-6"
            >

                <div
                    class="flex flex-col md:flex-row md:items-start md:justify-between gap-5"
                >

                    <div>

                        <p class="text-sm text-gray-400">
                            Nomor Tiket
                        </p>

                        <h1
                            class="text-2xl font-bold text-gray-800 mt-1"
                        >
                            {{ pengajuan.nomor_tiket }}
                        </h1>

                        <p class="text-sm text-gray-500 mt-2">
                            {{
                                formatDate(
                                    pengajuan.tanggal_pengajuan
                                )
                            }}
                        </p>

                    </div>


                    <span
                        class="self-start px-4 py-2 rounded-full text-sm font-medium"
                        :class="statusClass(pengajuan.status)"
                    >
                        {{ statusLabel(pengajuan.status) }}
                    </span>

                </div>

            </div>


            <!-- INFORMASI -->
            <div
                class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6"
            >

                <!-- PEMOHON -->
                <div
                    class="bg-white rounded-2xl border border-gray-100 p-6"
                >

                    <h2 class="font-bold text-gray-800">
                        Informasi Pemohon
                    </h2>

                    <div class="space-y-4 mt-5">

                        <div>
                            <p class="text-xs text-gray-400">
                                Nama
                            </p>

                            <p class="font-medium text-gray-800 mt-1">
                                {{ pengajuan.user?.name || '-' }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs text-gray-400">
                                Email
                            </p>

                            <p class="font-medium text-gray-800 mt-1">
                                {{ pengajuan.user?.email || '-' }}
                            </p>
                        </div>

                    </div>

                </div>


                <!-- LAYANAN -->
                <div
                    class="bg-white rounded-2xl border border-gray-100 p-6"
                >

                    <h2 class="font-bold text-gray-800">
                        Informasi Layanan
                    </h2>

                    <div class="space-y-4 mt-5">

                        <div>
                            <p class="text-xs text-gray-400">
                                Nama Layanan
                            </p>

                            <p class="font-medium text-gray-800 mt-1">
                                {{ pengajuan.layanan?.nama || '-' }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs text-gray-400">
                                Tanggal Pengajuan
                            </p>

                            <p class="font-medium text-gray-800 mt-1">
                                {{
                                    formatDate(
                                        pengajuan.tanggal_pengajuan
                                    )
                                }}
                            </p>
                        </div>


                        <div v-if="pengajuan.tanggal_selesai">
                            <p class="text-xs text-gray-400">
                                Tanggal Selesai
                            </p>

                            <p class="font-medium text-gray-800 mt-1">
                                {{
                                    formatDate(
                                        pengajuan.tanggal_selesai
                                    )
                                }}
                            </p>
                        </div>

                    </div>

                </div>

            </div>


            <!-- DOKUMEN -->
            <div
                class="bg-white rounded-2xl border border-gray-100 p-6 mt-6"
            >

                <div>
                    <h2 class="font-bold text-gray-800">
                        Dokumen Persyaratan
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Dokumen yang berkaitan dengan permohonan ini.
                    </p>
                </div>


                <div
                    v-if="!pengajuan.dokumens?.length"
                    class="mt-6 p-8 rounded-xl bg-gray-50 text-center"
                >

                    <div class="text-3xl mb-2">
                        📄
                    </div>

                    <p class="text-sm text-gray-500">
                        Belum ada dokumen yang tersedia.
                    </p>

                </div>


                <div
                    v-else
                    class="mt-6 space-y-3"
                >

                    <div
                        v-for="dokumen in pengajuan.dokumens"
                        :key="dokumen.id"
                        class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 p-4 rounded-xl border border-gray-100"
                    >

                        <div>

                            <p class="font-medium text-gray-800">
                                {{
                                    dokumen.persyaratan?.nama_syarat ||
                                    'Dokumen'
                                }}
                            </p>

                            <p class="text-xs text-gray-400 mt-1">
                                {{ dokumen.file || 'Tidak ada file' }}
                            </p>

                        </div>


                        <span
                            v-if="dokumen.text"
                            class="text-xs text-gray-500"
                        >
                            Data tersedia
                        </span>

                    </div>

                </div>

            </div>


            <!-- RIWAYAT DISPOSISI -->
            <div
                class="bg-white rounded-2xl border border-gray-100 p-6 mt-6"
            >

                <div>
                    <h2 class="font-bold text-gray-800">
                        Riwayat Disposisi
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Riwayat penanganan permohonan.
                    </p>
                </div>


                <div
                    v-if="!pengajuan.riwayat_disposisis?.length"
                    class="mt-6 p-8 rounded-xl bg-gray-50 text-center"
                >

                    <div class="text-3xl mb-2">
                        📋
                    </div>

                    <p class="text-sm text-gray-500">
                        Belum ada riwayat disposisi.
                    </p>

                </div>


                <div
                    v-else
                    class="mt-6"
                >

                    <div
                        v-for="(
                            riwayat,
                            index
                        ) in pengajuan.riwayat_disposisis"
                        :key="riwayat.id"
                        class="relative pl-8 pb-7 last:pb-0"
                    >

                        <!-- LINE -->
                        <div
                            v-if="
                                index <
                                pengajuan.riwayat_disposisis.length - 1
                            "
                            class="absolute left-[7px] top-4 bottom-0 w-px bg-gray-200"
                        ></div>


                        <!-- DOT -->
                        <div
                            class="absolute left-0 top-1.5 w-4 h-4 rounded-full bg-[#005AA7] border-4 border-blue-100"
                        ></div>


                        <div>

                            <p class="font-medium text-gray-800">
                                {{
                                    riwayat.tim_kerja?.nama_tim ||
                                    'Tim Kerja'
                                }}
                            </p>

                            <p
                                v-if="riwayat.handled_by"
                                class="text-sm text-gray-500 mt-1"
                            >
                                Ditangani oleh:
                                {{
                                    riwayat.handled_by.name
                                }}
                            </p>

                            <p class="text-sm text-gray-500 mt-1">
                                Status:
                                {{
                                    statusLabel(
                                        riwayat.status
                                    )
                                }}
                            </p>

                            <p
                                v-if="riwayat.keterangan"
                                class="text-sm text-gray-600 mt-2"
                            >
                                {{ riwayat.keterangan }}
                            </p>

                            <p
                                v-if="riwayat.tanggal_disposisi"
                                class="text-xs text-gray-400 mt-2"
                            >
                                {{
                                    formatDateTime(
                                        riwayat.tanggal_disposisi
                                    )
                                }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ACTION PLACEHOLDER -->
            <div
                v-if="
                    authStore.user?.role === 'helpdesk' ||
                    authStore.user?.role === 'pegawai'
                "
                class="bg-blue-50 border border-blue-100 rounded-2xl p-6 mt-6"
            >

                <h3 class="font-bold text-gray-800">
                    Tindakan Permohonan
                </h3>

                <p class="text-sm text-gray-600 mt-1">
                    Fitur proses dan disposisi akan tersedia pada tahap berikutnya.
                </p>

            </div>

        </template>

    </div>
</template>


<script setup>
import {
    onMounted,
    ref,
} from 'vue'

import { useRoute, useRouter } from 'vue-router'

import axios from '../../lib/axios'

import { useAuthStore } from '../../stores/auth'


const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()


const pengajuan = ref(null)

const loading = ref(true)
const error = ref('')


async function loadDetail() {
    loading.value = true
    error.value = ''

    try {
        const response = await axios.get(
            `/api/permohonan/${route.params.id}`
        )

        pengajuan.value =
            response.data.data

    } catch (err) {

        console.error(err)

        error.value =
            err.response?.data?.message ||
            'Gagal mengambil detail permohonan.'

    } finally {
        loading.value = false
    }
}


function goBack() {
    router.push('/dashboard/permohonan')
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

    return classes[status] ||
        'bg-gray-100 text-gray-600'
}


function formatDate(date) {
    if (!date) {
        return '-'
    }

    return new Date(date).toLocaleDateString(
        'id-ID',
        {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
        }
    )
}


function formatDateTime(date) {
    if (!date) {
        return '-'
    }

    return new Date(date).toLocaleString(
        'id-ID',
        {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        }
    )
}


onMounted(() => {
    loadDetail()
})
</script>