<template>
    <main class="min-h-screen bg-white">

        <!-- Header biru + wave -->
        <div class="relative bg-gradient-to-r from-[#0A3FBF] to-[#3B5CFF] overflow-hidden pb-24">

            <!-- Dekorasi titik-titik -->
            <div
                class="absolute top-8 right-10 w-40 h-32 opacity-40 pointer-events-none"
                style="background-image: radial-gradient(circle, rgba(255,255,255,0.7) 1.5px, transparent 1.5px); background-size: 14px 14px;"
            ></div>

            <div class="max-w-[1200px] mx-auto px-6 sm:px-10 pt-8">

                <router-link
                    to="/"
                    class="inline-flex items-center gap-2 text-white hover:underline font-medium"
                >
                    ← Kembali Ke Beranda
                </router-link>

                <h1 class="mt-6 text-4xl sm:text-5xl font-extrabold text-white text-center">
                    Lacak Permohonan
                </h1>

                <p class="mt-4 text-white/90 text-center max-w-[560px] mx-auto">
                    Masukkan nomor tiket Anda untuk melihat status terbaru dari permohonan anda
                </p>

            </div>

            <!-- Wave -->
            <svg
                class="absolute bottom-0 left-0 w-full"
                viewBox="0 0 1440 100"
                preserveAspectRatio="none"
                style="height: 60px;"
            >
                <path d="M0,60 C480,120 960,0 1440,60 L1440,100 L0,100 Z" fill="white" />
            </svg>

        </div>

        <!-- Card -->
        <div class="relative -mt-16 px-4 pb-16">

            <div class="max-w-[700px] mx-auto bg-white rounded-[32px] shadow-2xl px-8 sm:px-12 pt-14 pb-10">

                <!-- Icon badge -->
                <div class="absolute left-1/2 -translate-x-1/2 -top-10">
                    <div class="w-20 h-20 rounded-full bg-white shadow-lg flex items-center justify-center">
                        <div class="w-14 h-14 rounded-full bg-[#EAF3FF] flex items-center justify-center">
                            <i class="bi bi-file-earmark-text text-2xl text-[#0A66C2]"></i>
                        </div>
                    </div>
                </div>

                <!-- Pesan hasil -->
                <div
                    v-if="message"
                    class="mb-6 rounded-2xl px-5 py-4 text-sm"
                    :class="messageType === 'error' ? 'bg-red-50 border border-red-300 text-red-700' : 'bg-blue-50 border border-blue-300 text-blue-700'"
                >
                    {{ message }}
                </div>

                <form @submit.prevent="handleSearch">

                    <!-- Nomor Tiket -->
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-[#0A66C2] flex items-center justify-center shrink-0">
                            <i class="bi bi-pencil-fill text-white text-xs"></i>
                        </div>
                        <h2 class="font-semibold text-gray-800">Nomor Tiket</h2>
                    </div>

                    <input
                        v-model="ticketNumber"
                        type="text"
                        placeholder="Masukan nomor tiket ( Contoh : TK-20260720-xxxx )"
                        class="mt-3 w-full h-12 rounded-xl border border-gray-300 px-5 text-gray-700 placeholder-gray-400 outline-none focus:border-blue-500"
                    />

                    <hr class="my-6 border-gray-200">

                    <!-- Captcha -->
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-[#0A66C2] flex items-center justify-center shrink-0">
                            <i class="bi bi-shield-fill-check text-white text-xs"></i>
                        </div>
                        <h2 class="font-semibold text-gray-800">Captcha</h2>
                    </div>

                    <div class="mt-3 flex items-center gap-3">
                        <div class="h-20 w-[220px] border border-gray-300 rounded-2xl flex items-center justify-center bg-white">
                            <span class="text-3xl font-black tracking-wide font-mono">{{ captchaCode }}</span>
                        </div>

                        <button
                            type="button"
                            @click="refreshCaptcha"
                            class="w-10 h-10 rounded-xl border border-gray-300 flex items-center justify-center text-[#0A66C2] hover:bg-gray-50 transition"
                        >
                            <i class="bi bi-arrow-repeat text-lg"></i>
                        </button>
                    </div>

                    <input
                        v-model="captchaInput"
                        type="text"
                        placeholder="Masukan Code Captcha"
                        class="mt-3 w-full h-12 rounded-xl border border-gray-300 px-5 text-gray-700 placeholder-gray-400 outline-none focus:border-blue-500"
                    />

                    <button
                        type="submit"
                        :disabled="loading"
                        class="mt-6 w-full h-12 rounded-xl bg-[#0A3FBF] hover:bg-[#082f94] text-white font-bold transition disabled:opacity-50"
                    >
                        {{ loading ? 'Mencari...' : 'Cari Tiket' }}
                    </button>

                </form>

                <!-- Hasil pelacakan -->
                <div v-if="result" class="mt-8 border-t border-gray-200 pt-6">

                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-gray-800">{{ result.layanan }}</h3>
                        <span
                            class="text-xs font-semibold px-3 py-1 rounded-full"
                            :class="statusStyle(result.status).badge"
                        >
                            {{ statusStyle(result.status).label }}
                        </span>
                    </div>

                    <p class="mt-1 text-sm text-gray-500">
                        Diajukan {{ formatTanggal(result.tanggal_pengajuan) }}
                        <template v-if="result.tanggal_selesai">
                            · Selesai {{ formatTanggal(result.tanggal_selesai) }}
                        </template>
                    </p>

                    <div v-if="result.riwayat?.length" class="mt-5 space-y-4">
                        <div
                            v-for="(item, index) in result.riwayat"
                            :key="index"
                            class="flex gap-3"
                        >
                            <div class="flex flex-col items-center">
                                <div class="w-3 h-3 rounded-full bg-[#0A66C2] mt-1.5 shrink-0"></div>
                                <div v-if="index < result.riwayat.length - 1" class="w-px flex-1 bg-gray-200"></div>
                            </div>

                            <div class="pb-4">
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ statusStyle(item.status).label }}
                                </p>
                                <p v-if="item.keterangan" class="text-sm text-gray-500 mt-0.5">
                                    {{ item.keterangan }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ formatTanggal(item.tanggal_disposisi, true) }}
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </main>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from '../lib/axios'

