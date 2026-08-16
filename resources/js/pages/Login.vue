<template>
    <main
        class="relative min-h-screen w-full overflow-x-hidden bg-cover bg-center bg-no-repeat"
        style="background-image: url('/images/bg.png');"
    >
        <!-- Background overlay -->
        <div class="absolute inset-0 bg-white/10"></div>

        <!-- Content -->
        <div
            class="relative z-10 flex min-h-screen w-full items-center justify-center px-4 py-6 sm:px-6 md:px-8"
        >
            <div
                class="w-full max-w-[860px] overflow-hidden rounded-[28px] border border-white/70 bg-white/75 shadow-[0_20px_60px_rgba(0,0,0,0.18)] backdrop-blur-[4px]"
            >
                <!-- ============================= -->
                <!-- HEADER -->
                <!-- ============================= -->

                <div
                    class="px-6 pt-8 text-center sm:px-10 sm:pt-10 md:px-14 md:pt-12"
                >
                    <!-- LOGO -->

                    <div class="flex justify-center">
                        <img
                            src="/public/images/logo-kota-bogor.png"
                            alt="Logo Kota Bogor"
                            class="h-[clamp(58px,6vw,82px)] w-auto object-contain"
                        />
                    </div>

                    <!-- TITLE -->

                    <div class="mt-3">
                        <h1
                            class="text-[clamp(22px,2.8vw,34px)] font-bold leading-tight tracking-[-0.04em]"
                        >
                            <span class="text-[#1554F0]">
                                Layanan Diskominfo
                            </span>

                            <br />

                            <span class="text-black">
                                Kota Bogor
                            </span>
                        </h1>
                    </div>
                </div>

                <!-- ============================= -->
                <!-- FORM -->
                <!-- ============================= -->

                <form
                    @submit.prevent="handleLogin"
                    class="px-6 pb-8 pt-7 sm:px-10 sm:pb-10 md:px-14 md:pb-12"
                >
                    <!-- ERROR -->

                    <div
                        v-if="generalError"
                        class="mb-5 rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-600"
                    >
                        {{ generalError }}
                    </div>

                    <!-- ============================= -->
                    <!-- EMAIL -->
                    <!-- ============================= -->

                    <div>
                        <div class="relative">
                            <i
                                class="bi bi-envelope absolute left-4 top-1/2 -translate-y-1/2 text-[23px] text-black"
                            ></i>

                            <input
                                v-model="form.email"
                                type="email"
                                autocomplete="email"
                                placeholder="Email"
                                class="h-[clamp(48px,4vw,58px)] w-full rounded-[16px] border border-[#777777] bg-white/70 pl-14 pr-5 text-[clamp(13px,1.1vw,16px)] text-[#303030] outline-none transition placeholder:text-[#727689] focus:border-[#1554F0] focus:ring-2 focus:ring-[#1554F0]/10"
                            />
                        </div>

                        <p
                            v-if="errors.email"
                            class="ml-2 mt-1 text-xs text-red-500"
                        >
                            {{ errors.email[0] }}
                        </p>
                    </div>

                    <!-- ============================= -->
                    <!-- PASSWORD -->
                    <!-- ============================= -->

                    <div class="mt-4">
                        <div class="relative">
                            <i
                                class="bi bi-lock absolute left-4 top-1/2 -translate-y-1/2 text-[23px] text-black"
                            ></i>

                            <input
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                autocomplete="current-password"
                                placeholder="Password"
                                class="h-[clamp(48px,4vw,58px)] w-full rounded-[16px] border border-[#777777] bg-white/70 pl-14 pr-14 text-[clamp(13px,1.1vw,16px)] text-[#303030] outline-none transition placeholder:text-[#727689] focus:border-[#1554F0] focus:ring-2 focus:ring-[#1554F0]/10"
                            />

                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-black transition hover:text-[#1554F0]"
                            >
                                <i
                                    class="bi text-[20px]"
                                    :class="
                                        showPassword
                                            ? 'bi-eye'
                                            : 'bi-eye-slash'
                                    "
                                ></i>
                            </button>
                        </div>

                        <p
                            v-if="errors.password"
                            class="ml-2 mt-1 text-xs text-red-500"
                        >
                            {{ errors.password[0] }}
                        </p>
                    </div>

                    <!-- ============================= -->
                    <!-- REMEMBER ME -->
                    <!-- ============================= -->

                    <div class="mt-4 flex items-center">
                        <label
                            class="flex cursor-pointer items-center gap-2 text-[clamp(11px,0.9vw,13px)] text-black"
                        >
                            <input
                                v-model="rememberEmail"
                                type="checkbox"
                                class="h-5 w-5 cursor-pointer appearance-none rounded-[4px] border border-black bg-white checked:border-[#1554F0] checked:bg-[#1554F0]"
                            />

                            <span>Ingat Saya</span>
                        </label>
                    </div>

                    <!-- ============================= -->
                    <!-- CAPTCHA -->
                    <!-- ============================= -->

                    <div class="mt-7">
                        <div
                            class="flex flex-col gap-3 sm:flex-row sm:items-center"
                        >
                            <!-- CAPTCHA IMAGE -->

                            <div
                                class="flex h-[60px] w-full shrink-0 items-center justify-center overflow-hidden rounded-[14px] border border-[#777777] bg-white sm:h-[72px] sm:w-[190px]"
                            >
                                <img
                                    v-if="captchaUrl"
                                    :src="captchaUrl"
                                    alt="Captcha"
                                    class="h-full w-full object-contain"
                                />

                                <span
                                    v-else
                                    class="text-xs text-gray-500"
                                >
                                    Memuat captcha...
                                </span>
                            </div>

                            <!-- REFRESH -->

                            <button
                                type="button"
                                @click="loadCaptcha"
                                :disabled="captchaLoading"
                                class="flex h-[42px] w-[42px] shrink-0 items-center justify-center self-center rounded-xl border border-[#777777] bg-white transition hover:bg-gray-50 disabled:opacity-50"
                                title="Refresh Captcha"
                            >
                                <i
                                    class="bi bi-arrow-clockwise text-[22px] text-[#00A9D6]"
                                    :class="{
                                        'animate-spin': captchaLoading
                                    }"
                                ></i>
                            </button>

                            <!-- CAPTCHA INPUT -->

                            <input
                                v-model="form.captcha"
                                type="text"
                                autocomplete="off"
                                maxlength="5"
                                placeholder="Masukan Captcha"
                                class="h-[clamp(48px,4vw,58px)] min-w-0 flex-1 rounded-[16px] border border-[#777777] bg-white/70 px-5 text-[clamp(13px,1.1vw,16px)] uppercase outline-none transition placeholder:text-[#727689] focus:border-[#1554F0] focus:ring-2 focus:ring-[#1554F0]/10"
                            />
                        </div>

                        <p
                            v-if="errors.captcha"
                            class="ml-2 mt-1 text-xs text-red-500"
                        >
                            {{ errors.captcha[0] }}
                        </p>
                    </div>

                    <!-- ============================= -->
                    <!-- FORGOT PASSWORD -->
                    <!-- ============================= -->

                    <div class="mt-4">
                        <button
                            type="button"
                            class="text-[clamp(11px,0.9vw,13px)] text-black transition hover:text-[#1554F0]"
                        >
                            Lupa Password?
                        </button>
                    </div>

                    <!-- ============================= -->
                    <!-- LOGIN BUTTON -->
                    <!-- ============================= -->

                    <div class="mt-4 flex justify-end">
                        <button
                            type="submit"
                            :disabled="loading || captchaLoading"
                            class="h-[clamp(42px,3.5vw,50px)] min-w-[120px] rounded-[8px] bg-[#1554F0] px-7 text-[clamp(13px,1vw,15px)] font-medium text-white shadow-sm transition hover:bg-[#1048D6] disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            {{ loading ? 'Memproses...' : 'Masuk' }}
                        </button>
                    </div>
                </form>

                <!-- ============================= -->
                <!-- FOOTER -->
                <!-- ============================= -->

                <div
                    class="flex flex-col gap-2 border-t border-gray-300 bg-white/80 px-6 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-10 md:px-14"
                >
                    <!-- BACK -->

                    <router-link
                        :to="backUrl"
                        class="inline-flex items-center gap-2 text-[clamp(12px,1vw,15px)] font-medium text-[#1554F0] transition hover:text-[#1048D6]"
                    >
                        <i class="bi bi-arrow-left text-[18px]"></i>

                        Kembali ke detail layanan
                    </router-link>

                    <!-- REGISTER -->

                    <router-link
                        to="/register"
                        class="text-[clamp(12px,1vw,15px)] text-[#606575] transition hover:text-[#1554F0]"
                    >
                        Belum Punya Akun ?
                        <span class="font-medium text-[#1554F0]">
                            Daftar Sekarang !
                        </span>
                    </router-link>
                </div>
            </div>
        </div>
    </main>
