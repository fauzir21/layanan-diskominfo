<template>
    <DashboardLayout>

        <div v-if="loading" class="text-center text-gray-400 py-16">
            Memuat...
        </div>

        <div v-else-if="!pengajuan" class="bg-white rounded-2xl border border-gray-100 p-10 text-center text-gray-500">
            Pengajuan tidak ditemukan atau Anda tidak punya akses.
        </div>

        <div v-else class="bg-white rounded-2xl border border-gray-100 p-6 sm:p-8">

            <router-link
                to="/dashboard/permohonan"
                class="inline-flex items-center gap-2 text-[#005AA7] hover:underline text-sm font-medium"
            >
                ← Kembali
            </router-link>

            <div class="mt-4 flex items-center justify-between flex-wrap gap-3">
                <div>
                    <p class="text-sm text-gray-500">Nomor Tiket</p>
                    <p class="text-xl font-bold text-gray-800">{{ pengajuan.nomor_tiket }}</p>
                </div>

                <span
                    class="text-xs font-semibold px-3 py-1.5 rounded-full"
                    :class="statusBadgeClass(pengajuan.status)"
                >
                    {{ statusLabel(pengajuan.status) }}
                </span>
            </div>

            <p class="mt-4 text-sm text-gray-600">
                Layanan: <span class="font-medium text-gray-800">{{ pengajuan.layanan }}</span>
            </p>
            <p class="mt-1 text-sm text-gray-600">
                Diajukan: <span class="font-medium text-gray-800">{{ pengajuan.tanggal_pengajuan }}</span>
            </p>
            <p v-if="pengajuan.tanggal_selesai" class="mt-1 text-sm text-gray-600">
                Selesai: <span class="font-medium text-gray-800">{{ pengajuan.tanggal_selesai }}</span>
            </p>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-sm font-semibold text-gray-800 mb-4">Riwayat Status</p>

                <div
                    v-for="(riwayat, index) in pengajuan.riwayat"
                    :key="index"
                    class="flex gap-3 pb-4 last:pb-0"
                >
                    <div class="flex flex-col items-center">
                        <div class="w-2.5 h-2.5 rounded-full bg-[#005AA7] mt-1.5 shrink-0"></div>
                        <div
                            v-if="index < pengajuan.riwayat.length - 1"
                            class="w-px flex-1 bg-gray-200 mt-1"
                        ></div>
                    </div>
                    <div class="pb-1">
                        <p class="text-sm font-medium text-gray-800">{{ statusLabel(riwayat.status) }}</p>
                        <p v-if="riwayat.keterangan" class="text-sm text-gray-500 mt-0.5">{{ riwayat.keterangan }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ riwayat.tanggal_disposisi }}</p>
                    </div>
                </div>
            </div>

        </div>

    </DashboardLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from '../../lib/axios'
import DashboardLayout from '../../components/dashboard/DashboardLayout.vue'

const route = useRoute()
const pengajuan = ref(null)
const loading = ref(true)

const statusLabels = {
    menunggu_diproses: 'Menunggu Diproses',
    diproses: 'Diproses',
    perbaikan: 'Perlu Perbaikan',
    ditolak: 'Ditolak',
    selesai: 'Selesai',
}

const statusClasses = {
    menunggu_diproses: 'bg-yellow-100 text-yellow-700',
    diproses: 'bg-blue-100 text-blue-700',
    perbaikan: 'bg-orange-100 text-orange-700',
    ditolak: 'bg-red-100 text-red-700',
    selesai: 'bg-green-100 text-green-700',
}

function statusLabel(status) {
    return statusLabels[status] || status
}

function statusBadgeClass(status) {
    return statusClasses[status] || 'bg-gray-100 text-gray-700'
}

onMounted(async () => {
    try {
        const response = await axios.get(`/api/pengajuan/${route.params.id}`)
        pengajuan.value = response.data.data
    } catch (error) {
        pengajuan.value = null
    } finally {
        loading.value = false
    }
})
</script>