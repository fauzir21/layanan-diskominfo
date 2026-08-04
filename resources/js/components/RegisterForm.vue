<template>

<div
class="bg-white rounded-[40px] shadow-2xl w-[1080px] px-16 py-10">

    <!-- Judul -->

    <h1
    class="text-center text-4xl font-semibold text-gray-800">

        Buat Akun Baru

    </h1>

    <!-- Pesan sukses -->
    <div
    v-if="successMessage"
    class="mt-6 rounded-2xl bg-green-50 border border-green-300 text-green-700 px-6 py-4 text-sm"
    >
        {{ successMessage }}
    </div>

    <!-- Pesan error umum -->
    <div
    v-if="generalError"
    class="mt-6 rounded-2xl bg-red-50 border border-red-300 text-red-700 px-6 py-4 text-sm"
    >
        {{ generalError }}
    </div>

    <!-- FORM -->

    <form
    v-if="!successMessage"
    @submit.prevent="handleSubmit"
    class="mt-10 space-y-6">

        <!-- Nama -->

        <div>

            <label class="font-medium text-gray-800 block mb-2">

                Nama Lengkap

            </label>

            <input
            v-model="form.name"
            type="text"
            placeholder="Masukan Nama Lengkap Anda"
            class="w-full h-14 rounded-2xl bg-gray-100 border border-gray-200 px-6 text-gray-700 placeholder-gray-500 outline-none focus:border-blue-500"/>

            <p v-if="errors.name" class="text-sm text-red-500 mt-2 ml-1">
                {{ errors.name[0] }}
            </p>

        </div>

        <!-- Kategori -->

        <div>

            <label class="font-medium text-gray-800 block mb-2">

                Kategori Pengguna

            </label>

            <select
            v-model="form.kategori_pengguna"
            class="w-full h-14 rounded-2xl bg-gray-100 border border-gray-200 px-6 text-gray-500 outline-none focus:border-blue-500">

                <option disabled value="">

                    Kategori Pengguna

                </option>

                <option>ASN Pemerintah Kota Bogor</option>
                <option>Non ASN</option>
                <option>Masyarakat Umum</option>
                <option>Mahasiswa</option>
                <option>Instansi</option>

            </select>

            <p v-if="errors.kategori_pengguna" class="text-sm text-red-500 mt-2 ml-1">
                {{ errors.kategori_pengguna[0] }}
            </p>

        </div>

        <!-- Email -->

        <div>

            <label class="font-medium text-gray-800 block mb-2">

                Email

            </label>

            <input
            v-model="form.email"
            type="email"
            placeholder="Masukan Email Anda"
            class="w-full h-14 rounded-2xl bg-gray-100 border border-gray-200 px-6 text-gray-700 placeholder-gray-500 outline-none focus:border-blue-500"/>

            <p v-if="errors.email" class="text-sm text-red-500 mt-2 ml-1">
                {{ errors.email[0] }}
            </p>

        </div>

        <!-- Password -->

        <div
        class="grid grid-cols-2 gap-10">

            <div>

                <label class="font-medium text-gray-800 block mb-2">

                    Password

                </label>

                <input
                v-model="form.password"
                type="password"
                placeholder="Buat Password"
                class="w-full h-14 rounded-2xl bg-gray-100 border border-gray-200 px-6 text-gray-700 placeholder-gray-500 outline-none focus:border-blue-500"/>

                <p v-if="errors.password" class="text-sm text-red-500 mt-2 ml-1">
                    {{ errors.password[0] }}
                </p>

            </div>

            <div>

                <label class="font-medium text-gray-800 block mb-2">

                    Konfimasi Password

                </label>

                <input
                v-model="form.password_confirmation"
                type="password"
                placeholder="Konfirmasi Password"
                class="w-full h-14 rounded-2xl bg-gray-100 border border-gray-200 px-6 text-gray-700 placeholder-gray-500 outline-none focus:border-blue-500"/>

            </div>

        </div>

        <!-- Button -->

        <div class="flex items-center justify-between">

            <button
            type="submit"
            :disabled="loading"
            class="text-[16px] px-10 h-12 rounded-2xl bg-blue-600 text-white font-bold text-lg hover:bg-blue-700 transition disabled:opacity-50">

                {{ loading ? 'Memproses...' : 'Daftar Akun' }}

            </button>

            <!-- Back -->

            <router-link
            to="/"
            class="inline-flex items-center gap-2 text-blue-600 hover:underline font-medium">

                ← Kembali ke Beranda

            </router-link>

        </div>

    </form>

</div>

</template>

<script setup>
import { ref, reactive } from 'vue'
import axios from '../lib/axios'

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    kategori_pengguna: '',
})

const errors = ref({})
const generalError = ref('')
const successMessage = ref('')
const loading = ref(false)

async function handleSubmit() {
    errors.value = {}
    generalError.value = ''
    loading.value = true

    try {
        const response = await axios.post('/api/register', form)
        successMessage.value = response.data.message
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors || {}
        } else {
            generalError.value = 'Terjadi kesalahan, silakan coba lagi.'
        }
    } finally {
        loading.value = false
    }
}
</script>