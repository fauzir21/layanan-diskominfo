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

            <!-- Grid Layanan -->
            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 justify-items-center">

                <ServiceListCard
                    v-for="service in filteredServices"
                    :key="service.id"
                    :title="service.title"
                    :description="service.description"
                    :link="service.link"
                />

            </div>

            <p
                v-if="filteredServices.length === 0"
                class="mt-10 text-center text-gray-500"
            >
                Tidak ada layanan yang ditemukan.
            </p>

        </section>

    </main>
</template>

<script setup>
import { ref, computed } from 'vue'
import ServiceListCard from '../components/ServiceListCard.vue'

const search = ref('')
const activeFilter = ref('semua')

const filters = [
    { label: 'Semua Layanan', value: 'semua' },
    { label: 'Eksternal', value: 'eksternal' },
    { label: 'Internal', value: 'internal' },
]

// Ganti dummy data ini nanti pakai data asli dari backend/API
const services = ref([
    {
        id: 1,
        title: 'Nama Kategori Layanan',
        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua....',
        category: 'eksternal',
        link: '/layanan/1',
    },
    {
        id: 2,
        title: 'Nama Kategori Layanan',
        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua....',
        category: 'internal',
        link: '/layanan/2',
    },
])

const filteredServices = computed(() => {
    return services.value.filter((service) => {
        const matchFilter = activeFilter.value === 'semua' || service.category === activeFilter.value
        const matchSearch = service.title.toLowerCase().includes(search.value.toLowerCase())
        return matchFilter && matchSearch
    })
})
</script>