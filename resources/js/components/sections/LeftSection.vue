<template>
    <section class="w-full px-6 sm:px-10 lg:px-12 py-8 text-white">

        <!-- ================= HEADER ================= -->
        <div class="flex items-center gap-3">

            <img
                src="/public/images/logo-kota-bogor.png"
                alt="Logo Kota Bogor"
                class="w-10 h-10 object-contain shrink-0"
            />

            <div>

                <h2 class="text-[18px] sm:text-[20px] font-bold leading-none">
                    Layanan Diskominfo
                </h2>

                <p class="text-[14px] sm:text-[16px] text-white/90 mt-1">
                    Kota Bogor
                </p>

            </div>

        </div>

        <!-- ================= HERO ================= -->

        <div class="mt-8">

            <h1
                class="font-extrabold leading-[1.1] text-3xl sm:text-4xl lg:text-[44px]"
            >
                Judul
                <br>
                Website
            </h1>

            <p
                class="mt-3 w-full max-w-[560px] leading-6 text-[15px] sm:text-[16px] text-white/90"
            >
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                Accusamus temporibus, consequatur aspernatur pariatur
                molestiae, dignissimos voluptates officiis, consequatur aspernatur pariatur
                molestiae, dignissimos voluptates.
            </p>

            <!-- Search -->
            <SearchBar />

            <!-- Service Card -->
            <div class="flex flex-wrap gap-4 mt-4">

                <p v-if="loading" class="text-white/80 text-sm">Memuat layanan...</p>

                <ServiceListCard
                    v-else
                    v-for="service in services"
                    :key="service.id"
                    compact
                    :title="service.nama"
                    :description="service.deskripsi"
                    :link="`/layanan/${service.slug}`"
                />

                <p v-if="!loading && services.length === 0" class="text-white/80 text-sm">
                    Belum ada layanan.
                </p>

            </div>

            <!-- Button -->

            <div class="mt-8 flex flex-wrap justify-center lg:justify-start gap-4">
                <router-link
                    to="/lacak-permohonan"
                    class="bg-[#0A66C2] hover:bg-[#0959aa] transition px-6 py-3 rounded-2xl font-semibold flex items-center gap-3 shadow-lg"
                >
                    <i class="bi bi-arrow-left"></i>

                    Lacak Permohonan
                </router-link>

                <router-link
                    to="/layanan"
                    class="bg-[#0A66C2] hover:bg-[#0959aa] transition px-6 py-3 rounded-2xl font-semibold flex items-center gap-3 shadow-lg"
                >
                    Lihat Semua Layanan

                    <i class="bi bi-arrow-right"></i>

                </router-link>
            </div>

        </div>

    </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from '../../lib/axios'
import SearchBar from '../SearchBar.vue'
import ServiceListCard from '../ServiceListCard.vue'

const services = ref([])
const loading = ref(true)

onMounted(async () => {
    try {
        const response = await axios.get('/api/layanan')
        services.value = response.data.data.slice(0, 3)
    } finally {
        loading.value = false
    }
})
</script>