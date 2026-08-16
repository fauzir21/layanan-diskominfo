<template>
    <main
        class="relative min-h-screen w-full overflow-x-hidden bg-cover bg-center bg-no-repeat"
        style="background-image: url('/images/bg.png');"
    >

        <!-- Background overlay -->
        <div
            class="absolute inset-0 bg-white/10"
        ></div>


        <!-- Content -->
        <div
            class="relative z-10 flex min-h-screen w-full items-center justify-center px-3 py-6 sm:px-5 sm:py-8 md:px-8 lg:py-10"
        >

            <RegisterForm />

        </div>

    </main>
</template>


<script setup>
import RegisterForm from '../components/RegisterForm.vue'
</script>