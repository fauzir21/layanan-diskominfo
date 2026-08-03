<template>
    <main
        class="relative min-h-screen bg-cover bg-center overflow-hidden"
        style="background-image: url('/images/bg.png');"
    >
        <!-- Overlay -->
        <div class="absolute inset-0 bg-[#0058A8]/20"></div

        <!-- Container -->
        <div
            class="relative mx-auto h-screen max-w-[1365px] grid grid-cols-[1.15fr_0.85fr]"
        >
            <!-- Left -->
            <div class="flex items-center">
                <LeftSection />
            </div>

            <!-- Right -->
            <div class="flex items-center justify-center pr-10">
                <RightSection />
            </div>
        </div>
    </main>
</template>

<script setup>
import LeftSection from '../components/sections/LeftSection.vue'
import RightSection from '../components/sections/RightSection.vue'
</script>