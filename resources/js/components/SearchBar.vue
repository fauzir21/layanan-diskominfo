<template>

    <div
        class="mt-[clamp(20px,3vw,36px)] bg-white rounded-full h-[clamp(42px,5vw,56px)] w-full max-w-[860px] flex items-center pl-[clamp(16px,2.5vw,24px)] pr-[6px] shadow-xl"
    >

        <input
            v-model="query"
            type="text"
            placeholder="Cari Layanan ........"
            class="flex-1 min-w-0 text-[clamp(13px,1.4vw,17px)] text-gray-700 placeholder:text-gray-400 outline-none"
            @keyup.enter="$emit('search', query)"
        />

        <button
            v-if="query"
            type="button"
            class="shrink-0 text-gray-400 hover:text-red-500 px-2"
            @click="query = ''; $emit('search', '')"
        >
            <i class="bi bi-x-lg text-[clamp(13px,1.4vw,17px)]"></i>
        </button>

        <div class="w-px h-[60%] bg-gray-200 mx-1 shrink-0"></div>

        <button
            type="button"
            class="shrink-0 w-[clamp(32px,4vw,42px)] h-[clamp(32px,4vw,42px)] rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-100 transition"
            @click="$emit('search', query)"
        >
            <i class="bi bi-search text-[clamp(14px,1.6vw,19px)]"></i>
        </button>

    </div>

</template>

<script setup>
import { ref } from 'vue'

const query = ref('')

defineEmits(['search'])
</script>