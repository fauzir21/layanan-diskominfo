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

            </div>

        </div>

    </main>
</template>

<script setup>
import { ref } from 'vue'

const ticketNumber = ref('')
const captchaInput = ref('')
const captchaCode = ref('A7X92')
const loading = ref(false)
const message = ref('')
const messageType = ref('')

function refreshCaptcha() {
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'
    captchaCode.value = Array.from({ length: 5 }, () => chars[Math.floor(Math.random() * chars.length)]).join('')
}

async function handleSearch() {
    message.value = ''

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

    // TODO: sambungin ke API pelacakan permohonan begitu backend-nya udah ada
    loading.value = true
    setTimeout(() => {
        loading.value = false
        message.value = 'Fitur pelacakan permohonan belum tersedia — menunggu backend-nya siap.'
        messageType.value = 'info'
    }, 500)
}
</script>