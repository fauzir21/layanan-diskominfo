<template>
    <section class="w-full px-12 py-8 text-white">

        <!-- ================= HEADER ================= -->
        <div class="flex items-center gap-3">

            <img
                src="/public/images/logo-kota-bogor.png"
                alt="Logo Kota Bogor"
                class="w-10 h-10 object-contain"
            />

            <div>

                <h2 class="text-[20px] font-bold leading-none">
                    Layanan Diskominfo
                </h2>

                <p class="text-[16px] text-white/90 mt-1">
                    Kota Bogor
                </p>

            </div>

        </div>

        <!-- ================= HERO ================= -->

        <div class="mt-8">

            <h1
                class="font-extrabold leading-[1.05]"
                style="font-size:44px;"
            >
                Judul
                <br>
                Website
            </h1>

            <p
                class="mt-3 w-[560px] leading-5 text-[16px] text-white/90"
            >
                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                Accusamus temporibus, consequatur aspernatur pariatur
                molestiae, dignissimos voluptates officiis, consequatur aspernatur pariatur
                molestiae, dignissimos voluptates.
            </p>

            <!-- Search -->
            <SearchBar />

            <!-- Service Card -->
            <div class="flex gap-5 mt-3">

                <ServiceCard />

                <ServiceCard />

                <ServiceCard />

            </div>

            <!-- Button -->

            <div class="mt-8 flex justify-center">
                <button
                    class="bg-[#0A66C2] hover:bg-[#0959aa] transition px-6 py-3 rounded-2xl font-semibold flex items-center gap-3 shadow-lg"
                >
                    Lihat Semua Layanan

                    <i class="bi bi-arrow-right"></i>

                </button>
            </div>

        </div>

    </section>
</template>

<script setup>
import SearchBar from '../SearchBar.vue'
import ServiceCard from '../ServiceCard.vue'
</script>