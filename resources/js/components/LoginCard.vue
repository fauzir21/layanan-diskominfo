<template>

    <div
        class="w-full max-w-[380px] lg:max-w-[460px] lg:h-full rounded-[clamp(20px,2.5vw,32px)] overflow-hidden bg-white/90 backdrop-blur-xl border border-white/40 shadow-2xl flex flex-col"
    >

        <!-- Body -->
        <div class="px-[clamp(24px,3.5vw,40px)] py-[clamp(24px,3.5vw,40px)] flex-1">

            <!-- Title -->
            <div class="text-center">

                <p class="text-gray-500 text-[clamp(12px,1.3vw,15px)]">
                    Selamat Datang di
                </p>

                <h2 class="mt-2 text-[clamp(22px,2.8vw,34px)] font-bold text-[#005AA7] leading-tight">
                    Layanan Diskominfo
                </h2>

                <h3 class="text-[clamp(20px,2.6vw,32px)] font-bold text-gray-800 leading-tight">
                    Kota Bogor
                </h3>

            </div>

            <!-- Pesan error -->
            <div
                v-if="errorMessage"
                class="mt-4 rounded-xl bg-red-50 border border-red-300 text-red-700 px-4 py-3 text-sm"
            >
                {{ errorMessage }}
            </div>

            <form @submit.prevent="handleLogin">

                <!-- Email -->
                <div class="mt-[clamp(14px,2vw,22px)]">

                    <label class="text-[clamp(12px,1.3vw,15px)] text-gray-700">
                        Email
                    </label>

                    <div class="relative mt-2">
                        <i class="bi bi-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input
                            v-model="form.email"
                            type="email"
                            placeholder="Email"
                            class="w-full h-[clamp(42px,5vw,50px)] rounded-xl border bg-white pl-11 pr-4 text-[clamp(13px,1.4vw,16px)] outline-none focus:border-blue-500"
                        />
                    </div>

                </div>

                <!-- Password -->
                <div class="mt-[clamp(10px,1.5vw,18px)]">

                    <label class="text-[clamp(12px,1.3vw,15px)] text-gray-700">
                        Password
                    </label>

                    <div class="relative mt-2">
                        <i class="bi bi-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input
                            v-model="form.password"
                            :type="showPassword ? 'text' : 'password'"
                            placeholder="Password"
                            class="w-full h-[clamp(42px,5vw,50px)] rounded-xl border bg-white pl-11 pr-11 text-[clamp(13px,1.4vw,16px)] outline-none focus:border-blue-500"
                        />
                        <button
                            type="button"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                            @click="showPassword = !showPassword"
                        >
                            <i :class="showPassword ? 'bi bi-eye' : 'bi bi-eye-slash'"></i>
                        </button>
                    </div>

                </div>

                <!-- Remember -->
                <div class="mt-[clamp(12px,1.8vw,20px)]">

                    <label class="flex items-center gap-2 text-[clamp(12px,1.3vw,15px)] text-gray-700">

                        <input type="checkbox" v-model="form.remember">

                        Ingat Saya

                    </label>

                </div>

                <!-- Captcha -->
                <div
                    class="mt-[clamp(10px,1.5vw,18px)] h-[clamp(64px,8vw,96px)] rounded-xl bg-white border border-gray-300 flex items-center justify-center overflow-hidden select-none relative"
                >

                    <img
                        v-if="captchaSrc"
                        :src="captchaSrc"
                        alt="Kode Captcha"
                        class="h-full w-full object-cover"
                    />

                    <p v-else class="text-xs text-gray-400">Memuat captcha...</p>

                    <button
                        type="button"
                        title="Muat ulang captcha"
                        class="absolute right-2 top-1/2 -translate-y-1/2 w-7 h-7 rounded-full bg-white/90 border border-gray-200 flex items-center justify-center text-gray-500 hover:text-[#0A66C2] hover:border-[#0A66C2] transition"
                        @click="refreshCaptcha"
                    >
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>

                </div>

                <!-- Captcha Input -->
                <input
                    v-model="form.captcha"
                    type="text"
                    placeholder="Masukan Captcha"
                    class="mt-[clamp(8px,1.2vw,12px)] w-full h-[clamp(42px,5vw,50px)] rounded-xl border bg-gray-100 px-4 text-[clamp(13px,1.4vw,16px)] outline-none focus:border-blue-500"
                />

                <!-- Lupa Kata Sandi + Submit -->
                <div class="mt-[clamp(14px,2vw,22px)] flex items-center justify-between gap-3">

                    <button
                        type="button"
                        @click="showForgot = true"
                        class="text-[clamp(12px,1.3vw,15px)] text-gray-800 hover:underline whitespace-nowrap"
                    >
                        Lupa Kata Sandi?
                    </button>

                    <button
                        type="submit"
                        :disabled="loading"
                        class="h-[clamp(42px,5vw,50px)] px-[clamp(20px,3vw,34px)] rounded-xl bg-[#0A66C2] text-white text-[clamp(13px,1.4vw,16px)] font-semibold hover:bg-[#0959aa] transition disabled:opacity-50 whitespace-nowrap"
                    >
                        {{ loading ? 'Memproses...' : 'Masuk' }}
                    </button>

                </div>

            </form>

        </div>

        <!-- Footer -->
        <div
            class="bg-white py-[clamp(16px,2.2vw,24px)] text-center text-[clamp(12px,1.3vw,15px)] border-t border-gray-100"
        >

            Belum Punya Akun ?

            <router-link
                to="/register"
                class="font-semibold text-[#005AA7] cursor-pointer hover:underline"
            >
                Daftar Sekarang !
            </router-link>

        </div>

    </div>

    <ForgotPasswordModal
        :show="showForgot"
        @close="showForgot = false"
    />

</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from '../lib/axios'
import { useAuthStore } from '../stores/auth'
import ForgotPasswordModal from './ForgotPasswordModal.vue'

const router = useRouter()
const authStore = useAuthStore()

const showForgot = ref(false)
const showPassword = ref(false)
const loading = ref(false)
const errorMessage = ref('')

const form = reactive({
    email: '',
    password: '',
    remember: false,
    captcha: '',
})

const captchaSrc = ref('')

function refreshCaptcha() {
    form.captcha = ''
    captchaSrc.value = `/api/captcha?_=${Date.now()}`
}

onMounted(refreshCaptcha)

async function handleLogin() {
    errorMessage.value = ''
    loading.value = true

    try {
        const response = await axios.post('/api/login', form)
        authStore.setUser(response.data.user)
        router.push('/dashboard')
    } catch (error) {
        errorMessage.value = error.response?.data?.message || 'Terjadi kesalahan, silakan coba lagi.'
    } finally {
        loading.value = false
        refreshCaptcha()
    }
}
</script>