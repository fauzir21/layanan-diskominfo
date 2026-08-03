<template>
    <Transition name="fade">

        <div
            v-if="show"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
        >

            <div
                class="w-[420px] rounded-3xl bg-white shadow-2xl overflow-hidden"
            >

                <!-- Header -->
                <div class="px-8 pt-8">

                    <div class="flex justify-between items-center">

                        <h2 class="text-2xl font-bold text-[#005AA7]">
                            Lupa Password
                        </h2>

                        <button
                            @click="$emit('close')"
                            class="text-gray-500 hover:text-red-500 text-xl"
                        >
                            ✕
                        </button>

                    </div>

                    <p class="mt-3 text-gray-500 text-sm leading-6">

                        Masukkan email yang terdaftar.
                        Kami akan mengirimkan link untuk reset password.

                    </p>

                </div>

                <!-- Body -->

                <div class="px-8 py-6">

                    <label class="text-sm font-medium">

                        Email

                    </label>

                    <input
                        type="email"
                        placeholder="Masukkan email..."
                        class="mt-2 w-full h-11 rounded-xl border border-gray-300 px-4 outline-none focus:border-blue-500"
                    />

                </div>

                <!-- Footer -->

                <div
                    class="px-8 pb-8 flex justify-end gap-3"
                >

                    <button
                        @click="$emit('close')"
                        class="px-5 py-2 rounded-xl border"
                    >
                        Batal
                    </button>

                    <button
                        class="px-5 py-2 rounded-xl bg-[#005AA7] text-white hover:bg-blue-700"
                    >
                        Kirim
                    </button>

                </div>

            </div>

        </div>

    </Transition>
</template>

<script setup>

defineProps({
    show: Boolean
})

defineEmits([
    'close'
])

</script>

<style scoped>

.fade-enter-active,
.fade-leave-active{

    transition:.25s;

}

.fade-enter-from,
.fade-leave-to{

    opacity:0;

}

</style>