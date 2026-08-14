<template>
    <div>

        <!-- HEADER -->
        <div
            class="bg-white rounded-2xl border border-gray-100 p-6"
        >
            <h1 class="text-2xl font-bold text-gray-800">
                Permohonan
            </h1>

            <p class="text-gray-500 mt-1">
                Kelola dan pantau permohonan layanan.
            </p>
        </div>


        <!-- FILTER -->
        <div
            class="bg-white rounded-2xl border border-gray-100 p-5 mt-6"
        >

            <div
                class="flex flex-col md:flex-row gap-4"
            >

                <input
                    v-model="search"
                    type="text"
                    placeholder="Cari nomor tiket, nama, atau layanan..."
                    class="flex-1 border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-100"
                    @keyup.enter="loadData"
                />


                <select
                    v-model="status"
                    class="border border-gray-200 rounded-lg px-4 py-2.5 text-sm bg-white"
                    @change="loadData"
                >
                    <option value="">
                        Semua Status
                    </option>

                    <option value="menunggu_diproses">
                        Menunggu
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


                <button
                    type="button"
                    @click="loadData"
                    class="px-5 py-2.5 rounded-lg bg-[#005AA7] text-white text-sm font-medium hover:bg-[#004b8d]"
                >
                    Cari
                </button>

            </div>

        </div>


        <!-- ERROR -->
        <div
            v-if="error"
            class="bg-red-50 border border-red-100 text-red-600 rounded-xl p-4 mt-6"
        >
            {{ error }}
        </div>


        <!-- TABLE -->
        <div
            class="bg-white rounded-2xl border border-gray-100 mt-6 overflow-hidden"
        >

            <div
                v-if="loading"
                class="p-10 text-center text-gray-500"
            >
                Memuat permohonan...
            </div>


            <div
                v-else-if="!items.length"
                class="p-12 text-center"
            >

                <div class="text-4xl mb-3">
                    📭
                </div>

                <p class="text-gray-500">
                    Tidak ada permohonan ditemukan.
                </p>

            </div>


            <div
                v-else
                class="overflow-x-auto"
            >

                <table class="w-full text-sm">

                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">

                            <th
                                class="text-left px-6 py-4 font-semibold text-gray-600"
                            >
                                No. Tiket
                            </th>

                            <th
                                class="text-left px-6 py-4 font-semibold text-gray-600"
                            >
                                Pemohon
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
                            v-for="item in items"
                            :key="item.id"
                            class="border-b border-gray-100 last:border-0 hover:bg-gray-50"
                        >

                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-800">
                                    {{ item.nomor_tiket }}
                                </span>
                            </td>


                            <td class="px-6 py-4">

                                <p class="font-medium text-gray-800">
                                    {{ item.user?.name || '-' }}
                                </p>

                                <p class="text-xs text-gray-400">
                                    {{ item.user?.email || '' }}
                                </p>

                            </td>


                            <td class="px-6 py-4 text-gray-700">
                                {{ item.layanan?.nama || '-' }}
                            </td>


                            <td class="px-6 py-4 text-gray-500">
                                {{ formatDate(
                                    item.tanggal_pengajuan
                                ) }}
                            </td>


                            <td class="px-6 py-4">

                                <span
                                    class="inline-block px-3 py-1.5 rounded-full text-xs font-medium"
                                    :class="statusClass(item.status)"
                                >
                                    {{ statusLabel(item.status) }}
                                </span>

                            </td>


                            <td class="px-6 py-4 text-right">

                                <button
                                    type="button"
                                    @click="openDetail(item.id)"
                                    class="text-[#005AA7] hover:underline font-medium"
                                >
                                    Detail
                                </button>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>


            <!-- PAGINATION -->
            <div
                v-if="pagination.last_page > 1"
                class="flex items-center justify-between p-5 border-t border-gray-100"
            >

                <p class="text-xs text-gray-500">
                    Halaman {{ pagination.current_page }}
                    dari {{ pagination.last_page }}
                </p>


                <div class="flex gap-2">

                    <button
                        type="button"
                        :disabled="pagination.current_page <= 1"
                        @click="changePage(
                            pagination.current_page - 1
                        )"
                        class="px-3 py-2 rounded-lg border text-sm disabled:opacity-40"
                    >
                        Sebelumnya
                    </button>


                    <button
                        type="button"
                        :disabled="
                            pagination.current_page >=
                            pagination.last_page
                        "
                        @click="changePage(
                            pagination.current_page + 1
                        )"
                        class="px-3 py-2 rounded-lg border text-sm disabled:opacity-40"
                    >
                        Berikutnya
                    </button>

                </div>

            </div>

        </div>

    </div>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from '../../lib/axios'

const router = useRouter()
const items = ref([])
const loading = ref(false)
const error = ref('')

const search = ref('')
const status = ref('')

const pagination = ref({
    current_page: 1,
    last_page: 1,
})


async function loadData(page = 1) {
    loading.value = true
    error.value = ''


    try {
        const response = await axios.get(
            '/api/permohonan',
            {
                params: {
                    page,
                    search: search.value || undefined,
                    status: status.value || undefined,
                },
            }
        )


        items.value = response.data.data || []


        pagination.value = {
            current_page:
                response.data.current_page || 1,

            last_page:
                response.data.last_page || 1,
        }

    } catch (err) {

        console.error(err)

        error.value =
            err.response?.data?.message ||
            'Gagal mengambil data permohonan.'

    } finally {
        loading.value = false
    }
}


function changePage(page) {
    if (
        page < 1 ||
        page > pagination.value.last_page
    ) {
        return
    }

    loadData(page)
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


function openDetail(id) {
    router.push(`/dashboard/permohonan/${id}`)
}


onMounted(() => {
    loadData()
})
</script>