</template>

<script setup>
import {
    ref,
    reactive,
    computed,
    onMounted,
    onBeforeUnmount,
} from 'vue'

import {
    useRoute,
    useRouter,
} from 'vue-router'

import axios from '../lib/axios'
import { useAuthStore } from '../stores/auth'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

const form = reactive({
    email: '',
    password: '',
    captcha: '',
})

const errors = ref({})
const generalError = ref('')

const loading = ref(false)
const captchaLoading = ref(false)

const showPassword = ref(false)
const rememberEmail = ref(false)

const captchaUrl = ref('')

let captchaObjectUrl = null

const backUrl = computed(() => {
    return route.query.redirect || '/layanan'
})

async function loadCaptcha() {
    captchaLoading.value = true

    if (errors.value.captcha) {
        delete errors.value.captcha
    }

    try {
        const response = await axios.get(
            '/api/captcha',
            {
                responseType: 'blob',
                withCredentials: true,
            }
        )

        if (captchaObjectUrl) {
            URL.revokeObjectURL(captchaObjectUrl)
        }

        captchaObjectUrl = URL.createObjectURL(
            response.data
        )

        captchaUrl.value = captchaObjectUrl

        form.captcha = ''
    } catch (error) {
        console.error(
            'Gagal mengambil captcha:',
            error
        )

        generalError.value =
            'Captcha gagal dimuat. Silakan refresh halaman.'
    } finally {
        captchaLoading.value = false
    }
}

