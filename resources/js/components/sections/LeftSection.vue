<template>
    <section
        class="w-full px-[clamp(24px,6vw,90px)] py-[clamp(28px,5vw,56px)]"
    >

        <!-- ================= LOGO ================= -->

        <div class="flex items-center gap-[clamp(8px,1vw,14px)]">

            <img
                src="/public/images/logo-kota-bogor.png"
                alt="Logo Kota Bogor"
                class="h-[clamp(52px,5vw,82px)] w-[clamp(52px,5vw,82px)] shrink-0 object-contain"
            />

            <div>

                <h2
                    class="text-[clamp(17px,1.7vw,22px)] font-bold leading-tight text-[#1449C7]"
                >
                    Diskominfo
                </h2>

                <p
                    class="mt-0.5 text-[clamp(14px,1.4vw,18px)] font-bold leading-tight text-[#1449C7]"
                >
                    Kota Bogor
                </p>

            </div>

        </div>


        <!-- ================= HERO ================= -->

        <div
            class="mt-[clamp(28px,4.5vw,52px)]"
        >

            <h1
                class="max-w-[700px] text-[clamp(38px,5.5vw,68px)] font-extrabold leading-[0.98] tracking-[-0.04em] text-[#071F55]"
            >

                Layanan Diskominfo

                <br>

                <span class="text-[#1554F0]">
                    Kota Bogor
                </span>

            </h1>


            <p
                class="mt-[clamp(14px,1.5vw,20px)] max-w-[650px] text-[clamp(14px,1.35vw,18px)] leading-[1.5] text-[#1F2937]"
            >
                Akses berbagai layanan Diskominfo Kota Bogor secara mudah
                dan terpadu. Ajukan layanan, lengkapi persyaratan, dan
                pantau status permohonan Anda secara online.
            </p>


            <!-- ================= SEARCH ================= -->

            <SearchBar />


            <!-- ================= SERVICES ================= -->

            <div
                class="mt-[clamp(12px,1.8vw,20px)] grid w-full max-w-[860px] grid-cols-1 gap-[clamp(10px,1.2vw,16px)] sm:grid-cols-2 lg:grid-cols-3"
            >

                <!-- Loading -->

                <div
                    v-if="loading"
                    class="col-span-full py-5 text-sm text-gray-600"
                >
                    Memuat layanan...
                </div>


                <!-- Services -->

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


                <!-- Empty -->

                <div
                    v-if="!loading && services.length === 0"
                    class="col-span-full py-5 text-sm text-gray-600"
                >
                    Belum ada layanan.
                </div>

            </div>


            <!-- ================= ACTION BUTTON ================= -->

            <div
                class="mt-[clamp(24px,3.5vw,40px)] flex w-full max-w-[860px] flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
            >

                <router-link
                    to="/lacak-permohonan"
                    class="inline-flex min-h-[clamp(42px,4vw,50px)] items-center justify-center gap-2 rounded-2xl bg-[#0A66C2] px-[clamp(18px,2.5vw,30px)] text-[clamp(13px,1.2vw,16px)] font-semibold text-white shadow-lg transition hover:bg-[#0959aa]"
                >

                    <i class="bi bi-arrow-left"></i>

                    <span>
                        Cek Status Permohonan
                    </span>

                </router-link>


                <router-link
                    to="/layanan"
                    class="inline-flex min-h-[clamp(42px,4vw,50px)] items-center justify-center gap-2 rounded-2xl bg-[#0A66C2] px-[clamp(18px,2.5vw,30px)] text-[clamp(13px,1.2vw,16px)] font-semibold text-white shadow-lg transition hover:bg-[#0959aa]"
                >

                    <span>
                        Lihat Semua Layanan
                    </span>

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

        /*
        |--------------------------------------------------------------------------
        | LANDING PAGE
        |--------------------------------------------------------------------------
        | Maksimal hanya 3 layanan.
        */

        services.value =
            (response.data.data || []).slice(0, 3)

    } catch (error) {

        console.error(
            'Gagal mengambil layanan:',
            error
        )

        services.value = []

    } finally {

        loading.value = false

    }

})
</script>