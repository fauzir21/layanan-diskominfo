<template>
    <Transition name="fade">

        <div
            v-if="show"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
        >

            <div class="w-full max-w-[520px] max-h-[90vh] overflow-y-auto rounded-3xl bg-white shadow-2xl">

                <!-- Sukses -->
                <div v-if="nomorTiket" class="px-8 py-10 text-center">

                    <div class="w-16 h-16 mx-auto rounded-full bg-green-50 flex items-center justify-center">
                        <i class="bi bi-check-circle-fill text-3xl text-green-600"></i>
                    </div>

                    <h2 class="mt-4 text-xl font-bold text-gray-800">
                        Pengajuan Berhasil Dikirim
                    </h2>

                    <p class="mt-2 text-sm text-gray-500">
                        Simpan nomor tiket ini untuk melacak status permohonan Anda.
                    </p>

                    <div class="mt-5 bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 font-mono font-bold text-lg text-[#0A66C2] tracking-wide">
                        {{ nomorTiket }}
                    </div>

                    <div class="mt-6 flex gap-3">
                        <button
                            @click="closeAndReset"
                            class="flex-1 px-5 py-2.5 rounded-xl border hover:bg-gray-50 transition"
                        >
                            Tutup
                        </button>

                        <router-link
                            :to="`/lacak-permohonan?tiket=${nomorTiket}`"
                            class="flex-1 px-5 py-2.5 rounded-xl bg-[#0A66C2] text-white text-center hover:bg-[#0959aa] transition"
                        >
                            Lacak Sekarang
                        </router-link>
                    </div>

                </div>

                <!-- Form -->
                <form v-else @submit.prevent="handleSubmit">

                    <div class="px-8 pt-8">

                        <div class="flex justify-between items-center">

                            <h2 class="text-xl font-bold text-[#005AA7]">
                                Ajukan {{ layanan?.nama }}
                            </h2>

                            <button
                                type="button"
                                @click="closeAndReset"
                                class="text-gray-500 hover:text-red-500 text-xl"
                            >
                                ✕
                            </button>

                        </div>

                        <p class="mt-2 text-gray-500 text-sm leading-6">
                            Lengkapi persyaratan berikut sebelum mengirim permohonan.
                        </p>

                        <div
                            v-if="generalError"
                            class="mt-4 rounded-xl bg-red-50 border border-red-300 text-red-700 px-4 py-3 text-sm"
                        >
                            {{ generalError }}
                        </div>

                    </div>

                    <div class="px-8 py-6 space-y-5">

                        <div v-if="!layanan?.persyaratans?.length" class="text-sm text-gray-500">
                            Layanan ini tidak memiliki persyaratan tambahan — langsung kirim permohonan.
                        </div>

                        <div
                            v-for="syarat in layanan?.persyaratans"
                            :key="syarat.id"
                        >
                            <label class="text-sm font-medium text-gray-700">
                                {{ syarat.nama_syarat }}
                                <span v-if="syarat.wajib" class="text-red-500">*</span>
                            </label>

                            <input
                                v-if="syarat.tipe === 'file'"
                                type="file"
                                @change="onFileChange(syarat.id, $event)"
                                class="mt-2 w-full text-sm rounded-xl border border-gray-300 px-3 py-2 outline-none focus:border-blue-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-[#EAF3FF] file:text-[#0A66C2] file:font-medium"
                            />

                            <input
                                v-else
                                v-model="jawabanText[syarat.id]"
                                type="text"
                                :placeholder="`Masukkan ${syarat.nama_syarat}`"
                                class="mt-2 w-full h-11 rounded-xl border border-gray-300 px-4 outline-none focus:border-blue-500"
                            />

                            <p v-if="fieldErrors[syarat.id]" class="mt-1 text-xs text-red-600">
                                {{ fieldErrors[syarat.id] }}
                            </p>
                        </div>

                    </div>

                    <div class="px-8 pb-8 flex justify-end gap-3">

                        <button
                            type="button"
                            @click="closeAndReset"
                            class="px-5 py-2 rounded-xl border"
                        >
                            Batal
                        </button>

                        <button
                            type="submit"
                            :disabled="submitting"
                            class="px-5 py-2 rounded-xl bg-[#005AA7] text-white hover:bg-blue-700 disabled:opacity-50"
                        >
                            {{ submitting ? 'Mengirim...' : 'Kirim Permohonan' }}
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </Transition>
</template>

<script setup>
import { reactive, ref, watch } from 'vue'
import axios from '../lib/axios'

const props = defineProps({
    show: Boolean,
    layanan: Object,
})

const emit = defineEmits(['close'])

const jawabanFile = reactive({})
const jawabanText = reactive({})
const fieldErrors = reactive({})
const generalError = ref('')
const submitting = ref(false)
const nomorTiket = ref('')

watch(() => props.show, (isShown) => {
    if (isShown) {
        // reset form tiap kali modal dibuka ulang
        Object.keys(jawabanFile).forEach((key) => delete jawabanFile[key])
        Object.keys(jawabanText).forEach((key) => delete jawabanText[key])
        Object.keys(fieldErrors).forEach((key) => delete fieldErrors[key])
        generalError.value = ''
        nomorTiket.value = ''
    }
})

function onFileChange(persyaratanId, event) {
    jawabanFile[persyaratanId] = event.target.files[0] || null
}

function closeAndReset() {
    emit('close')
}

async function handleSubmit() {
    generalError.value = ''
    Object.keys(fieldErrors).forEach((key) => delete fieldErrors[key])
    submitting.value = true

    try {
        const formData = new FormData()
        formData.append('layanan_id', props.layanan.id)

        for (const syarat of props.layanan.persyaratans ?? []) {
            if (syarat.tipe === 'file') {
                if (jawabanFile[syarat.id]) {
                    formData.append(`jawaban[${syarat.id}]`, jawabanFile[syarat.id])
                }
            } else {
                if (jawabanText[syarat.id]) {
                    formData.append(`jawaban[${syarat.id}]`, jawabanText[syarat.id])
                }
            }
        }

        const response = await axios.post('/api/pengajuan', formData)
        nomorTiket.value = response.data.data.nomor_tiket
    } catch (error) {
        if (error.response?.status === 422) {
            const errors = error.response.data.errors || {}
            for (const key in errors) {
                // key formatnya "jawaban.{id}"
                const id = key.split('.')[1]
                fieldErrors[id] = errors[key][0]
            }
            generalError.value = 'Ada persyaratan yang belum lengkap.'
        } else {
            generalError.value = error.response?.data?.message || 'Terjadi kesalahan, silakan coba lagi.'
        }
    } finally {
        submitting.value = false
    }
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: .25s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>