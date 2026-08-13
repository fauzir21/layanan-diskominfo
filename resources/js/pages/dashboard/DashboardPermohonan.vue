<template>
    <DashboardLayout>

        <!-- ===== PEMOHON ===== -->
        <div v-if="authStore.user?.role === 'user'">

            <div v-if="loading" class="text-center text-gray-400 py-16">
                Memuat permohonan...
            </div>

            <div v-else-if="pengajuans.length === 0" class="bg-white rounded-2xl border border-gray-100 p-10 text-center">
                <p class="text-gray-500">Anda belum pernah mengajukan permohonan.</p>
                <router-link
                    to="/layanan"
                    class="mt-4 inline-flex items-center gap-2 bg-[#005AA7] hover:bg-[#004b8c] transition text-white font-semibold px-5 py-2.5 rounded-xl"
                >
                    Lihat Layanan
                </router-link>
            </div>

            <div v-else class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-left">
                        <tr>
                            <th class="px-6 py-3 font-medium">Nomor Tiket</th>
                            <th class="px-6 py-3 font-medium">Layanan</th>
                            <th class="px-6 py-3 font-medium">Tanggal</th>
                            <th class="px-6 py-3 font-medium">Status</th>
                            <th class="px-6 py-3 font-medium"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="item in pengajuans"
                            :key="item.id"
                            class="border-t border-gray-100"
                        >
                            <td class="px-6 py-4 font-medium text-gray-800">{{ item.nomor_tiket }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ item.layanan }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ item.tanggal_pengajuan }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="text-xs font-semibold px-3 py-1.5 rounded-full"
                                    :class="statusBadgeClass(item.status)"
                                >
                                    {{ statusLabel(item.status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <router-link
                                    :to="`/dashboard/permohonan/${item.id}`"
                                    class="text-[#005AA7] font-medium hover:underline"
                                >
                                    Detail
                                </router-link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

        <!-- ===== HELPDESK ===== -->
        <div v-else-if="authStore.user?.role === 'helpdesk'">

            <div
                v-if="actionMessage"
                class="mb-4 rounded-xl bg-green-50 border border-green-300 text-green-700 px-4 py-3 text-sm"
            >
                {{ actionMessage }}
            </div>

            <div v-if="loading" class="text-center text-gray-400 py-16">
                Memuat permohonan...
            </div>

            <div v-else-if="pengajuans.length === 0" class="bg-white rounded-2xl border border-gray-100 p-10 text-center text-gray-500">
                Tidak ada permohonan yang menunggu diproses.
            </div>

            <div v-else class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-left">
                        <tr>
                            <th class="px-6 py-3 font-medium">Nomor Tiket</th>
                            <th class="px-6 py-3 font-medium">Pemohon</th>
                            <th class="px-6 py-3 font-medium">Layanan</th>
                            <th class="px-6 py-3 font-medium">Tanggal</th>
                            <th class="px-6 py-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="item in pengajuans"
                            :key="item.id"
                            class="border-t border-gray-100"
                        >
                            <td class="px-6 py-4 font-medium text-gray-800">{{ item.nomor_tiket }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ item.nama_pemohon }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ item.layanan }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ item.tanggal_pengajuan }}</td>
                            <td class="px-6 py-4 text-right space-x-3">
                                <button
                                    :disabled="processingId === item.id"
                                    @click="prosesPengajuan(item, 'diproses')"
                                    class="text-green-600 font-medium hover:underline disabled:opacity-50"
                                >
                                    Teruskan
                                </button>
                                <button
                                    :disabled="processingId === item.id"
                                    @click="prosesPengajuan(item, 'ditolak')"
                                    class="text-red-600 font-medium hover:underline disabled:opacity-50"
                                >
                                    Tolak
                                </button>
                                <router-link
                                    :to="`/dashboard/permohonan/${item.id}`"
                                    class="text-[#005AA7] font-medium hover:underline"
                                >
                                    Detail
                                </router-link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

        <!-- ===== PEGAWAI ===== -->
        <div v-else-if="authStore.user?.role === 'pegawai'">

            <div
                v-if="actionMessage"
                class="mb-4 rounded-xl bg-green-50 border border-green-300 text-green-700 px-4 py-3 text-sm"
            >
                {{ actionMessage }}
            </div>

            <div v-if="loading" class="text-center text-gray-400 py-16">
                Memuat permohonan...
            </div>

            <div v-else-if="pengajuans.length === 0" class="bg-white rounded-2xl border border-gray-100 p-10 text-center text-gray-500">
                Tidak ada permohonan yang perlu diproses tim Anda saat ini.
            </div>

            <div v-else class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-left">
                        <tr>
                            <th class="px-6 py-3 font-medium">Nomor Tiket</th>
                            <th class="px-6 py-3 font-medium">Pemohon</th>
                            <th class="px-6 py-3 font-medium">Layanan</th>
                            <th class="px-6 py-3 font-medium">Tanggal</th>
                            <th class="px-6 py-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="item in pengajuans"
                            :key="item.id"
                            class="border-t border-gray-100"
                        >
                            <td class="px-6 py-4 font-medium text-gray-800">{{ item.nomor_tiket }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ item.nama_pemohon }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ item.layanan }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ item.tanggal_pengajuan }}</td>
                            <td class="px-6 py-4 text-right space-x-3">
                                <button
                                    :disabled="processingId === item.id"
                                    @click="prosesPegawai(item, 'selesai')"
                                    class="text-green-600 font-medium hover:underline disabled:opacity-50"
                                >
                                    Selesaikan
                                </button>
                                <button
                                    :disabled="processingId === item.id"
                                    @click="prosesPegawai(item, 'perbaikan')"
                                    class="text-orange-600 font-medium hover:underline disabled:opacity-50"
                                >
                                    Minta Perbaikan
                                </button>
                                <router-link
                                    :to="`/dashboard/permohonan/${item.id}`"
                                    class="text-[#005AA7] font-medium hover:underline"
                                >
                                    Detail
                                </router-link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

        <!-- ===== ADMIN (menyusul) ===== -->
        <div v-else class="bg-white rounded-2xl border border-gray-100 p-10 text-center text-gray-400">
            Halaman ini lagi dikembangkan buat role Anda.
        </div>

    </DashboardLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from '../../lib/axios'
