<template>

<div
class="w-[235px] rounded-[22px] bg-white shadow-xl p-5 hover:-translate-y-1 transition">

    <i class="bi bi-file-earmark-text text-[22px] text-gray-800"></i>

    <h3
    class="mt-2 text-lg font-semibold text-gray-800">

        {{ title }}

    </h3>

    <p
    class="mt-2 text-sm leading-6 text-gray-500 line-clamp-2">

        {{ description }}

    </p>

    <router-link
    :to="link"
    class="mt-3 inline-flex items-center gap-2 bg-[#0A66C2] hover:bg-[#0959aa] transition text-white text-sm font-semibold px-4 py-2 rounded-full"
    >

        Lihat Persyaratan

        <i class="bi bi-arrow-right"></i>

    </router-link>

</div>

</template>

<script setup>
defineProps({
    title: { type: String, default: 'Nama Layanan' },
    description: { type: String, default: 'Lorem ipsum dolor sit amet consectetur adipisicing elit.' },
    link: { type: String, default: '/layanan' },
})
</script>