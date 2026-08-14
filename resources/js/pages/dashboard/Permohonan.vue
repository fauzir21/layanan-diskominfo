<template>
    <div>

        <!-- HEADER -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Permohonan
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Kelola dan pantau permohonan layanan.
            </p>
        </div>


        <!-- FILTER & SEARCH -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 mb-6">

            <div
                class="flex flex-col lg:flex-row lg:items-center gap-4"
            >

                <!-- SEARCH -->
                <div class="flex-1">

                    <label
                        for="search"
                        class="sr-only"
                    >
                        Cari permohonan
                    </label>

                    <div class="relative">

                        <input
                            id="search"
                            v-model="search"
                            type="text"
                            placeholder="Cari nomor tiket atau nama pemohon..."
                            class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400"
                        />

                    </div>

                </div>


                <!-- STATUS -->
                <div class="w-full lg:w-56">

                    <select
                        v-model="statusFilter"
                        class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm bg-white outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400"
                    >

                        <option value="">
                            Semua Status
                        </option>

                        <option value="menunggu_diproses">
                            Menunggu Diproses
                        </option>

                        <option value="diproses">
                            Diproses
                        </option>

                        <option value="perbaikan">
                            Perbaikan
                        </option>

                        <option value="selesai">
                            Selesai
                        </option>

                        <option value="ditolak">
                            Ditolak
                        </option>

                    </select>

                </div>


                <!-- REFRESH -->
                <button
                    type="button"
                    @click="loadPermohonan"
                    :disabled="loading"
                    class="px-5 py-3 rounded-lg bg-[#005AA7] text-white text-sm font-medium hover:bg-[#004b8d] disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ loading ? 'Memuat...' : 'Refresh' }}
                </button>

            </div>

        </div>


        <!-- ERROR -->
        <div
            v-if="error"
            class="bg-red-50 border border-red-100 text-red-600 rounded-xl p-4 mb-6 text-sm"
        >
            {{ error }}

            <button
                type="button"
                @click="loadPermohonan"
                class="ml-2 underline font-medium"
            >
                Coba lagi
            </button>
        </div>


        <!-- LOADING -->
        <div
            v-if="loading"
            class="bg-white rounded-2xl border border-gray-100 p-10 text-center"
        >

            <p class="text-gray-500">
                Memuat data permohonan...
            </p>

        </div>


        <!-- TABLE -->
        <div
            v-else
            class="bg-white rounded-2xl border border-gray-100 overflow-hidden"
        >

            <!-- DESKTOP TABLE -->
            <div class="overflow-x-auto">

                <table class="w-full min-w-[850px]">

                    <thead class="bg-gray-50 border-b border-gray-100">

                        <tr>

                            <th
                                class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase"
                            >
                                No. Tiket
                            </th>

                            <th
                                class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase"
                            >
                                Pemohon
                            </th>

                            <th
                                class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase"
                            >
                                Layanan
                            </th>

                            <th
                                class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase"
                            >
                                Tanggal
                            </th>

                            <th
                                class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase"
                            >
                                Status
                            </th>

                            <th
                                class="text-right px-6 py-4 text-xs font-semibold text-gray-500 uppercase"
                            >
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        <tr
                            v-for="pengajuan in filteredPermohonans"
                            :key="pengajuan.id"
                            class="hover:bg-gray-50 transition"
                        >

                            <!-- TIKET -->
                            <td class="px-6 py-5">

                                <p class="font-semibold text-gray-800">
                                    {{ pengajuan.nomor_tiket || '-' }}
                                </p>

                            </td>


                            <!-- PEMOHON -->
                            <td class="px-6 py-5">

                                <p class="font-medium text-gray-800">
                                    {{ pengajuan.user?.name || '-' }}
                                </p>

                                <p
                                    v-if="pengajuan.user?.email"
                                    class="text-xs text-gray-400 mt-1"
                                >
                                    {{ pengajuan.user.email }}
                                </p>

                            </td>


                            <!-- LAYANAN -->
                            <td class="px-6 py-5">

                                <p class="text-gray-700">
                                    {{ pengajuan.layanan?.nama || '-' }}
                                </p>

                            </td>


                            <!-- TANGGAL -->
                            <td class="px-6 py-5">

                                <p class="text-sm text-gray-600">
                                    {{
                                        formatDate(
                                            pengajuan.tanggal_pengajuan
                                        )
                                    }}
                                </p>

                            </td>


                            <!-- STATUS -->
                            <td class="px-6 py-5">

                                <span
                                    class="inline-flex px-3 py-1.5 rounded-full text-xs font-medium"
                                    :class="
                                        statusClass(
                                            pengajuan.status
                                        )
                                    "
                                >
                                    {{
                                        statusLabel(
                                            pengajuan.status
                                        )
                                    }}
                                </span>

                            </td>


                            <!-- AKSI -->
                            <td class="px-6 py-5 text-right">

                                <button
                                    type="button"
                                    @click="openDetail(pengajuan.id)"
                                    class="text-sm font-medium text-[#005AA7] hover:underline"
                                >
                                    Detail
                                </button>

                            </td>

                        </tr>


                        <!-- EMPTY -->
                        <tr
                            v-if="filteredPermohonans.length === 0"
                        >

                            <td
                                colspan="6"
                                class="px-6 py-14 text-center"
                            >

                                <div class="text-4xl mb-3">
                                    📋
                                </div>

                                <h3
                                    class="font-semibold text-gray-800"
                                >
                                    Tidak ada permohonan
                                </h3>

                                <p
                                    class="text-sm text-gray-500 mt-1"
                                >
                                    {{
                                        search ||
                                        statusFilter
                                            ? 'Tidak ada data yang sesuai dengan filter.'
                                            : 'Belum ada permohonan yang tersedia.'
                                    }}
                                </p>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>


            <!-- FOOTER -->
            <div
                v-if="filteredPermohonans.length > 0"
                class="border-t border-gray-100 px-6 py-4"
            >

                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2"
                >

                    <p class="text-sm text-gray-500">
                        Menampilkan
                        <span class="font-medium text-gray-700">
                            {{ filteredPermohonans.length }}
                        </span>
                        permohonan
                    </p>

                    <p
                        v-if="pagination.last_page"
                        class="text-xs text-gray-400"
                    >
                        Halaman {{ pagination.current_page }}
                        dari {{ pagination.last_page }}
                    </p>

                </div>

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

