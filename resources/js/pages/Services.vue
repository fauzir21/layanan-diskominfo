<template>
    <main class="services-page">

        <!-- ===================================================== -->
        <!-- BACKGROUND OVERLAY -->
        <!-- ===================================================== -->

        <div class="services-background"></div>


        <!-- ===================================================== -->
        <!-- CONTENT -->
        <!-- ===================================================== -->

        <div class="services-content">

            <!-- ================================================= -->
            <!-- HEADER -->
            <!-- ================================================= -->

            <header class="services-header">

                <!-- Logo + Kembali -->
                <div class="top-navigation">

                    <img
                        src="/public/images/logo-kota-bogor.png"
                        alt="Logo Kota Bogor"
                        class="bogor-logo"
                    />


                    <router-link
                        to="/"
                        class="back-link"
                    >
                        <i class="bi bi-arrow-left"></i>

                        <span>Kembali Ke Beranda</span>
                    </router-link>

                </div>


                <!-- Judul -->

                <div class="title-section">

                    <h1>
                        Daftar Seluruh Layanan
                    </h1>

                    <p>
                        Temukan Layanan yang Anda butuhkan dan lihat persyaratan yang di perlukan
                    </p>

                </div>

            </header>


            <!-- ================================================= -->
            <!-- SEARCH -->
            <!-- ================================================= -->

            <section class="search-section">

                <div class="search-box">

                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari Layanan........"
                        aria-label="Cari layanan"
                    />


                    <!-- Clear -->

                    <button
                        v-if="search"
                        type="button"
                        class="clear-button"
                        @click="search = ''"
                        aria-label="Hapus pencarian"
                    >
                        <i class="bi bi-x-lg"></i>
                    </button>


                    <!-- Divider -->

                    <div class="search-divider"></div>


                    <!-- Search Icon -->

                    <button
                        type="button"
                        class="search-button"
                        @click="fetchServices"
                        aria-label="Cari"
                    >
                        <i class="bi bi-search"></i>
                    </button>

                </div>


                <!-- ================================================= -->
                <!-- FILTER -->
                <!-- ================================================= -->

                <div class="filter-wrapper">

                    <button
                        v-for="filter in filters"
                        :key="filter.value"
                        type="button"
                        class="filter-button"
                        :class="{
                            active: activeFilter === filter.value
                        }"
                        @click="activeFilter = filter.value"
                    >
                        {{ filter.label }}
                    </button>

                </div>

            </section>


            <!-- ================================================= -->
            <!-- SERVICES -->
            <!-- ================================================= -->

            <section class="services-list">

                <!-- Loading -->

                <div
                    v-if="loading"
                    class="loading-state"
                >
                    Memuat layanan...
                </div>


                <!-- Cards -->

                <div
                    v-else-if="services.length > 0"
                    class="services-grid"
                >

                    <ServiceListCard
                        v-for="service in services"
                        :key="service.id"
                        :title="service.nama"
                        :description="service.deskripsi"
                        :link="`/layanan/${service.slug}`"
                    />

                </div>


                <!-- Empty -->

                <div
                    v-else
                    class="empty-state"
                >
                    <i class="bi bi-file-earmark-x"></i>

                    <p>
                        Tidak ada layanan yang ditemukan.
                    </p>

                </div>

            </section>

        </div>

    </main>
</template>


<script setup>

import {
    ref,
    watch,
    onMounted,
    onBeforeUnmount,
} from 'vue'

import axios from '../lib/axios'

import ServiceListCard from '../components/ServiceListCard.vue'


/*
|--------------------------------------------------------------------------
| STATE
|--------------------------------------------------------------------------
*/

const search = ref('')

const activeFilter = ref('semua')

const services = ref([])

const loading = ref(true)


/*
|--------------------------------------------------------------------------
| FILTER
|--------------------------------------------------------------------------
*/

const filters = [
    {
        label: 'Semua Layanan',
        value: 'semua',
    },

    {
        label: 'Eksternal',
        value: 'eksternal',
    },

    {
        label: 'Internal',
        value: 'internal',
    },
]


/*
|--------------------------------------------------------------------------
| FETCH SERVICES
|--------------------------------------------------------------------------
*/

async function fetchServices() {

    loading.value = true

    try {

        const response = await axios.get(
            '/api/layanan',
            {
                params: {
                    kategori: activeFilter.value,
                    search: search.value,
                },
            }
        )


        services.value =
            response.data.data ?? []

    } catch (error) {

        console.error(
            'Gagal mengambil layanan:',
            error
        )

        services.value = []

    } finally {

        loading.value = false

    }

}


/*
|--------------------------------------------------------------------------
| SEARCH DEBOUNCE
|--------------------------------------------------------------------------
*/

let debounceTimer = null


watch(
    [
        activeFilter,
        search,
    ],
    () => {

        clearTimeout(
            debounceTimer
        )


        debounceTimer =
            setTimeout(
                fetchServices,
                300
            )

    }
)