const route = useRoute()

const ticketNumber = ref('')
const captchaInput = ref('')
const captchaCode = ref('A7X92')
const loading = ref(false)
const message = ref('')
const messageType = ref('')
const result = ref(null)

const STATUS_MAP = {
    menunggu_diproses: { label: 'Menunggu Diproses', badge: 'bg-amber-50 text-amber-700' },
    diproses: { label: 'Diproses', badge: 'bg-blue-50 text-blue-700' },
    perbaikan: { label: 'Perlu Perbaikan', badge: 'bg-orange-50 text-orange-700' },
    ditolak: { label: 'Ditolak', badge: 'bg-red-50 text-red-700' },
    selesai: { label: 'Selesai', badge: 'bg-green-50 text-green-700' },
}

function statusStyle(status) {
    return STATUS_MAP[status] || { label: status, badge: 'bg-gray-100 text-gray-700' }
}

function formatTanggal(value, withTime = false) {
    if (!value) return ''

    return new Date(value).toLocaleString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        ...(withTime ? { hour: '2-digit', minute: '2-digit' } : {}),
    })
}

function refreshCaptcha() {
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'
    captchaCode.value = Array.from({ length: 5 }, () => chars[Math.floor(Math.random() * chars.length)]).join('')
}

async function handleSearch() {
    message.value = ''
    result.value = null

    if (!ticketNumber.value) {
        message.value = 'Nomor tiket wajib diisi.'
        messageType.value = 'error'
        return
    }

    if (captchaInput.value.toUpperCase() !== captchaCode.value) {
        message.value = 'Kode captcha tidak sesuai.'
        messageType.value = 'error'
        refreshCaptcha()
        captchaInput.value = ''
        return
    }

    loading.value = true

    try {
        const response = await axios.get(`/api/pengajuan/lacak/${encodeURIComponent(ticketNumber.value)}`)
        result.value = response.data.data
    } catch (error) {
        if (error.response?.status === 404) {
            message.value = 'Nomor tiket tidak ditemukan.'
        } else {
            message.value = 'Terjadi kesalahan, silakan coba lagi.'
        }
        messageType.value = 'error'
        refreshCaptcha()
        captchaInput.value = ''
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    if (route.query.tiket) {
        ticketNumber.value = route.query.tiket
    }
})
</script>