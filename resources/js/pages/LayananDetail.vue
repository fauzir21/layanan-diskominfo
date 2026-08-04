<template>
    <main class="min-h-screen bg-gradient-to-b from-[#EAF3FF] to-white py-10">

        <section class="max-w-[800px] mx-auto px-6">

            <router-link
                to="/layanan"
                class="inline-flex items-center gap-2 text-blue-600 hover:underline font-medium"
            >
                ← Kembali ke Daftar Layanan
            </router-link>

            <div v-if="loading" class="mt-10 text-center text-gray-500">
                Memuat...
            </div>

            <div v-else-if="!layanan" class="mt-10 text-center text-gray-500">
                Layanan tidak ditemukan.
            </div>

            <div v-else class="mt-6 bg-white rounded-[32px] shadow-lg p-10">

                <span
                    class="text-xs font-semibold px-3 py-1 rounded-full"
                    :class="layanan.kategori === 'eksternal' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700'"
                >
                    {{ layanan.kategori }}
                </span>

                <h1 class="mt-4 text-3xl font-bold text-gray-800">{{ layanan.nama }}</h1>

                <p class="mt-4 text-gray-600 leading-7">{{ layanan.deskripsi }}</p>

                <div class="mt-8">
                    <h2 class="text-lg font-semibold text-gray-800">Persyaratan</h2>

                    <ul v-if="layanan.persyaratans?.length" class="mt-3 space-y-2">
                        <li
                            v-for="syarat in layanan.persyaratans"
                            :key="syarat.id"
                            class="flex items-start gap-2 text-gray-700"
                        >
                            <i class="bi bi-check-circle-fill text-green-600 mt-1"></i>
                            {{ syarat.nama_syarat }}
                        </li>
                    </ul>

                    <p v-else class="mt-3 text-gray-500">Belum ada persyaratan yang ditentukan.</p>
                </div>

            </div>

        </section>

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