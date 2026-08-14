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
                            {{ formatDate(pengajuan.tanggal_pengajuan) }}
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
                                {{ formatDate(pengajuan.tanggal_pengajuan) }}
                            </p>
                        </div>


                        <div v-if="pengajuan.tanggal_selesai">
                            <p class="text-xs text-gray-400">
                                Tanggal Selesai
                            </p>

                            <p class="font-medium text-gray-800 mt-1">
                                {{ formatDate(pengajuan.tanggal_selesai) }}
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
                                {{ riwayat.handled_by.name }}
                            </p>

                            <p class="text-sm text-gray-500 mt-1">
                                Status:
                                {{ statusLabel(riwayat.status) }}
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
                                {{ formatDateTime(riwayat.tanggal_disposisi) }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- FORM DISPOSISI -->
            <div
                v-if="
                    authStore.user?.role === 'helpdesk' &&
                    pengajuan.status === 'menunggu_diproses'
                "
                class="bg-white rounded-2xl border border-gray-100 p-6 mt-6"
            >

                <div>
                    <h2 class="font-bold text-gray-800">
                        Disposisi Permohonan
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Teruskan permohonan kepada Tim Kerja yang sesuai.
                    </p>
                </div>


                <!-- ERROR DISPOSISI -->
                <div
                    v-if="disposisiError"
                    class="mt-5 bg-red-50 border border-red-100 text-red-600 rounded-xl p-4 text-sm"
                >
                    {{ disposisiError }}
                </div>


                <!-- SUCCESS -->
                <div
                    v-if="disposisiSuccess"
                    class="mt-5 bg-green-50 border border-green-100 text-green-700 rounded-xl p-4 text-sm"
                >
                    {{ disposisiSuccess }}
                </div>


                <form
                    class="mt-6 space-y-5"
                    @submit.prevent="submitDisposisi"
                >

                    <!-- TIM KERJA -->
                    <div>

                        <label
                            for="timKerja"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Tim Kerja
                        </label>

                        <select
                            id="timKerja"
                            v-model="disposisi.tim_kerja_id"
                            required
                            class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm bg-white outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400"
                        >

                            <option value="" disabled>
                                Pilih Tim Kerja
                            </option>

                            <option
                                v-for="tim in timKerjas"
                                :key="tim.id"
                                :value="tim.id"
                            >
                                {{ tim.nama_tim }}
                            </option>

                        </select>

                    </div>


                    <!-- KETERANGAN -->
                    <div>

                        <label
                            for="keterangan"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Keterangan
                        </label>

                        <textarea
                            id="keterangan"
                            v-model="disposisi.keterangan"
                            rows="4"
                            placeholder="Tuliskan instruksi atau keterangan disposisi..."
                            class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm outline-none resize-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400"
                        ></textarea>

                    </div>


                    <!-- SUBMIT -->
                    <div class="flex justify-end">

                        <button
                            type="submit"
                            :disabled="
                                submitting ||
                                !disposisi.tim_kerja_id
                            "
                            class="px-6 py-3 rounded-lg bg-[#005AA7] text-white text-sm font-medium hover:bg-[#004b8d] disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {{
                                submitting
                                    ? 'Mengirim...'
                                    : 'Kirim Disposisi'
                            }}
                        </button>

                    </div>

                </form>

            </div>


            <!-- STATUS SUDAH DIPROSES -->
            <div
                v-else-if="
                    authStore.user?.role === 'helpdesk' &&
                    pengajuan.status !== 'menunggu_diproses'
                "
                class="bg-blue-50 border border-blue-100 rounded-2xl p-6 mt-6"
            >

                <h3 class="font-bold text-gray-800">
                    Tindakan Permohonan
                </h3>

                <p class="text-sm text-gray-600 mt-1">
                    Permohonan ini sudah memiliki proses disposisi.
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

import {
    useRoute,
    useRouter,
} from 'vue-router'

import axios from '../../lib/axios'

import { useAuthStore } from '../../stores/auth'


const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()


const pengajuan = ref(null)

const loading = ref(true)
const error = ref('')


/*
|--------------------------------------------------------------------------
| DISPOSISI
|--------------------------------------------------------------------------
*/

const timKerjas = ref([])

const disposisi = ref({
    tim_kerja_id: '',
    keterangan: '',
})

const submitting = ref(false)

const disposisiError = ref('')

const disposisiSuccess = ref('')


/*
|--------------------------------------------------------------------------
| LOAD DETAIL
|--------------------------------------------------------------------------
*/

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


/*
|--------------------------------------------------------------------------
| LOAD TIM KERJA
|--------------------------------------------------------------------------
*/

async function loadTimKerja() {
    try {

        const response = await axios.get(
            '/api/tim-kerja'
        )

        timKerjas.value =
            response.data.data ||
            response.data ||
            []

    } catch (err) {

        console.error(err)

        disposisiError.value =
            err.response?.data?.message ||
            'Gagal mengambil data Tim Kerja.'

    }
}


/*
|--------------------------------------------------------------------------
| SUBMIT DISPOSISI
|--------------------------------------------------------------------------
*/

async function submitDisposisi() {

    if (!disposisi.value.tim_kerja_id) {
        disposisiError.value =
            'Silakan pilih Tim Kerja terlebih dahulu.'

        return
    }

    submitting.value = true
    disposisiError.value = ''
    disposisiSuccess.value = ''

    try {

        await axios.post(
            `/api/permohonan/${route.params.id}/disposisi`,
            {
                tim_kerja_id:
                    disposisi.value.tim_kerja_id,

                keterangan:
                    disposisi.value.keterangan,
            }
        )

        disposisiSuccess.value =
            'Disposisi berhasil dikirim.'

        disposisi.value = {
            tim_kerja_id: '',
            keterangan: '',
        }

        await loadDetail()

    } catch (err) {

        console.error(err)

        disposisiError.value =
            err.response?.data?.message ||
            'Gagal mengirim disposisi.'

    } finally {

        submitting.value = false

    }
}


/*
|--------------------------------------------------------------------------
| NAVIGATION
|--------------------------------------------------------------------------
*/

function goBack() {
    router.push('/dashboard/permohonan')
}


/*
|--------------------------------------------------------------------------
| STATUS
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


/*
|--------------------------------------------------------------------------
| DATE
|--------------------------------------------------------------------------
*/

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


/*
|--------------------------------------------------------------------------
| INITIAL LOAD
|--------------------------------------------------------------------------
*/

onMounted(async () => {

    await loadDetail()

    if (authStore.user?.role === 'helpdesk') {
        await loadTimKerja()
    }

})
</script>