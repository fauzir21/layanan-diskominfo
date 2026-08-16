<template>

    <div
        class="w-full max-w-[770px] rounded-[clamp(24px,3vw,38px)] bg-white px-[clamp(20px,4.5vw,64px)] py-[clamp(24px,3.5vw,34px)] shadow-[0_20px_60px_rgba(0,0,0,0.18)]"
    >

        <!-- ===================================================== -->
        <!-- LOGO -->
        <!-- ===================================================== -->

        <div class="flex justify-center">

            <img
                src="/public/images/logo-kota-bogor.png"
                alt="Logo Kota Bogor"
                class="h-[clamp(54px,5vw,70px)] w-auto object-contain"
            />

        </div>


        <!-- ===================================================== -->
        <!-- HEADER -->
        <!-- ===================================================== -->

        <div class="mt-3 text-center">

            <h1
                class="text-[clamp(22px,2.3vw,29px)] font-medium leading-tight tracking-[-0.03em] text-[#303030]"
            >
                Buat Akun Baru
            </h1>


            <p
                class="mt-1 text-[clamp(11px,0.9vw,13px)] text-[#666B7A]"
            >
                Silahkan lengkapi data diri untuk membuat akun anda
            </p>

        </div>


        <!-- ===================================================== -->
        <!-- SUCCESS -->
        <!-- ===================================================== -->

        <div
            v-if="successMessage"
            class="mt-6 rounded-2xl border border-green-300 bg-green-50 px-5 py-4 text-sm text-green-700"
        >

            {{ successMessage }}

            <div class="mt-3">

                <router-link
                    to="/"
                    class="font-semibold text-blue-600 hover:underline"
                >
                    Kembali ke halaman login
                </router-link>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- GENERAL ERROR -->
        <!-- ===================================================== -->

        <div
            v-if="generalError"
            class="mt-6 rounded-2xl border border-red-300 bg-red-50 px-5 py-4 text-sm text-red-700"
        >
            {{ generalError }}
        </div>


        <!-- ===================================================== -->
        <!-- FORM -->
        <!-- ===================================================== -->

        <form
            v-if="!successMessage"
            @submit.prevent="handleSubmit"
            class="mt-6"
        >

            <!-- ================================================= -->
            <!-- NAMA -->
            <!-- ================================================= -->

            <div>

                <label
                    for="name"
                    class="mb-2 block text-[clamp(13px,1vw,15px)] font-medium text-[#303030]"
                >
                    Nama Lengkap
                </label>


                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    autocomplete="name"
                    placeholder="Masukan Nama Lengkap Anda"
                    class="h-[clamp(40px,3.2vw,42px)] w-full rounded-[clamp(12px,1.2vw,15px)] border border-[#9D9D9D] bg-[#F7F7F7] px-[clamp(15px,1.7vw,22px)] text-[clamp(12px,1vw,14px)] outline-none transition placeholder:text-[#727689] focus:border-[#1554F0] focus:ring-2 focus:ring-[#1554F0]/10"
                />


                <p
                    v-if="errors.name"
                    class="ml-1 mt-1 text-xs text-red-500"
                >
                    {{ errors.name[0] }}
                </p>

            </div>


            <!-- ================================================= -->
            <!-- KATEGORI -->
            <!-- ================================================= -->

            <div class="mt-4">

                <label
                    for="kategori"
                    class="mb-2 block text-[clamp(13px,1vw,15px)] font-medium text-[#303030]"
                >
                    Kategori Pengguna
                </label>


                <div class="relative">

                    <select
                        id="kategori"
                        v-model="form.kategori_pengguna"
                        class="h-[clamp(40px,3.2vw,42px)] w-full appearance-none rounded-[clamp(12px,1.2vw,15px)] border border-[#9D9D9D] bg-[#F7F7F7] px-[clamp(15px,1.7vw,22px)] pr-12 text-[clamp(12px,1vw,14px)] text-[#727689] outline-none transition focus:border-[#1554F0] focus:ring-2 focus:ring-[#1554F0]/10"
                    >

                        <option
                            disabled
                            value=""
                        >
                            Kategori Pengguna
                        </option>

                        <option value="ASN Pemerintah Kota Bogor">
                            ASN Pemerintah Kota Bogor
                        </option>

                        <option value="Non ASN">
                            Non ASN
                        </option>

                        <option value="Masyarakat Umum">
                            Masyarakat Umum
                        </option>

                        <option value="Mahasiswa">
                            Mahasiswa
                        </option>

                        <option value="Instansi">
                            Instansi
                        </option>

                    </select>


                    <i
                        class="bi bi-chevron-down pointer-events-none absolute right-5 top-1/2 -translate-y-1/2 text-black"
                    ></i>

                </div>


                <p
                    v-if="errors.kategori_pengguna"
                    class="ml-1 mt-1 text-xs text-red-500"
                >
                    {{ errors.kategori_pengguna[0] }}
                </p>

            </div>


            <!-- ================================================= -->
            <!-- EMAIL -->
            <!-- ================================================= -->

            <div class="mt-4">

                <label
                    for="email"
                    class="mb-2 block text-[clamp(13px,1vw,15px)] font-medium text-[#303030]"
                >
                    Email
                </label>


                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    autocomplete="email"
                    placeholder="Masukan Email Anda"
                    class="h-[clamp(40px,3.2vw,42px)] w-full rounded-[clamp(12px,1.2vw,15px)] border border-[#9D9D9D] bg-[#F7F7F7] px-[clamp(15px,1.7vw,22px)] text-[clamp(12px,1vw,14px)] outline-none transition placeholder:text-[#727689] focus:border-[#1554F0] focus:ring-2 focus:ring-[#1554F0]/10"
                />


                <p
                    class="ml-1 mt-2 text-[clamp(10px,0.8vw,12px)] text-[#606575]"
                >
                    *Masukan Nama Lengkap Anda
                </p>


                <p
                    v-if="errors.email"
                    class="ml-1 mt-1 text-xs text-red-500"
                >
                    {{ errors.email[0] }}
                </p>

            </div>


            <!-- ================================================= -->
            <!-- PASSWORD -->
            <!-- ================================================= -->

            <div
                class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-10"
            >

                <div>

                    <label
                        for="password"
                        class="mb-2 block text-[clamp(13px,1vw,15px)] font-medium text-[#303030]"
                    >
                        Kata Sandi
                    </label>


                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        autocomplete="new-password"
                        placeholder="Buat Kata Sandi"
                        class="h-[clamp(40px,3.2vw,42px)] w-full rounded-[clamp(12px,1.2vw,15px)] border border-[#9D9D9D] bg-[#F7F7F7] px-5 text-[clamp(12px,1vw,14px)] outline-none transition placeholder:text-[#727689] focus:border-[#1554F0] focus:ring-2 focus:ring-[#1554F0]/10"
                    />


                    <p
                        v-if="errors.password"
                        class="ml-1 mt-1 text-xs text-red-500"
                    >
                        {{ errors.password[0] }}
                    </p>

                </div>


                <div>

                    <label
                        for="password_confirmation"
                        class="mb-2 block text-[clamp(13px,1vw,15px)] font-medium text-[#303030]"
                    >
                        Konfirmasi Kata Sandi
                    </label>


                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        autocomplete="new-password"
                        placeholder="Konfirmasi Kata Sandi"
                        class="h-[clamp(40px,3.2vw,42px)] w-full rounded-[clamp(12px,1.2vw,15px)] border border-[#9D9D9D] bg-[#F7F7F7] px-5 text-[clamp(12px,1vw,14px)] outline-none transition placeholder:text-[#727689] focus:border-[#1554F0] focus:ring-2 focus:ring-[#1554F0]/10"
                    />

                </div>

            </div>


            <!-- ================================================= -->
            <!-- CAPTCHA -->
            <!-- ================================================= -->

            <div class="mt-5">

                <label
                    class="mb-2 block text-[clamp(13px,1vw,15px)] font-medium text-[#303030]"
                >
                    Captcha
                </label>


                <div
                    class="flex flex-col gap-3 sm:flex-row sm:items-center"
                >

                    <!-- CAPTCHA IMAGE -->

                    <div
                        class="flex h-[60px] w-full shrink-0 items-center justify-center overflow-hidden rounded-[15px] border border-[#9D9D9D] bg-[#F7F7F7] sm:h-[72px] sm:w-[220px]"
                    >

                        <img
                            v-if="captchaUrl"
                            :src="captchaUrl"
                            alt="Captcha"
                            class="h-full w-full object-contain"
                        />


                        <span
                            v-else
                            class="text-sm text-gray-500"
                        >
                            Memuat captcha...
                        </span>

                    </div>


                    <!-- REFRESH BUTTON -->

                    <button
                        type="button"
                        @click="loadCaptcha"
                        :disabled="captchaLoading"
                        class="flex h-[42px] w-[42px] shrink-0 items-center justify-center self-center rounded-xl border border-[#9D9D9D] bg-[#F7F7F7] text-[#00A9D6] transition hover:bg-white disabled:opacity-50"
                        title="Refresh Captcha"
                    >

                        <i
                            class="bi bi-arrow-clockwise text-[22px]"
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
                        placeholder="Masukan Kode Captcha"
                        class="h-[clamp(40px,3.2vw,42px)] min-w-0 flex-1 rounded-[clamp(12px,1.2vw,15px)] border border-[#9D9D9D] bg-[#F7F7F7] px-[clamp(15px,1.7vw,22px)] text-[clamp(12px,1vw,14px)] uppercase outline-none transition placeholder:text-[#727689] focus:border-[#1554F0] focus:ring-2 focus:ring-[#1554F0]/10"
                    />

                </div>


                <p
                    v-if="errors.captcha"
                    class="ml-1 mt-1 text-xs text-red-500"
                >
                    {{ errors.captcha[0] }}
                </p>

            </div>


            <!-- ================================================= -->
            <!-- REGISTER -->
            <!-- ================================================= -->

            <button
                type="submit"
                :disabled="loading || captchaLoading"
                class="mt-5 h-[clamp(44px,3.6vw,50px)] w-full rounded-[clamp(12px,1.2vw,15px)] bg-[#1554F0] text-[clamp(14px,1.1vw,16px)] font-bold tracking-wide text-white shadow-sm transition hover:bg-[#1048D6] disabled:cursor-not-allowed disabled:opacity-50"
            >

                {{ loading ? 'Memproses...' : 'Daftar Akun' }}

            </button>


            <!-- ================================================= -->
            <!-- BACK -->
            <!-- ================================================= -->

            <router-link
                to="/"
                class="mt-5 inline-flex items-center gap-2 text-[clamp(13px,1vw,15px)] font-medium text-[#1554F0] transition hover:text-[#1048D6]"
            >

                <i class="bi bi-arrow-left text-[18px]"></i>

                Kembali ke Beranda

            </router-link>

        </form>

    </div>

