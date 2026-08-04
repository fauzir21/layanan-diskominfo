<template>
    <main class="min-h-screen bg-gradient-to-b from-[#EAF3FF] to-white">

        <section class="max-w-[1200px] mx-auto px-10 py-10">

            <!-- Kembali -->
            <router-link
                to="/"
                class="inline-flex items-center gap-2 text-blue-600 hover:underline font-medium"
            >
                ← Kembali Ke Beranda
            </router-link>

            <!-- Judul -->
            <h1 class="mt-6 text-5xl font-extrabold text-[#0A66C2] text-center">
                Daftar Seluruh Layanan
            </h1>

            <p class="mt-3 text-center text-gray-700">
                Temukan Layanan yang Anda butuhkan dan lihat persyaratan yang di perlukan
            </p>

            <!-- Search -->
            <div class="mt-8 flex justify-center">
                <div class="bg-white rounded-2xl h-14 w-[760px] flex items-center px-6 shadow-md">

                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari Layanan ........"
                        class="flex-1 outline-none text-gray-700"
                    />

                    <button
                        v-if="search"
                        @click="search = ''"
                        class="text-gray-400 hover:text-red-500 px-3"
                    >
                        <i class="bi bi-x-lg"></i>
                    </button>

                    <div class="w-px h-6 bg-gray-300 mx-3"></div>

                    <i class="bi bi-search text-gray-500 text-lg"></i>

                </div>
            </div>

            <!-- Filter -->
            <div class="mt-6 flex justify-center gap-3">

                <button
                    v-for="filter in filters"
                    :key="filter.value"
                    @click="activeFilter = filter.value"
                    :class="[
                        'px-5 py-2 rounded-full font-medium text-sm transition',
                        activeFilter === filter.value
                            ? 'bg-[#0A66C2] text-white shadow'
                            : 'bg-white text-gray-700 border border-gray-300 hover:border-blue-400'
                    ]"
                >
                    {{ filter.label }}
                </button>

            </div>

            <!-- Loading -->
            <p v-if="loading" class="mt-10 text-center text-gray-500">Memuat layanan...</p>

            <!-- Grid Layanan -->
            <div
                v-else
                class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 justify-items-center"
            >

                <ServiceListCard
                    v-for="service in services"
                    :key="service.id"
                    :title="service.nama"
                    :description="service.deskripsi"
                    :link="`/layanan/${service.slug}`"
                />

            </div>

            <p
                v-if="!loading && services.length === 0"
                class="mt-10 text-center text-gray-500"
            >
                Tidak ada layanan yang ditemukan.
            </p>

        </section>

    </main>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import axios from '../lib/axios'
import ServiceListCard from '../components/ServiceListCard.vue'

const search = ref('')
const activeFilter = ref('semua')
const services = ref([])
const loading = ref(true)

const filters = [
    { label: 'Semua Layanan', value: 'semua' },
    { label: 'Eksternal', value: 'eksternal' },
    { label: 'Internal', value: 'internal' },
]

async function fetchServices() {
    loading.value = true

    try {
        const response = await axios.get('/api/layanan', {
            params: {
                kategori: activeFilter.value,
                search: search.value,
            },
        })
        services.value = response.data.data
    } finally {
        loading.value = false
    }
}

let debounceTimer = null

watch([activeFilter, search], () => {
    clearTimeout(debounceTimer)
    debounceTimer = setTimeout(fetchServices, 300)
})

onMounted(fetchServices)
</script>