async function handleLogin() {
    errors.value = {}
    generalError.value = ''

    if (!form.email || !form.password || !form.captcha) {
        generalError.value =
            'Email, password, dan captcha wajib diisi.'

        return
    }

    loading.value = true

    try {
        await axios.get('/sanctum/csrf-cookie')

        const response = await axios.post(
            '/api/login',
            {
                email: form.email,
                password: form.password,
                captcha: form.captcha,
            },
            {
                withCredentials: true,
            }
        )

        authStore.setUser(response.data.user)

        if (rememberEmail.value) {
            localStorage.setItem(
                'remembered_email',
                form.email
            )
        } else {
            localStorage.removeItem(
                'remembered_email'
            )
        }

        const redirectTarget =
            route.query.redirect || '/layanan'

        router.push(redirectTarget)
    } catch (error) {
        console.error(
            'Login error:',
            error
        )

        if (error.response?.status === 422) {
            errors.value =
                error.response.data?.errors || {}

            generalError.value =
                error.response.data?.message ||
                'Email, password, atau captcha tidak sesuai.'
        } else if (error.response?.status === 403) {
            generalError.value =
                error.response.data?.message ||
                'Email belum diverifikasi.'
        } else if (error.response?.status === 419) {
            generalError.value =
                'Sesi sudah kedaluwarsa. Silakan refresh halaman dan coba lagi.'
        } else {
            generalError.value =
                error.response?.data?.message ||
                'Terjadi kesalahan saat login.'
        }

        await loadCaptcha()
    } finally {
        loading.value = false
    }
}

onMounted(async () => {
    const rememberedEmail =
        localStorage.getItem(
            'remembered_email'
        )

    if (rememberedEmail) {
        form.email = rememberedEmail
        rememberEmail.value = true
    }

    if (authStore.isLoading) {
        await authStore.fetchUser()
    }

    if (authStore.user) {
        router.replace(
            route.query.redirect || '/layanan'
        )

        return
    }

    await loadCaptcha()
})

onBeforeUnmount(() => {
    if (captchaObjectUrl) {
        URL.revokeObjectURL(captchaObjectUrl)
    }
})
</script>