/*
|--------------------------------------------------------------------------
| INITIAL LOAD
|--------------------------------------------------------------------------
*/

onMounted(() => {

    fetchServices()

})


/*
|--------------------------------------------------------------------------
| CLEANUP
|--------------------------------------------------------------------------
*/

onBeforeUnmount(() => {

    clearTimeout(
        debounceTimer
    )

})

</script>


<style scoped>

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');


/*
|--------------------------------------------------------------------------
| PAGE
|--------------------------------------------------------------------------
*/

.services-page {

    position: relative;

    min-height: 100vh;

    overflow-x: hidden;

    font-family:
        'Poppins',
        sans-serif;

}


/*
|--------------------------------------------------------------------------
| FIXED BACKGROUND
|--------------------------------------------------------------------------
|
| Background TETAP DI TEMPAT ketika halaman di-scroll.
|
*/

.services-background {

    position: fixed;

    inset: 0;

    z-index: 0;

    background-image:
        linear-gradient(
            to bottom,
            rgba(240, 247, 255, 0.25),
            rgba(255, 255, 255, 0.68)
        ),
        url('/images/bg2.png');

    background-size: cover;

    background-position: center center;

    background-repeat: no-repeat;

    background-attachment: fixed;

}


/*
|--------------------------------------------------------------------------
| CONTENT
|--------------------------------------------------------------------------
*/

.services-content {

    position: relative;

    z-index: 1;

    width: 100%;

    min-height: 100vh;

    padding:
        clamp(28px, 3vw, 42px)
        clamp(20px, 6vw, 88px)
        80px;

}


/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

.services-header {

    width: 100%;

}


/*
|--------------------------------------------------------------------------
| TOP NAVIGATION
|--------------------------------------------------------------------------
*/

.top-navigation {

    display: flex;

    align-items: center;

    gap: clamp(
        24px,
        2.5vw,
        40px
    );

}


/*
|--------------------------------------------------------------------------
| LOGO
|--------------------------------------------------------------------------
*/

.bogor-logo {

    width:
        clamp(46px, 4vw, 55px);

    height:
        clamp(56px, 5vw, 68px);

    object-fit: contain;

}


/*
|--------------------------------------------------------------------------
| BACK LINK
|--------------------------------------------------------------------------
*/

.back-link {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    color: #1554F0;

    font-size:
        clamp(13px, 1.15vw, 16px);

    font-weight: 400;

    text-decoration: none;

    transition:
        color 0.2s ease,
        transform 0.2s ease;

}


.back-link i {

    font-size:
        clamp(18px, 1.5vw, 21px);

}


.back-link:hover {

    color: #0d43c7;

    transform: translateX(-2px);

}


/*
|--------------------------------------------------------------------------
| TITLE
|--------------------------------------------------------------------------
*/

.title-section {

    margin-top:
        clamp(4px, 0.5vw, 8px);

    text-align: center;

}


.title-section h1 {

    margin: 0;

    color: #1554F0;

    font-size:
        clamp(
            36px,
            4.25vw,
            62px
        );

    font-weight: 700;

    line-height: 1.1;

    letter-spacing: -0.055em;

}


.title-section p {

    margin:
        clamp(12px, 1.2vw, 18px)
        0
        0;

    color: #161616;

    font-size:
        clamp(
            12px,
            1.05vw,
            16px
        );

    font-weight: 400;

    line-height: 1.5;

}


/*
|--------------------------------------------------------------------------
| SEARCH SECTION
|--------------------------------------------------------------------------
*/

.search-section {

    display: flex;

    flex-direction: column;

    align-items: center;

    margin-top:
        clamp(30px, 3vw, 46px);

}


/*
|--------------------------------------------------------------------------
| SEARCH BOX
|--------------------------------------------------------------------------
*/

.search-box {

    display: flex;

    align-items: center;

    width:
        min(
            100%,
            608px
        );

    height:
        clamp(46px, 3.3vw, 50px);

    padding-left:
        clamp(20px, 1.8vw, 25px);

    padding-right: 8px;

    background: rgba(
        255,
        255,
        255,
        0.9
    );

    border:
        1.5px solid #171717;

    border-radius: 999px;

    transition:
        box-shadow 0.2s ease,
        border-color 0.2s ease;

}


.search-box:focus-within {

    border-color: #1554F0;

    box-shadow:
        0 0 0 3px
        rgba(21, 84, 240, 0.08);

}


.search-box input {

    flex: 1;

    min-width: 0;

    height: 100%;

    border: 0;

    outline: none;

    background: transparent;

    color: #222;

    font-family: inherit;

    font-size:
        clamp(12px, 1vw, 14px);

}


.search-box input::placeholder {

    color: #777B8A;

}


/*
|--------------------------------------------------------------------------
| CLEAR
|--------------------------------------------------------------------------
*/