</template>


<script setup>

import {
    ref,
    reactive,
    onMounted,
    onBeforeUnmount,
} from 'vue'

import axios from '../lib/axios'


/*
|--------------------------------------------------------------------------
| FORM
|--------------------------------------------------------------------------
*/

const form = reactive({

    name: '',

    email: '',

    password: '',

    password_confirmation: '',

    kategori_pengguna: '',

    captcha: '',

})


/*
|--------------------------------------------------------------------------
| STATE
|--------------------------------------------------------------------------
*/

const errors = ref({})

const generalError = ref('')

const successMessage = ref('')

const loading = ref(false)

const captchaLoading = ref(false)

const captchaUrl = ref('')

let captchaObjectUrl = null


/*
|--------------------------------------------------------------------------
| LOAD CAPTCHA
|--------------------------------------------------------------------------
*/

async function loadCaptcha() {

    captchaLoading.value = true

    errors.value.captcha = undefined

    try {

        const response = await axios.get(
            '/api/captcha',
            {
                responseType: 'blob',

                /*
                |--------------------------------------------------------------------------
                | Pastikan session cookie ikut.
                |--------------------------------------------------------------------------
                */

                withCredentials: true,
            }
        )


        /*
        |--------------------------------------------------------------------------
        | Hapus URL captcha sebelumnya
        |--------------------------------------------------------------------------
        */

        if (captchaObjectUrl) {

            URL.revokeObjectURL(
                captchaObjectUrl
            )

        }


        /*
        |--------------------------------------------------------------------------
        | Buat URL dari SVG blob
        |--------------------------------------------------------------------------
        */

        captchaObjectUrl =
            URL.createObjectURL(
                response.data
            )


        captchaUrl.value =
            captchaObjectUrl


        /*
        |--------------------------------------------------------------------------
        | CAPTCHA baru berarti input lama harus dikosongkan.
        |--------------------------------------------------------------------------
        */

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


/*
|--------------------------------------------------------------------------
| REGISTER
|--------------------------------------------------------------------------
*/

async function handleSubmit() {

    errors.value = {}

    generalError.value = ''

    loading.value = true


    try {

        const response =
            await axios.post(
                '/api/register',
                form
            )


        successMessage.value =
            response.data.message


    } catch (error) {

        /*
        |--------------------------------------------------------------------------
        | Validation Error
        |--------------------------------------------------------------------------
        */

        if (
            error.response?.status === 422
        ) {

            errors.value =
                error.response.data.errors ||
                {}


            /*
            |--------------------------------------------------------------------------
            | Kalau captcha salah/kedaluwarsa,
            | otomatis generate captcha baru.
            |--------------------------------------------------------------------------
            */

            if (
                errors.value.captcha
            ) {

                await loadCaptcha()

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Unauthorized / Forbidden
        |--------------------------------------------------------------------------
        */

        else if (
            error.response?.status === 403
        ) {

            generalError.value =
                error.response.data.message ||
                'Akses ditolak.'

        }


        /*
        |--------------------------------------------------------------------------
        | Error lainnya
        |--------------------------------------------------------------------------
        */

        else {

            generalError.value =
                error.response?.data?.message ||
                'Terjadi kesalahan, silakan coba lagi.'

        }

    } finally {

        loading.value = false

    }

}


/*
|--------------------------------------------------------------------------
| INITIAL CAPTCHA
|--------------------------------------------------------------------------
*/

onMounted(() => {

    loadCaptcha()

})


/*
|--------------------------------------------------------------------------
| CLEANUP
|--------------------------------------------------------------------------
*/

onBeforeUnmount(() => {

    if (captchaObjectUrl) {

        URL.revokeObjectURL(
            captchaObjectUrl
        )

    }

})

</script>