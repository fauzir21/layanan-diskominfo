<template>
    <main
        class="min-h-screen bg-gradient-to-b from-[#EAF3FF] to-white"
    >

        <div class="max-w-[1180px] mx-auto px-6 sm:px-10 pt-8">

            <!-- Header -->
            <div class="flex items-center gap-4">

                <img
                    src="/public/images/logo-kota-bogor.png"
                    alt="Logo Kota Bogor"
                    class="w-10 h-10 object-contain shrink-0"
                />

                <router-link
                    to="/layanan"
                    class="inline-flex items-center gap-2 text-blue-600 hover:underline font-medium"
                >
                    ← Kembali Ke Daftar Layanan
                </router-link>

            </div>

            <div v-if="loading" class="mt-16 text-center text-gray-500">
                Memuat...
            </div>

            <div v-else-if="!layanan" class="mt-16 text-center text-gray-500">
                Layanan tidak ditemukan.
            </div>

            <template v-else>

                <!-- Judul -->
                <h1 class="mt-6 text-4xl sm:text-5xl font-extrabold text-[#0A66C2] text-center">
                    {{ layanan.nama }}
                </h1>

                <p class="mt-3 text-center text-gray-700">
                    Temukan Layanan yang Anda butuhkan dan lihat persyaratan yang di perlukan
                </p>

                <!-- Card -->
                <div class="mt-8 bg-white rounded-[32px] shadow-lg px-8 sm:px-12 py-10 mb-12">

                    <h2 class="text-xl font-bold text-gray-800">Deskripsi layanan</h2>

                    <p class="mt-3 text-gray-700 leading-7">
                        {{ layanan.deskripsi }}
                    </p>

                    <hr class="my-8 border-gray-200">

                    <h2 class="text-xl font-bold text-gray-800">Persyaratan Berkas</h2>

                    <div
                        v-if="layanan.persyaratans?.length"
                        class="mt-5 flex flex-wrap gap-5"
                    >
                        <div
                            v-for="syarat in layanan.persyaratans"
                            :key="syarat.id"
                            class="w-full sm:w-[260px] border border-gray-300 rounded-2xl p-4 flex gap-3"
                        >
                            <i class="bi bi-file-earmark-arrow-up-fill text-[32px] text-[#0A66C2] shrink-0"></i>

                            <div>
                                <h3 class="font-semibold text-gray-800">{{ syarat.nama_syarat }}</h3>
                                <p class="text-red-500 text-sm font-medium">Wajib</p>

                                <div class="mt-1 flex flex-wrap gap-x-3 gap-y-1 text-xs text-gray-500">
                                    <span class="inline-flex items-center gap-1">
                                        <i class="bi bi-file-earmark"></i> Format: PDF/JPG
                                    </span>
                                    <span class="inline-flex items-center gap-1">
                                        <i class="bi bi-file-earmark"></i> Maks: 2MB
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p v-else class="mt-3 text-gray-500">Belum ada persyaratan yang ditentukan.</p>

                    <div class="mt-8 flex justify-end">
                        <button
                            class="inline-flex items-center gap-2 bg-[#0A66C2] hover:bg-[#0959aa] transition text-white font-semibold px-6 py-3 rounded-xl"
                        >
                            Ajukan Permohonan
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>

                </div>

            </template>

        </div>

    </main>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from '../lib/axios'

const route = useRoute()
const layanan = ref(null)
const loading = ref(true)

onMounted(async () => {
    try {
        const response = await axios.get(`/api/layanan/${route.params.slug}`)
        layanan.value = response.data.data
    } catch (error) {
        layanan.value = null
    } finally {
        loading.value = false
    }
})
</script>