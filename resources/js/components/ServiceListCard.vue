<template>

<div
class="w-[380px] rounded-[24px] bg-white shadow-md p-7">

    <i class="bi bi-file-earmark-text text-[26px] text-gray-800"></i>

    <h3
    class="mt-3 text-xl font-semibold text-gray-800">

        {{ title }}

    </h3>

    <p
    class="mt-3 text-[15px] leading-6 text-gray-500 line-clamp-3">

        {{ description }}

    </p>

    <div class="mt-5">

        <router-link
        :to="link"
        class="inline-flex items-center gap-2 bg-[#0A66C2] hover:bg-[#0959aa] transition text-white text-sm font-semibold px-5 py-2.5 rounded-full"
        >

            Lihat Persyaratan

            <i class="bi bi-arrow-right"></i>

        </router-link>

    </div>

</div>

</template>

<script setup>
defineProps({
    title: { type: String, default: 'Nama Kategori Layanan' },
    description: { type: String, default: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua....' },
    link: { type: String, default: '/' },
})
</script>