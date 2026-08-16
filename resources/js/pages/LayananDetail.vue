<template>
    <main
        class="min-h-screen bg-gradient-to-b from-[#EAF3FF] to-white"
    >
        <div
            class="mx-auto max-w-[1180px] px-6 pt-8 sm:px-10"
        >
            <!-- Header -->

            <div class="flex items-center gap-4">
                <img
                    src="/public/images/logo-kota-bogor.png"
                    alt="Logo Kota Bogor"
                    class="h-10 w-10 shrink-0 object-contain"
                />

                <router-link
                    to="/layanan"
                    class="inline-flex items-center gap-2 font-medium text-blue-600 hover:underline"
                >
                    <i class="bi bi-arrow-left"></i>

                    Kembali Ke Daftar Layanan
                </router-link>
            </div>

            <!-- Loading -->

            <div
                v-if="loading"
                class="mt-16 text-center text-gray-500"
            >
                Memuat...
            </div>

            <!-- Not Found -->

            <div
                v-else-if="!layanan"
                class="mt-16 text-center text-gray-500"
            >
                Layanan tidak ditemukan.
            </div>

            <template v-else>
                <!-- Judul -->

                <h1
                    class="mt-6 text-center text-4xl font-extrabold text-[#0A66C2] sm:text-5xl"
                >
                    {{ layanan.nama }}
                </h1>

                <p
                    class="mt-3 text-center text-gray-700"
                >
                    Temukan Layanan yang Anda butuhkan
                    dan lihat persyaratan yang di perlukan
                </p>

                <!-- Card -->

                <div
                    class="mb-12 mt-8 rounded-[32px] bg-white px-8 py-10 shadow-lg sm:px-12"
                >
                    <!-- DESKRIPSI -->

                    <h2
                        class="text-xl font-bold text-gray-800"
                    >
                        Deskripsi layanan
                    </h2>

                    <p
                        class="mt-3 leading-7 text-gray-700"
                    >
                        {{ layanan.deskripsi }}
                    </p>

                    <hr
                        class="my-8 border-gray-200"
                    />

                    <!-- PERSYARATAN -->

                    <h2
                        class="text-xl font-bold text-gray-800"
                    >
                        Persyaratan Berkas
                    </h2>

                    <div
                        v-if="
                            layanan.persyaratans?.length
                        "
                        class="mt-5 flex flex-wrap gap-5"
                    >
                        <div
                            v-for="syarat in layanan.persyaratans"
                            :key="syarat.id"
                            class="w-full rounded-2xl border border-gray-300 p-4 sm:w-[260px]"
                        >
                            <div class="flex gap-3">
                                <i
                                    class="shrink-0 text-[32px]"
                                    :class="
                                        syarat.tipe === 'file'
                                            ? 'bi bi-file-earmark-arrow-up-fill text-[#0A66C2]'
                                            : 'bi bi-pencil-square text-[#0A66C2]'
                                    "
                                ></i>

                                <div>
                                    <h3
                                        class="font-semibold text-gray-800"
                                    >
                                        {{ syarat.nama_syarat }}
                                    </h3>

                                    <p
                                        class="text-sm font-medium"
                                        :class="
                                            syarat.wajib
                                                ? 'text-red-500'
                                                : 'text-gray-400'
                                        "
                                    >
                                        {{
                                            syarat.wajib
                                                ? 'Wajib'
                                                : 'Opsional'
                                        }}
                                    </p>

                                    <div
                                        class="mt-1 flex flex-wrap gap-x-3 gap-y-1 text-xs text-gray-500"
                                    >
                                        <span
                                            v-if="
                                                syarat.tipe ===
                                                'file'
                                            "
                                            class="inline-flex items-center gap-1"
                                        >
                                            <i
                                                class="bi bi-file-earmark"
                                            ></i>

                                            Format: PDF/JPG
                                        </span>

                                        <span
                                            v-if="
                                                syarat.tipe ===
                                                'file'
                                            "
                                            class="inline-flex items-center gap-1"
                                        >
                                            <i
                                                class="bi bi-file-earmark"
                                            ></i>

                                            Maks: 2MB
                                        </span>

                                        <span
                                            v-else
                                            class="inline-flex items-center gap-1"
                                        >
                                            <i
                                                class="bi bi-input-cursor-text"
                                            ></i>

                                            Isian teks
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p
                        v-else
                        class="mt-3 text-gray-500"
                    >
                        Belum ada persyaratan yang
                        ditentukan.
                    </p>

                    <!-- LOGIN INFO -->

                    <div
                        v-if="!authStore.user"
                        class="mt-6 rounded-xl border border-blue-200 bg-blue-50 px-5 py-4 text-sm text-blue-700"
                    >
                        Untuk mengajukan permohonan,
                        silakan login terlebih dahulu.
                    </div>

                    <!-- ACTION -->

                    <div
                        class="mt-8 flex justify-end"
                    >
                        <button
                            @click="handleAjukan"
                            class="inline-flex items-center gap-2 rounded-xl bg-[#0A66C2] px-6 py-3 font-semibold text-white transition hover:bg-[#0959aa]"
                        >
                            Ajukan Permohonan

                            <i
                                class="bi bi-arrow-right"
                            ></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- PENGAJUAN MODAL -->

        <PengajuanModal
            :show="showModal"
            :layanan="layanan"
            @close="showModal = false"
        />
    </main>
</template>

<script setup>
import {
    ref,
    onMounted,
} from 'vue'

import {
    useRoute,
    useRouter,
} from 'vue-router'

import axios from '../lib/axios'

import { useAuthStore } from '../stores/auth'

import PengajuanModal from '../components/PengajuanModal.vue'

const route = useRoute()
const router = useRouter()

const authStore = useAuthStore()

const layanan = ref(null)

const loading = ref(true)

const showModal = ref(false)

function handleAjukan() {
    /*
    |--------------------------------------------------------------------------
    | USER BELUM LOGIN
    |--------------------------------------------------------------------------
    |
    | Simpan URL layanan sekarang supaya setelah login
    | user bisa kembali ke halaman layanan tersebut.
    |
    */

    if (!authStore.user) {
        router.push({
            path: '/login',
            query: {
                redirect: route.fullPath,
            },
        })

        return
    }

    /*
    |--------------------------------------------------------------------------
    | USER SUDAH LOGIN
    |--------------------------------------------------------------------------
    */

    showModal.value = true
}

onMounted(async () => {
    try {
        const response = await axios.get(
            `/api/layanan/${route.params.slug}`
        )

        layanan.value =
            response.data.data
    } catch (error) {
        console.error(
            'Gagal mengambil detail layanan:',
            error
        )

        layanan.value = null
    } finally {
        loading.value = false
    }
})
</script>