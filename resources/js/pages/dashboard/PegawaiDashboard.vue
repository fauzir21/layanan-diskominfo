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
                Kelola permohonan yang ditugaskan kepada tim Anda.
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
                    title="Menunggu"
                    :value="data?.statistics?.menunggu_diproses ?? 0"
                    icon="📥"
                />

                <StatCard
                    title="Diproses"
                    :value="data?.statistics?.diproses ?? 0"
                    icon="🔄"
                />

                <StatCard
                    title="Perbaikan"
                    :value="data?.statistics?.perbaikan ?? 0"
                    icon="⚠️"
                />

                <StatCard
                    title="Selesai"
                    :value="data?.statistics?.selesai ?? 0"
                    icon="✅"
                />

            </div>


            <!-- RINGKASAN -->
            <div
                class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6"
            >

                <!-- TOTAL -->
                <div
                    class="bg-white rounded-2xl border border-gray-100 p-6"
                >
                    <p class="text-sm text-gray-500">
                        Total Tugas
                    </p>

                    <p class="text-3xl font-bold text-gray-800 mt-2">
                        {{ data?.statistics?.total_pengajuan ?? 0 }}
                    </p>

                    <p class="text-xs text-gray-400 mt-1">
                        Total permohonan yang ditangani tim Anda.
                    </p>
                </div>


                <!-- DITOLAK -->
                <div
                    class="bg-white rounded-2xl border border-gray-100 p-6"
                >
                    <p class="text-sm text-gray-500">
                        Ditolak
                    </p>

                    <p class="text-3xl font-bold text-red-600 mt-2">
                        {{ data?.statistics?.ditolak ?? 0 }}
                    </p>

                    <p class="text-xs text-gray-400 mt-1">
                        Permohonan yang ditolak.
                    </p>
                </div>

            </div>


            <!-- DAFTAR TUGAS -->
            <div
                class="bg-white rounded-2xl border border-gray-100 p-6 mt-6"
            >

                <div class="flex items-center justify-between">

                    <div>
                        <h3 class="font-bold text-gray-800">
                            Tugas Terbaru
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Permohonan terbaru yang berkaitan dengan tim Anda.
                        </p>
                    </div>

                    <router-link
                        to="/dashboard/permohonan"
                        class="text-sm text-[#005AA7] hover:underline"
                    >
                        Lihat semua
                    </router-link>

                </div>


                <!-- EMPTY -->
                <div
                    v-if="!latestApplications.length"
                    class="py-12 text-center text-gray-400 text-sm"
                >
                    Belum ada tugas yang tersedia.
                </div>


                <!-- DATA -->
                <div
                    v-else
                    class="mt-6 space-y-4"
                >

                    <div
                        v-for="pengajuan in latestApplications"
                        :key="pengajuan.id"
                        class="flex items-center justify-between gap-4 border-b border-gray-100 pb-4 last:border-0 last:pb-0"
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


                        <div class="text-right shrink-0">

                            <span
                                class="inline-block px-2.5 py-1 rounded-full text-xs font-medium"
                                :class="statusClass(pengajuan.status)"
                            >
                                {{ statusLabel(pengajuan.status) }}
                            </span>

                            <p class="text-xs text-gray-400 mt-2">
                                {{ formatDate(pengajuan.tanggal_pengajuan) }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- INFO TIM -->
            <div
                class="bg-blue-50 border border-blue-100 rounded-2xl p-6 mt-6"
            >

                <div class="flex items-start gap-4">

                    <div
                        class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center shrink-0"
                    >
                        👥
                    </div>

                    <div>

                        <h3 class="font-bold text-gray-800">
                            Tugas Berdasarkan Tim Kerja
                        </h3>

                        <p class="text-sm text-gray-600 mt-1">
                            Data pada dashboard ini hanya menampilkan
                            permohonan yang berkaitan dengan tim kerja
                            akun pegawai yang sedang login.
                        </p>

                    </div>

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
| Backend sudah melakukan filtering berdasarkan Tim Kerja pegawai.
| Vue hanya menampilkan hasil yang dikirim API.
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