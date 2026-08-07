<template>
    <main
        class="relative min-h-screen bg-cover bg-center flex items-center justify-center py-10"
        style="background-image:url('/images/background-register.jpg')"
    >
        <!-- Overlay -->
        <div class="absolute inset-0 bg-[#0058A8]/40"></div>

        <!-- Logo pojok kiri atas -->
        <img
            src="/public/images/logo-kota-bogor.png"
            alt="Logo Kota Bogor"
            class="absolute top-8 left-8 w-24 z-10"
        />

        <!-- Form -->
        <div class="relative z-10 px-4">
            <RegisterForm />
        </div>
    </main>
</template>

<script setup>
import RegisterForm from '../components/RegisterForm.vue'
</script>