import { useAuthStore } from '../../stores/auth'
import DashboardLayout from '../../components/dashboard/DashboardLayout.vue'

const authStore = useAuthStore()
const pengajuans = ref([])
const loading = ref(true)
const processingId = ref(null)
const actionMessage = ref('')

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

async function fetchData() {
    loading.value = true

    try {
        if (authStore.user?.role === 'user') {
            const response = await axios.get('/api/pengajuan')
            pengajuans.value = response.data.data
        } else if (authStore.user?.role === 'helpdesk') {
            const response = await axios.get('/api/helpdesk/pengajuan')
            pengajuans.value = response.data.data
        } else if (authStore.user?.role === 'pegawai') {
            const response = await axios.get('/api/pegawai/pengajuan')
            pengajuans.value = response.data.data
        }
    } finally {
        loading.value = false
    }
}

async function prosesPengajuan(item, status) {
    const label = status === 'diproses' ? 'meneruskan' : 'menolak'

    if (!confirm(`Yakin mau ${label} permohonan ${item.nomor_tiket}?`)) return

    let keterangan = null
    if (status === 'ditolak') {
        keterangan = prompt('Alasan penolakan (opsional):') || null
    }

    processingId.value = item.id
    actionMessage.value = ''

    try {
        await axios.post(`/api/helpdesk/pengajuan/${item.id}/proses`, {
            status,
            keterangan,
        })
        actionMessage.value = `Permohonan ${item.nomor_tiket} berhasil di${status === 'diproses' ? 'teruskan' : 'tolak'}.`
        await fetchData()
    } catch (error) {
        alert(error.response?.data?.message || 'Terjadi kesalahan.')
    } finally {
        processingId.value = null
    }
}

async function prosesPegawai(item, status) {
    const label = status === 'selesai' ? 'menyelesaikan' : 'meminta perbaikan untuk'

    if (!confirm(`Yakin mau ${label} permohonan ${item.nomor_tiket}?`)) return

    const keterangan = prompt(
        status === 'selesai' ? 'Catatan penyelesaian (opsional):' : 'Apa yang perlu diperbaiki?'
    ) || null

    processingId.value = item.id
    actionMessage.value = ''

    try {
        await axios.post(`/api/pegawai/pengajuan/${item.id}/proses`, {
            status,
            keterangan,
        })
        actionMessage.value = `Permohonan ${item.nomor_tiket} berhasil diperbarui.`
        await fetchData()
    } catch (error) {
        alert(error.response?.data?.message || 'Terjadi kesalahan.')
    } finally {
        processingId.value = null
    }
}

onMounted(fetchData)
</script>