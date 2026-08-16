<template>
    <main class="min-h-screen bg-gray-50 py-10">

        <section class="max-w-[1100px] mx-auto px-6">

            <!-- Loading status login -->
            <div v-if="authStore.isLoading" class="text-center text-gray-500 py-20">
                Memuat...
            </div>

            <!-- Bukan admin -->
            <div
                v-else-if="!authStore.user || !isAdmin"
                class="text-center text-gray-500 py-20"
            >
                Akses ditolak. Halaman ini khusus admin.
                <br>

                <router-link
                    to="/"
                    class="text-blue-600 hover:underline"
                >
                    Kembali ke Beranda
                </router-link>
            </div>

            <!-- Konten Admin -->
            <div v-else>

                <!-- Header -->
                <div class="flex items-center justify-between">
                    <h1 class="text-3xl font-bold text-gray-800">
                        Kelola Layanan
                    </h1>

                    <button
                        @click="openCreateForm"
                        class="bg-[#0A66C2] hover:bg-[#0959aa] transition text-white font-semibold px-5 py-2.5 rounded-xl"
                    >
                        + Tambah Layanan
                    </button>
                </div>

                <!-- Pesan -->
                <div
                    v-if="message"
                    class="mt-4 rounded-xl bg-green-50 border border-green-300 text-green-700 px-4 py-3 text-sm"
                >
                    {{ message }}
                </div>

                <!-- List Layanan -->
                <div
                    v-if="loadingList"
                    class="mt-8 text-gray-500"
                >
                    Memuat data layanan...
                </div>

                <div
                    v-else
                    class="mt-8 space-y-4"
                >

                    <div
                        v-for="item in layanans"
                        :key="item.id"
                        class="bg-white rounded-2xl shadow p-6 flex items-start justify-between"
                    >

                        <div>

                            <!-- Kategori -->
                            <span
                                class="text-xs font-semibold px-3 py-1 rounded-full"
                                :class="
                                    item.kategori === 'eksternal'
                                        ? 'bg-blue-100 text-blue-700'
                                        : 'bg-amber-100 text-amber-700'
                                "
                            >
                                {{ item.kategori }}
                            </span>

                            <!-- Nama -->
                            <h3 class="mt-2 text-lg font-semibold text-gray-800">
                                {{ item.nama }}
                            </h3>

                            <!-- Deskripsi -->
                            <p class="mt-1 text-sm text-gray-500 max-w-xl">
                                {{ item.deskripsi }}
                            </p>

                            <!-- Jumlah persyaratan -->
                            <p
                                v-if="item.persyaratans?.length"
                                class="mt-2 text-xs text-gray-400"
                            >
                                {{ item.persyaratans.length }} persyaratan
                            </p>

                        </div>

                        <!-- Action -->
                        <div class="flex gap-2 shrink-0">

                            <button
                                @click="openEditForm(item)"
                                class="text-sm font-medium text-blue-600 hover:underline"
                            >
                                Edit
                            </button>

                            <button
                                @click="deleteLayanan(item)"
                                class="text-sm font-medium text-red-600 hover:underline"
                            >
                                Hapus
                            </button>

                        </div>

                    </div>

                    <p
                        v-if="layanans.length === 0"
                        class="text-gray-500"
                    >
                        Belum ada layanan.
                    </p>

                </div>

            </div>

        </section>

        <!-- ================================================= -->
        <!-- MODAL TAMBAH / EDIT -->
        <!-- ================================================= -->

        <div
            v-if="showForm"
            class="fixed inset-0 bg-black/40 flex items-center justify-center px-4 z-50"
        >

            <div
                class="bg-white rounded-3xl shadow-2xl w-full max-w-[700px] p-8 max-h-[90vh] overflow-y-auto"
            >

                <!-- Header Modal -->
                <div class="flex items-center justify-between">

                    <h2 class="text-2xl font-bold text-gray-800">
                        {{ editingId ? 'Edit Layanan' : 'Tambah Layanan' }}
                    </h2>

                    <button
                        type="button"
                        @click="showForm = false"
                        class="text-gray-400 hover:text-gray-700 text-xl"
                    >
                        ✕
                    </button>

                </div>

                <form
                    @submit.prevent="submitForm"
                    class="mt-6 space-y-5"
                >

                    <!-- Nama Layanan -->
                    <div>

                        <label class="font-medium text-gray-800 block mb-2">
                            Nama Layanan
                        </label>

                        <input
                            v-model="form.nama"
                            type="text"
                            placeholder="Masukkan nama layanan"
                            class="w-full h-12 rounded-xl bg-gray-100 border border-gray-200 px-4 outline-none focus:border-blue-500"
                        />

                        <p
                            v-if="errors.nama"
                            class="text-sm text-red-500 mt-1"
                        >
                            {{ errors.nama[0] }}
                        </p>

                    </div>

                    <!-- Kategori -->
                    <div>

                        <label class="font-medium text-gray-800 block mb-2">
                            Kategori Pengguna
                        </label>

                        <select
                            v-model="form.kategori"
                            class="w-full h-12 rounded-xl bg-gray-100 border border-gray-200 px-4 outline-none focus:border-blue-500"
                        >
                            <option value="eksternal">
                                Eksternal
                            </option>

                            <option value="internal">
                                Internal
                            </option>
                        </select>

                    </div>

                    <!-- Deskripsi -->
                    <div>

                        <label class="font-medium text-gray-800 block mb-2">
                            Deskripsi
                        </label>

                        <textarea
                            v-model="form.deskripsi"
                            rows="4"
                            placeholder="Masukkan deskripsi layanan"
                            class="w-full rounded-xl bg-gray-100 border border-gray-200 px-4 py-3 outline-none focus:border-blue-500"
                        ></textarea>

                        <p
                            v-if="errors.deskripsi"
                            class="text-sm text-red-500 mt-1"
                        >
                            {{ errors.deskripsi[0] }}
                        </p>

                    </div>

                    <!-- ================================================= -->
                    <!-- PERSYARATAN -->
                    <!-- ================================================= -->

                    <div>

                        <div class="flex items-center justify-between mb-3">

                            <label class="font-medium text-gray-800">
                                Persyaratan Layanan
                            </label>

                            <button
                                type="button"
                                @click="addPersyaratan"
                                class="text-sm text-blue-600 hover:underline"
                            >
                                + Tambah Syarat
                            </button>

                        </div>

                        <!-- Empty -->
                        <div
                            v-if="form.persyaratans.length === 0"
                            class="rounded-xl border border-dashed border-gray-300 p-5 text-center text-sm text-gray-400"
                        >
                            Belum ada persyaratan.
                            <br>
                            Klik "+ Tambah Syarat" untuk menambahkan.
                        </div>

                        <!-- List Persyaratan -->
                        <div
                            v-for="(syarat, index) in form.persyaratans"
                            :key="index"
                            class="border border-gray-200 rounded-2xl p-4 mb-3 bg-gray-50"
                        >

                            <!-- Header -->
                            <div class="flex items-center justify-between mb-3">

                                <span class="text-sm font-semibold text-gray-700">
                                    Persyaratan {{ index + 1 }}
                                </span>

                                <button
                                    type="button"
                                    @click="removePersyaratan(index)"
                                    class="text-red-500 hover:text-red-700"
                                    title="Hapus persyaratan"
                                >
                                    <i class="bi bi-trash"></i>
                                </button>

                            </div>

                            <!-- Nama Persyaratan -->
                            <div class="mb-3">

                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                    Nama Persyaratan
                                </label>

                                <input
                                    v-model="syarat.nama_syarat"
                                    type="text"
                                    placeholder="Contoh: KTP / Nomor HP / Surat Pengantar"
                                    class="w-full h-11 rounded-xl bg-white border border-gray-200 px-4 outline-none focus:border-blue-500"
                                />

                            </div>

                            <!-- Tipe + Wajib -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                                <!-- Tipe -->
                                <div>

                                    <label class="block text-xs font-medium text-gray-600 mb-1">
                                        Tipe Input
                                    </label>

                                    <select
                                        v-model="syarat.tipe"
                                        class="w-full h-11 rounded-xl bg-white border border-gray-200 px-3 outline-none focus:border-blue-500"
                                    >

                                        <option value="file">
                                            File / Dokumen
                                        </option>

                                        <option value="text">
                                            Isian Teks
                                        </option>

                                    </select>

                                </div>

                                <!-- Wajib -->
                                <div>

                                    <label class="block text-xs font-medium text-gray-600 mb-1">
                                        Status
                                    </label>

                                    <label
                                        class="h-11 px-3 rounded-xl bg-white border border-gray-200 flex items-center gap-2 cursor-pointer"
                                    >

                                        <input
                                            v-model="syarat.wajib"
                                            type="checkbox"
                                            class="w-4 h-4"
                                        />

                                        <span class="text-sm text-gray-700">
                                            Persyaratan wajib
                                        </span>

                                    </label>

                                </div>

                            </div>

                        </div>

                        <!-- Error backend -->
                        <p
                            v-if="errors.persyaratans"
                            class="text-sm text-red-500 mt-2"
                        >
                            {{ errors.persyaratans[0] }}
                        </p>

                    </div>

                    <!-- ================================================= -->
                    <!-- BUTTON -->
                    <!-- ================================================= -->

                    <div class="flex justify-end gap-3 pt-2">

                        <button
                            type="button"
                            @click="showForm = false"
                            class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50"
                        >
                            Batal
                        </button>

                        <button
                            type="submit"
                            :disabled="saving"
                            class="px-5 py-2.5 rounded-xl bg-[#0A66C2] hover:bg-[#0959aa] text-white font-semibold disabled:opacity-50"
                        >
                            {{ saving ? 'Menyimpan...' : 'Simpan' }}
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </main>
</template>

