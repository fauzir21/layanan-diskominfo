<template>
    <main
        class="relative min-h-screen overflow-x-hidden bg-cover bg-center bg-no-repeat"
        style="background-image: url('/images/bg.png');"
    >

        <!-- Background overlay -->
        <div
            class="absolute inset-0 bg-gradient-to-b from-white/10 via-white/35 to-[#001B3D]/30"
        ></div>


        <!-- Main container -->
        <div
            class="relative z-10 mx-auto flex min-h-screen w-full max-w-[1600px] flex-col lg:grid lg:grid-cols-[1.55fr_0.85fr]"
        >

            <!-- LEFT -->
            <div
                class="flex min-w-0 items-center"
            >
                <LeftSection />
            </div>


            <!-- RIGHT -->
            <div
                class="flex min-w-0 items-center justify-center px-5 py-8 sm:px-8 lg:px-8 lg:py-14 xl:px-12"
            >
                <RightSection />
            </div>

        </div>

    </main>
</template>


<script setup>
import LeftSection from '../components/sections/LeftSection.vue'
import RightSection from '../components/sections/RightSection.vue'
</script>