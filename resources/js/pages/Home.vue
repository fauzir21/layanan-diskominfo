<template>
    <main
        class="relative min-h-screen bg-cover bg-center"
        style="background-image: url('/images/bg.png');"
    >
        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-white/10 via-white/50 to-[#001B3D]/45"></div>

        <!-- Container -->
        <div
            class="relative mx-auto max-w flex flex-col lg:grid lg:grid-cols-[1.15fr_0.85fr] lg:min-h-screen"
        >
            <!-- Left -->
            <div class="flex items-center py-10 lg:py-0">
                <LeftSection />
            </div>

            <!-- Right -->
            <div class="flex items-center lg:items-stretch justify-center px-6 pb-12 lg:pb-12 lg:pt-12 lg:pr-12">
                <RightSection />
            </div>
        </div>
    </main>
</template>

<script setup>
import LeftSection from '../components/sections/LeftSection.vue'
import RightSection from '../components/sections/RightSection.vue'
</script>