<script setup>

import {
    ref,
    reactive,
    computed,
    onMounted
} from 'vue'

import axios from '../lib/axios'
import { useAuthStore } from '../stores/auth'

const authStore = useAuthStore()

const isAdmin = computed(() => {
    return authStore.user?.role === 'admin'
})

const layanans = ref([])

const loadingList = ref(true)

const message = ref('')

const showForm = ref(false)

const editingId = ref(null)

const saving = ref(false)

const errors = ref({})

const form = reactive({

    nama: '',

    deskripsi: '',

    kategori: 'eksternal',

    persyaratans: []

})

onMounted(async () => {

    if (!authStore.user && !authStore.isLoading) {
        await authStore.fetchUser()
    }

    fetchLayanans()

})

async function fetchLayanans() {

    loadingList.value = true

    try {

        const response = await axios.get('/api/layanan')

        layanans.value = response.data.data

    } catch (error) {

        console.error('Gagal mengambil layanan:', error)

    } finally {

        loadingList.value = false

    }

}

function createEmptyPersyaratan() {

    return {

        nama_syarat: '',

        tipe: 'file',

        wajib: true

    }

}

function addPersyaratan() {

    form.persyaratans.push(
        createEmptyPersyaratan()
    )

}

function removePersyaratan(index) {

    form.persyaratans.splice(index, 1)

}

