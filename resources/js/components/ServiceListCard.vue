<template>

<div
:class="[
    'rounded-[24px] bg-white shadow-md',
    compact ? 'w-[205px] p-5' : 'w-[360px] p-7'
]">

    <i
    :class="compact ? 'text-[20px]' : 'text-[26px]'"
    class="bi bi-file-earmark-text text-gray-800"></i>

    <h3
    :class="compact ? 'text-base' : 'text-xl'"
    class="mt-3 font-semibold text-gray-800 line-clamp-1">

        {{ title }}

    </h3>

    <p
    :class="compact ? 'text-xs mt-2 line-clamp-2' : 'text-[15px] mt-3 line-clamp-3'"
    class="leading-6 text-gray-500">

        {{ description }}

    </p>

    <div :class="compact ? 'mt-3' : 'mt-5'">

        <router-link
        :to="link"
        :class="compact ? 'text-xs px-4 py-2' : 'text-sm px-5 py-2.5'"
        class="inline-flex items-center gap-2 bg-[#0A66C2] hover:bg-[#0959aa] transition text-white font-semibold rounded-full"
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
    compact: { type: Boolean, default: false },
})
</script>