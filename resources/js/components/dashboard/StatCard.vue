<template>
    <div class="bg-white rounded-2xl border border-gray-100 p-5">

        <div class="flex items-center justify-between">

            <div>
                <p class="text-sm text-gray-500">
                    {{ title }}
                </p>

                <p class="text-2xl font-bold text-gray-800 mt-2">
                    {{ value }}
                </p>
            </div>

            <div
                class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-xl"
            >
                {{ icon }}
            </div>

        </div>

    </div>
</template>

<script setup>
defineProps({
    title: String,
    value: [String, Number],
    icon: String,
})
</script>