<template>
    <main
        class="relative min-h-screen bg-cover bg-center"
        style="background-image: url('/images/bg.png');"
    >
        <!-- Overlay -->
        <div class="absolute inset-0 bg-[#0058A8]/40"></div>

        <!-- Container -->
        <div
            class="relative mx-auto max-w-[1365px] flex flex-col lg:grid lg:grid-cols-[1.15fr_0.85fr] lg:min-h-screen"
        >
            <!-- Left -->
            <div class="flex items-center py-10 lg:py-0">
                <LeftSection />
            </div>

            <!-- Right -->
            <div class="flex items-center justify-center px-6 pb-12 lg:pb-0 lg:pr-10">
                <RightSection />
            </div>
        </div>
    </main>
</template>

<script setup>
import LeftSection from '../components/sections/LeftSection.vue'
import RightSection from '../components/sections/RightSection.vue'
</script>