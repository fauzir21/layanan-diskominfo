<template>
    <section class="w-full px-[clamp(24px,6vw,110px)] py-[clamp(28px,5vw,56px)]">

        <!-- ================= HEADER ================= -->
        <div class="flex items-center gap-3">

            <img
                src="/public/images/logo-kota-bogor.png"
                alt="Logo Kota Bogor"
                class="w-[clamp(50px,4.5vw,100px)] h-[clamp(50px,4.5vw,100px)] object-contain shrink-0"
            />

            <div>

                <h2 class="text-[clamp(18px,2.2vw,24px)] font-bold leading-none text-[#1449C7]">
                    Diskominfo
                </h2>

                <p class="text-[clamp(14px,1.8vw,18px)] text-[#1449C7] mt-1 font-bold">
                    Kota Bogor
                </p>

            </div>

        </div>

        <!-- ================= HERO ================= -->

        <div class="mt-[clamp(24px,4vw,40px)]">

            <h1
                class="font-extrabold leading-[1.1] text-[clamp(34px,6vw,68px)] text-[#0A1F44]"
            >
                Layanan Diskominfo
                <br>
                <span class="text-[#1554F0]">Kota Bogor</span>
            </h1>

            <p
                class="mt-[clamp(10px,1.5vw,16px)] w-full max-w-[640px] leading-[1.6] text-[clamp(14px,1.5vw,18px)] text-[#374151]"
            >
                Akses berbagai layanan Diskominfo Kota Bogor secara mudah dan
                terpadu. Ajukan layanan, lengkapi persyaratan, dan pantau
                status permohonan Anda secara online.
            </p>

            <!-- Search -->
            <SearchBar />

            <!-- Service Card (maksimal 3, selalu berjajar) -->
            <div class="grid grid-cols-3 gap-[clamp(8px,1.5vw,20px)] mt-[clamp(14px,2vw,24px)] w-full max-w-[860px]">

                <p v-if="loading" class="col-span-3 text-gray-600 text-sm">
                    Memuat layanan...
                </p>

                <ServiceListCard
                    v-else
                    v-for="service in services"
                    :key="service.id"
                    compact
                    :title="service.nama"
                    :description="service.deskripsi"
                    :link="`/layanan/${service.slug}`"
                    button-text="Detail Layanan"
                />

                <p v-if="!loading && services.length === 0" class="col-span-3 text-gray-600 text-sm">
                    Belum ada layanan.
                </p>

            </div>

            <!-- Button -->

            <div class="mt-[clamp(24px,4vw,40px)] flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 w-full max-w-[860px]">
                <router-link
                    to="/lacak-permohonan"
                    class="bg-[#0A66C2] hover:bg-[#0959aa] transition px-[clamp(18px,2.5vw,30px)] py-[clamp(10px,1.5vw,15px)] rounded-2xl text-[clamp(13px,1.4vw,17px)] font-semibold flex items-center justify-center gap-3 shadow-lg whitespace-nowrap text-white"
                >
                    <i class="bi bi-arrow-left"></i>

                    Cek Status Permohonan
                </router-link>

                <router-link
                    to="/layanan"
                    class="bg-[#0A66C2] hover:bg-[#0959aa] transition px-[clamp(18px,2.5vw,30px)] py-[clamp(10px,1.5vw,15px)] rounded-2xl text-[clamp(13px,1.4vw,17px)] font-semibold flex items-center justify-center gap-3 shadow-lg whitespace-nowrap text-white"
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
        // maksimal 3 layanan ditampilkan di landing page
        services.value = response.data.data.slice(0, 3)
    } finally {
        loading.value = false
    }
})
</script>