<template>

    <div
        class="w-full max-w-[380px] rounded-[28px] overflow-hidden bg-white/25 backdrop-blur-xl border border-white/40 shadow-2xl"
    >

        <!-- Body -->
        <div class="px-8 py-8">

            <!-- Title -->
            <div class="text-center">

                <p class="text-gray-700 text-sm">
                    Selamat Datang di
                </p>

                <h2 class="mt-2 text-[30px] font-bold text-[#005AA7]">
                    Layanan Diskominfo
                </h2>

                <h3 class="text-[28px] font-bold text-gray-800">
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
                <div class="mt-3">

                    <label class="text-sm text-gray-700">
                        Email
                    </label>

                    <input
                        v-model="form.email"
                        type="email"
                        placeholder="Masukkan email"
                        class="mt-2 w-full h-11 rounded-xl border bg-white px-4 outline-none focus:border-blue-500"
                    />

                </div>

                <!-- Password -->
                <div class="mt-2">

                    <label class="text-sm text-gray-700">
                        Password
                    </label>

                    <input
                        v-model="form.password"
                        type="password"
                        placeholder="Masukkan password"
                        class="mt-2 w-full h-11 rounded-xl border bg-white px-4 outline-none focus:border-blue-500"
                    />

                </div>

                <!-- Remember -->
                <div class="flex items-center justify-between mt-5">

                    <label class="flex items-center gap-2 text-sm">

                        <input type="checkbox">

                        Ingat Saya

                    </label>

                    <button
                        type="button"
                        @click="showForgot = true"
                        class="text-sm text-blue-600 hover:underline"
                    >
                        Lupa Password?
                    </button>

                </div>

                <!-- Captcha -->
                <div
                    class="mt-3 h-20 rounded-xl bg-white border border-gray-300 flex items-center justify-center"
                >

                    <span class="text-3xl font-bold tracking-widest">
                        A7X92
                    </span>

                </div>

                <!-- Captcha Input -->
                <input
                    type="text"
                    placeholder="Masukkan Captcha"
                    class="mt-2 w-full h-11 rounded-xl border bg-gray-300 px-4 outline-none focus:border-blue-500"
                />

                <!-- Login -->
                <button
                    type="submit"
                    :disabled="loading"
                    class="mt-2 w-full h-11 rounded-xl bg-[#005AA7] text-white font-semibold hover:bg-[#004b8c] transition disabled:opacity-50"
                >
                    {{ loading ? 'Memproses...' : 'Masuk' }}
                </button>

            </form>

        </div>

        <!-- Footer -->
        <div
            class="bg-white py-5 text-center text-sm"
        >

            Belum punya akun?

            <router-link
                to="/register"
                class="font-semibold text-[#005AA7] cursor-pointer hover:underline"
            >
                Daftar Sekarang
            </router-link>

        </div>

    </div>

    <ForgotPasswordModal
        :show="showForgot"
        @close="showForgot = false"
    />

</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import axios from '../lib/axios'
import { useAuthStore } from '../stores/auth'
import ForgotPasswordModal from './ForgotPasswordModal.vue'

const router = useRouter()
const authStore = useAuthStore()

const showForgot = ref(false)
const loading = ref(false)
const errorMessage = ref('')

const form = reactive({
    email: '',
    password: '',
})

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
    }
}
</script>