.clear-button {

    display: flex;

    align-items: center;

    justify-content: center;

    width: 35px;

    height: 100%;

    border: 0;

    background: transparent;

    color: #151515;

    cursor: pointer;

}


.clear-button i {

    font-size: 13px;

}


/*
|--------------------------------------------------------------------------
| DIVIDER
|--------------------------------------------------------------------------
*/

.search-divider {

    width: 1px;

    height: 30px;

    background: #777;

}


/*
|--------------------------------------------------------------------------
| SEARCH BUTTON
|--------------------------------------------------------------------------
*/

.search-button {

    display: flex;

    align-items: center;

    justify-content: center;

    width: 48px;

    height: 100%;

    border: 0;

    background: transparent;

    color: #111;

    cursor: pointer;

}


.search-button i {

    font-size: 19px;

}


/*
|--------------------------------------------------------------------------
| FILTER
|--------------------------------------------------------------------------
*/

.filter-wrapper {

    display: flex;

    align-items: center;

    justify-content: center;

    flex-wrap: wrap;

    gap:
        clamp(12px, 2vw, 28px);

    margin-top: 12px;

}


.filter-button {

    min-width:
        clamp(96px, 8vw, 108px);

    height:
        clamp(25px, 2vw, 29px);

    padding:
        0
        16px;

    border:
        1px solid #111;

    border-radius: 999px;

    background: rgba(
        255,
        255,
        255,
        0.88
    );

    color: #111;

    font-family: inherit;

    font-size:
        clamp(9px, 0.7vw, 11px);

    font-weight: 400;

    cursor: pointer;

    transition:
        background 0.2s ease,
        color 0.2s ease,
        border-color 0.2s ease,
        transform 0.2s ease;

}


.filter-button:hover {

    transform: translateY(-1px);

    border-color: #1554F0;

}


.filter-button.active {

    background: #1554F0;

    border-color: #1554F0;

    color: white;

}


/*
|--------------------------------------------------------------------------
| SERVICE LIST
|--------------------------------------------------------------------------
*/

.services-list {

    width: 100%;

    max-width: 1160px;

    margin:
        clamp(48px, 5vw, 66px)
        auto
        0;

}


/*
|--------------------------------------------------------------------------
| GRID
|--------------------------------------------------------------------------
|
| Desktop = maksimal 3 card per baris.
|
*/

.services-grid {

    display: grid;

    grid-template-columns:
        repeat(
            3,
            minmax(0, 1fr)
        );

    gap:
        clamp(18px, 2vw, 26px);

    align-items: stretch;

}


/*
|--------------------------------------------------------------------------
| LOADING
|--------------------------------------------------------------------------
*/

.loading-state {

    display: flex;

    align-items: center;

    justify-content: center;

    min-height: 180px;

    color: #6D7280;

    font-size: 14px;

}


/*
|--------------------------------------------------------------------------
| EMPTY
|--------------------------------------------------------------------------
*/

.empty-state {

    display: flex;

    flex-direction: column;

    align-items: center;

    justify-content: center;

    min-height: 180px;

    color: #70778A;

}


.empty-state i {

    margin-bottom: 12px;

    font-size: 38px;

}


.empty-state p {

    margin: 0;

    font-size: 14px;

}


/*
|--------------------------------------------------------------------------
| TABLET
|--------------------------------------------------------------------------
*/

@media (max-width: 1024px) {

    .services-content {

        padding:
            28px
            36px
            70px;

    }


    .services-grid {

        grid-template-columns:
            repeat(
                2,
                minmax(0, 1fr)
            );

    }

}


/*
|--------------------------------------------------------------------------
| MOBILE
|--------------------------------------------------------------------------
*/

@media (max-width: 640px) {

    .services-content {

        padding:
            22px
            18px
            60px;

    }


    .top-navigation {

        gap: 15px;

    }


    .bogor-logo {

        width: 42px;

        height: 54px;

    }


    .back-link {

        font-size: 12px;

    }


    .back-link i {

        font-size: 17px;

    }


    .title-section {

        margin-top: 28px;

    }


    .title-section h1 {

        font-size: 35px;

        letter-spacing: -0.045em;

    }


    .title-section p {

        max-width: 340px;

        margin-left: auto;

        margin-right: auto;

        font-size: 11px;

    }


    .search-section {

        margin-top: 28px;

    }


    .search-box {

        height: 46px;

    }


    .filter-wrapper {

        gap: 8px;

        margin-top: 12px;

    }


    .filter-button {

        min-width: 88px;

        padding: 0 12px;

        font-size: 9px;

    }


    .services-list {

        margin-top: 40px;

    }


    .services-grid {

        grid-template-columns: 1fr;

        gap: 16px;

    }

}


/*
|--------------------------------------------------------------------------
| VERY SMALL MOBILE
|--------------------------------------------------------------------------
*/

@media (max-width: 380px) {

    .title-section h1 {

        font-size: 30px;

    }


    .filter-button {

        min-width: 82px;

    }

}

</style>