function resetForm() {

    form.nama = ''

    form.deskripsi = ''

    form.kategori = 'eksternal'

    form.persyaratans = []

    errors.value = {}

}

function openCreateForm() {

    resetForm()

    editingId.value = null

    showForm.value = true

}

function openEditForm(item) {

    resetForm()

    editingId.value = item.id

    form.nama = item.nama

    form.deskripsi = item.deskripsi

    form.kategori = item.kategori

    form.persyaratans = (item.persyaratans || []).map((p) => ({

        nama_syarat: p.nama_syarat,

        tipe: p.tipe || 'file',

        wajib: Boolean(p.wajib)

    }))

    showForm.value = true

}

async function submitForm() {

    errors.value = {}

    saving.value = true

    message.value = ''

    try {

        const payload = {

            nama: form.nama,

            deskripsi: form.deskripsi,

            kategori: form.kategori,

            persyaratans: form.persyaratans

                .filter((syarat) => syarat.nama_syarat.trim() !== '')

                .map((syarat) => ({

                    nama_syarat: syarat.nama_syarat.trim(),

                    tipe: syarat.tipe,

                    wajib: Boolean(syarat.wajib)

                }))

        }

        if (editingId.value) {

            await axios.put(
                `/api/admin/layanan/${editingId.value}`,
                payload
            )

            message.value =
                'Layanan berhasil diperbarui.'

        } else {

            await axios.post(
                '/api/admin/layanan',
                payload
            )

            message.value =
                'Layanan berhasil ditambahkan.'

        }

        showForm.value = false

        await fetchLayanans()

    } catch (error) {

        console.error(
            'Gagal menyimpan layanan:',
            error
        )

        if (error.response?.status === 422) {

            errors.value =
                error.response.data.errors || {}

        } else {

            alert(
                error.response?.data?.message ||
                'Terjadi kesalahan saat menyimpan layanan.'
            )

        }

    } finally {

        saving.value = false

    }

}

async function deleteLayanan(item) {

    if (
        !confirm(
            `Hapus layanan "${item.nama}"?`
        )
    ) {
        return
    }

    try {

        await axios.delete(
            `/api/admin/layanan/${item.id}`
        )

        message.value =
            'Layanan berhasil dihapus.'

        await fetchLayanans()

    } catch (error) {

        console.error(
            'Gagal menghapus layanan:',
            error
        )

        alert(
            error.response?.data?.message ||
            'Gagal menghapus layanan.'
        )

    }

}

</script>