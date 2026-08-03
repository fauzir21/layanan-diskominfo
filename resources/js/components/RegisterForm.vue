<template>

<div
class="bg-white rounded-[40px] shadow-2xl w-[1080px] px-16 py-10">

    <!-- Judul -->

    <h1
    class="text-center text-[25px] font-semibold text-gray-800">

        Buat Akun Baru

    </h1>

    <!-- FORM -->

    <form
    class="mt-2 space-y-6">

        <!-- Nama -->

        <div>

            <label class="font-medium text-gray-800 block mb-2">

                Nama Lengkap

            </label>

            <input
            type="text"
            placeholder="Masukan Nama Lengkap Anda"
            class="text-[14px] w-full h-12 rounded-2xl bg-gray-100 border border-gray-200 px-6 text-gray-700 placeholder-gray-500 outline-none focus:border-blue-500"/>

            <p
            class="text-[12px] text-sm text-gray-500 mt-2 ml-1">

                *Masukan Nama Lengkap atau Nama Instansi Anda
            </p>

        </div>

        <!-- Kategori -->

        <div>

            <label class="font-medium text-gray-800 block mb-2">

                Kategori Pengguna

            </label>

            <select
            class="text-[14px] w-full h-12 rounded-2xl bg-gray-100 border border-gray-200 px-6 text-gray-500 outline-none focus:border-blue-500">

                <option disabled selected>

                    Kategori Pengguna

                </option>

                <option>

                    ASN Pemerintah Kota Bogor

                </option>

                <option>

                    Non ASN

                </option>

                <option>

                    Masyarakat Umum

                </option>

                <option>

                    Mahasiswa

                </option>

                <option>

                    Instansi

                </option>

            </select>

        </div>

        <!-- Email -->

        <div>

            <label class="font-medium text-gray-800 block mb-2">

                Email

            </label>

            <input
            type="email"
            placeholder="Masukan Email Anda"
            class="text-[14px] w-full h-12 rounded-2xl bg-gray-100 border border-gray-200 px-6 text-gray-700 placeholder-gray-500 outline-none focus:border-blue-500"/>


        </div>

        <!-- Password -->

        <div
        class="grid grid-cols-2 gap-10">

            <div>

                <label class="font-medium text-gray-800 block mb-2">

                    Password

                </label>

                <input
                type="password"
                placeholder="Buat Password"
                class="text-[14px] w-full h-12 rounded-2xl bg-gray-100 border border-gray-200 px-6 text-gray-700 placeholder-gray-500 outline-none focus:border-blue-500"/>

            </div>

            <div>

                <label class="font-medium text-gray-800 block mb-2">

                    Konfimasi Password

                </label>

                <input
                type="password"
                placeholder="Konfirmasi Password"
                class="text-[14px] w-full h-12 rounded-2xl bg-gray-100 border border-gray-200 px-6 text-gray-700 placeholder-gray-500 outline-none focus:border-blue-500"/>

            </div>

        </div>

        <!-- Captcha -->

        <div>

            <label class="font-medium text-gray-800 block mb-2">

                Captcha

            </label>

            <div
            class="w-[240px] h-15 border border-gray-300 rounded-2xl flex items-center justify-center bg-white">

                <span
                class="text-4xl font-black tracking-wide font-mono">

                    Captcha

                </span>

            </div>

        </div>

        <!-- Input Captcha -->

        <input
        type="text"
        placeholder="Masukan Kode Captcha"
        class="text-[14px] w-full h-12 rounded-2xl bg-gray-100 border border-gray-200 px-6 text-gray-700 placeholder-gray-500 outline-none focus:border-blue-500"/>

        <!-- Button -->

        <div class="flex items-center justify-between">

            <button
            type="submit"
            class="text-[16px] px-30 h-12 rounded-2xl bg-blue-600 text-white font-bold text-lg hover:bg-blue-700 transition">

                Daftar Akun

            </button>

            <!-- Back -->

            <router-link
            to="/"
            class="inline-flex items-center gap-2 text-blue-600 hover:underline font-medium">

                ← Kembali ke Beranda

            </router-link>

        </div>

    </form>

</div>

</template>

<script setup>
</script>