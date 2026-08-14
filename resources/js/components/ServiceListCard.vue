<template>

<div
:class="[
    'rounded-[clamp(16px,2.5vw,28px)] bg-white shadow-md h-full flex flex-col',
    compact ? 'w-full p-[clamp(14px,2.2vw,24px)]' : 'w-[360px] p-7'
]">

    <i
    :class="compact ? 'text-[clamp(16px,2vw,24px)]' : 'text-[26px]'"
    class="bi bi-file-earmark-text text-gray-800"></i>

    <h3
    :class="compact ? 'text-[clamp(13px,1.5vw,18px)]' : 'text-xl'"
    class="mt-[clamp(6px,1vw,14px)] font-semibold text-gray-800 line-clamp-1">

        {{ title }}

    </h3>

    <p
    :class="compact ? 'text-[clamp(11px,1.2vw,14px)] mt-[clamp(4px,0.8vw,10px)] line-clamp-2' : 'text-[15px] mt-3 line-clamp-3'"
    class="leading-[1.5] text-gray-500 flex-1">

        {{ description }}

    </p>

    <div :class="compact ? 'mt-[clamp(8px,1.4vw,14px)]' : 'mt-5'">

        <router-link
        :to="link"
        :class="compact ? 'text-[clamp(10px,1.2vw,14px)] px-[clamp(12px,1.8vw,20px)] py-[clamp(6px,1vw,10px)] gap-1.5' : 'text-sm px-5 py-2.5 gap-2'"
        class="inline-flex items-center bg-[#0A66C2] hover:bg-[#0959aa] transition text-white font-semibold rounded-full whitespace-nowrap"
        >

            {{ buttonText }}

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
    buttonText: { type: String, default: 'Lihat Persyaratan' },
})
</script>