import {
    useRouter,
} from 'vue-router'

import axios from '../../lib/axios'


const router = useRouter()


/*
|--------------------------------------------------------------------------
| STATE
|--------------------------------------------------------------------------
*/

const permohonans = ref([])

const loading = ref(true)

const error = ref('')

const search = ref('')

const statusFilter = ref('')


const pagination = ref({
    current_page: 1,
    last_page: 1,
    total: 0,
})


/*
|--------------------------------------------------------------------------
| LOAD DATA
|--------------------------------------------------------------------------
*/

async function loadPermohonan() {

    loading.value = true
    error.value = ''

    try {

        const response = await axios.get(
            '/api/permohonan'
        )

        /*
         * Endpoint Laravel menggunakan pagination.
         *
         * Struktur normal:
         *
         * {
         *     current_page,
         *     data: [],
         *     last_page,
         *     total
         * }
         */

        const data = response.data

        permohonans.value =
            data.data ||
            []

        pagination.value = {
            current_page:
                data.current_page || 1,

            last_page:
                data.last_page || 1,

            total:
                data.total ||
                permohonans.value.length,
        }

    } catch (err) {

        console.error(err)

        error.value =
            err.response?.data?.message ||
            'Gagal mengambil data permohonan.'

        permohonans.value = []

    } finally {

        loading.value = false

    }
}


/*
|--------------------------------------------------------------------------
| FILTER
|--------------------------------------------------------------------------
*/

const filteredPermohonans = computed(() => {

    let data = [...permohonans.value]


    /*
     * STATUS
     */

    if (statusFilter.value) {

        data = data.filter(
            item =>
                item.status ===
                statusFilter.value
        )

    }


    /*
     * SEARCH
     */

    const keyword =
        search.value
            .trim()
            .toLowerCase()


    if (keyword) {

        data = data.filter(item => {

            const nomorTiket =
                item.nomor_tiket
                    ?.toLowerCase() || ''

            const namaPemohon =
                item.user?.name
                    ?.toLowerCase() || ''

            const emailPemohon =
                item.user?.email
                    ?.toLowerCase() || ''

            const namaLayanan =
                item.layanan?.nama
                    ?.toLowerCase() || ''

            return (
                nomorTiket.includes(keyword) ||
                namaPemohon.includes(keyword) ||
                emailPemohon.includes(keyword) ||
                namaLayanan.includes(keyword)
            )

        })

    }


    return data

})


/*
|--------------------------------------------------------------------------
| DETAIL
|--------------------------------------------------------------------------
*/

function openDetail(id) {

    router.push(
        `/dashboard/permohonan/${id}`
    )

}


/*
|--------------------------------------------------------------------------
| STATUS LABEL
|--------------------------------------------------------------------------
*/

function statusLabel(status) {

    const labels = {

        menunggu_diproses:
            'Menunggu Diproses',

        diproses:
            'Diproses',

        perbaikan:
            'Perbaikan',

        selesai:
            'Selesai',

        ditolak:
            'Ditolak',

    }

    return labels[status] || status || '-'

}


/*
|--------------------------------------------------------------------------
| STATUS CLASS
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


/*
|--------------------------------------------------------------------------
| INITIAL LOAD
|--------------------------------------------------------------------------
*/

onMounted(() => {

    loadPermohonan()

})
</script>