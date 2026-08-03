<template>
    <main
        class="relative min-h-screen bg-cover bg-center flex items-center justify-center py-10"
        style="background-image: url('/images/bg2.png')"
    >
        <!-- Overlay -->
        <div class="absolute inset-0 bg-[#0058A8]/40"></div>

        <!-- Logo pojok kiri atas -->
        <img
            src="/public/images/logo-kota-bogor.png"
            alt="Logo Kota Bogor"
            class="absolute top-10 left-5 w-20 z-6"
        />

        <!-- Form -->
        <div class="relative z-10">
            <RegisterForm />
        </div>
    </main>
</template>

<script setup>
import RegisterForm from '../components/RegisterForm.vue'
</script>