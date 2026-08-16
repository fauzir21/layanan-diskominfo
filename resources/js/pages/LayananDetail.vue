<template>
    <main class="detail-page">

        <!-- ========================================= -->
        <!-- BACKGROUND ATAS -->
        <!-- ========================================= -->

        <div class="detail-background"></div>

        <!-- ========================================= -->
        <!-- HEADER -->
        <!-- ========================================= -->

        <div class="detail-header">

            <div class="header-left">

                <img
                    src="/public/images/logo-kota-bogor.png"
                    alt="Logo Kota Bogor"
                    class="logo"
                />

                <router-link
                    to="/layanan"
                    class="back-link"
                >
                    <i class="bi bi-arrow-left"></i>

                    <span>
                        Kembali Ke Daftar Layanan
                    </span>
                </router-link>

            </div>

        </div>

        <!-- ========================================= -->
        <!-- LOADING -->
        <!-- ========================================= -->

        <div
            v-if="loading"
            class="loading-state"
        >
            Memuat...
        </div>

        <!-- ========================================= -->
        <!-- NOT FOUND -->
        <!-- ========================================= -->

        <div
            v-else-if="!layanan"
            class="loading-state"
        >
            Layanan tidak ditemukan.
        </div>

        <!-- ========================================= -->
        <!-- CONTENT -->
        <!-- ========================================= -->

        <template v-else>

            <!-- TITLE -->

            <section class="title-section">

                <h1>
                    {{ layanan.nama }}
                </h1>

                <p>
                    Temukan Layanan yang Anda butuhkan
                    dan lihat persyaratan yang di perlukan
                </p>

            </section>


            <!-- ===================================== -->
            <!-- MAIN CARD -->
            <!-- ===================================== -->

            <section class="detail-card">

                <!-- DESKRIPSI -->

                <div class="description-section">

                    <h2>
                        Deskripsi layanan
                    </h2>

                    <p>
                        {{ layanan.deskripsi }}
                    </p>

                </div>


                <div class="divider"></div>


                <!-- ================================= -->
                <!-- PERSYARATAN -->
                <!-- ================================= -->

                <div class="requirement-section">

                    <h2>
                        Persyaratan Berkas
                    </h2>


                    <!-- ADA DATA -->

                    <div
                        v-if="layanan.persyaratans?.length"
                        class="requirement-grid"
                    >

                        <div
                            v-for="syarat in layanan.persyaratans"
                            :key="syarat.id"
                            class="requirement-card"
                        >

                            <!-- ICON -->

                            <div class="requirement-icon">

                                <img
                                    v-if="syarat.tipe === 'file'"
                                    src="/public/images/icon-persyaratan-file.png"
                                    alt="Persyaratan file"
                                />

                                <img
                                    v-else
                                    src="/public/images/icon-persyaratan-text.png"
                                    alt="Persyaratan teks"
                                />

                            </div>


                            <!-- CONTENT -->

                            <div class="requirement-content">

                                <h3>
                                    {{ syarat.nama_syarat }}
                                </h3>


                                <span
                                    class="requirement-status"
                                    :class="{
                                        wajib: syarat.wajib,
                                        optional: !syarat.wajib
                                    }"
                                >
                                    {{
                                        syarat.wajib
                                            ? 'Wajib'
                                            : 'Opsional'
                                    }}
                                </span>


                                <!-- FILE -->

                                <div
                                    v-if="syarat.tipe === 'file'"
                                    class="requirement-meta"
                                >

                                    <span>
                                        <i class="bi bi-file-earmark"></i>

                                        Format : File
                                    </span>

                                    <span>
                                        <i class="bi bi-file-earmark"></i>

                                        Maks : 2 MB
                                    </span>

                                </div>


                                <!-- TEXT -->

                                <div
                                    v-else
                                    class="requirement-meta"
                                >

                                    <span>
                                        <i class="bi bi-input-cursor-text"></i>

                                        Format : Teks
                                    </span>

                                    <span>
                                        <i class="bi bi-file-earmark"></i>

                                        Maks : 500 Char
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- TIDAK ADA DATA -->

                    <p
                        v-else
                        class="empty-requirement"
                    >
                        Belum ada persyaratan yang
                        ditentukan.
                    </p>

                </div>


                <!-- ================================= -->
                <!-- LOGIN INFO -->
                <!-- ================================= -->

                <div
                    v-if="!authStore.user"
                    class="login-info"
                >
                    Untuk mengajukan permohonan,
                    silakan login terlebih dahulu.
                </div>


                <!-- ================================= -->
                <!-- BUTTON -->
                <!-- ================================= -->

                <div class="action-section">

                    <button
                        @click="handleAjukan"
                        class="submit-button"
                    >

                        <span>
                            Ajukan Permohonan
                        </span>

                        <i class="bi bi-arrow-right"></i>

                    </button>

                </div>

            </section>

        </template>


        <!-- ========================================= -->
        <!-- MODAL PENGAJUAN -->
        <!-- ========================================= -->

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
    onMounted
} from 'vue'

import {
    useRoute,
    useRouter
} from 'vue-router'

import axios from '../lib/axios'

import {
    useAuthStore
} from '../stores/auth'

import PengajuanModal from '../components/PengajuanModal.vue'


const route = useRoute()

const router = useRouter()

const authStore = useAuthStore()


const layanan = ref(null)

const loading = ref(true)

const showModal = ref(false)


/*
|--------------------------------------------------------------------------
| AJUKAN PERMOHONAN
|--------------------------------------------------------------------------
*/

function handleAjukan() {

    /*
    |--------------------------------------------------------------------------
    | USER BELUM LOGIN
    |--------------------------------------------------------------------------
    */

    if (!authStore.user) {

        router.push({
            path: '/login',

            query: {
                redirect: route.fullPath
            }
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


/*
|--------------------------------------------------------------------------
| AMBIL DETAIL LAYANAN
|--------------------------------------------------------------------------
*/

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


<style scoped>

/* ================================================= */
/* PAGE */
/* ================================================= */

.detail-page {

    position: relative;

    min-height: 100vh;

    background: #ffffff;

    overflow-x: hidden;

    padding-bottom: 70px;

}


/* ================================================= */
/* BACKGROUND */
/* ================================================= */

.detail-background {

    position: absolute;

    top: 0;
    left: 0;

    width: 100%;

    height: 270px;

    background-image:
        url('/public/images/bg-detail-layanan.png');

    background-position:
        center top;

    background-repeat:
        no-repeat;

    background-size:
        cover;

    z-index: 0;

}


/* ================================================= */
/* HEADER */
/* ================================================= */

.detail-header {

    position: relative;

    z-index: 2;

    width: 100%;

    max-width: 1180px;

    margin: 0 auto;

    padding: 38px 0 0;

}


.header-left {

    display: flex;

    align-items: center;

    gap: 30px;

}


.logo {

    width: 50px;

    height: 65px;

    object-fit: contain;

}


.back-link {

    display: inline-flex;

    align-items: center;

    gap: 9px;

    color: #075BFF;

    font-size: 16px;

    font-weight: 500;

    text-decoration: none;

    transition: 0.2s ease;

}


.back-link:hover {

    opacity: 0.75;

}


.back-link i {

    font-size: 22px;

}


/* ================================================= */
/* TITLE */
/* ================================================= */

.title-section {

    position: relative;

    z-index: 2;

    text-align: center;

    max-width: 1100px;

    margin: 18px auto 0;

    padding: 0 25px;

}


.title-section h1 {

    margin: 0;

    color: #0875FF;

    font-size: clamp(
        40px,
        5vw,
        64px
    );

    line-height: 1.05;

    font-weight: 800;

}


.title-section p {

    margin-top: 10px;

    color: #111111;

    font-size: clamp(
        14px,
        1.5vw,
        17px
    );

    line-height: 1.5;

}


/* ================================================= */
/* LOADING */
/* ================================================= */

.loading-state {

    position: relative;

    z-index: 2;

    min-height: 300px;

    display: flex;

    align-items: center;

    justify-content: center;

    color: #777;

}


/* ================================================= */
/* MAIN CARD */
/* ================================================= */

.detail-card {

    position: relative;

    z-index: 2;

    width: calc(
        100% - 80px
    );

    max-width: 1268px;

    margin: 35px auto 0;

    padding: 50px 60px 32px;

    background: #ffffff;

    border-radius: 24px;

    box-shadow:
        0 20px 45px
        rgba(
            0,
            0,
            0,
            0.16
        );

}


/* ================================================= */
/* DESCRIPTION */
/* ================================================= */

.description-section h2 {

    margin: 0;

    color: #111111;

    font-size: 21px;

    font-weight: 700;

}


.description-section p {

    margin-top: 18px;

    padding-left: 20px;

    color: #171717;

    font-size: 17px;

    line-height: 1.55;

}


/* ================================================= */
/* DIVIDER */
/* ================================================= */

.divider {

    height: 1px;

    background: #8c8c8c;

    margin:
        38px 0
        32px;

}


/* ================================================= */
/* REQUIREMENT */
/* ================================================= */

.requirement-section h2 {

    margin: 0;

    color: #111111;

    font-size: 21px;

    font-weight: 700;

}


/* ================================================= */
/* REQUIREMENT GRID */
/* ================================================= */

.requirement-grid {

    display: grid;

    grid-template-columns:
        repeat(
            3,
            minmax(
                0,
                1fr
            )
        );

    gap: 42px;

    margin-top: 20px;

}


/* ================================================= */
/* REQUIREMENT CARD */
/* ================================================= */

.requirement-card {

    min-height: 108px;

    display: flex;

    align-items: center;

    gap: 15px;

    padding: 18px 16px;

    background: #ffffff;

    border: 1px solid #111111;

    border-radius: 11px;

    box-sizing: border-box;

}


.requirement-icon {

    width: 58px;

    height: 58px;

    flex-shrink: 0;

    display: flex;

    align-items: center;

    justify-content: center;

}


.requirement-icon img {

    width: 58px;

    height: 58px;

    object-fit: contain;

}


/* ================================================= */
/* REQUIREMENT CONTENT */
/* ================================================= */

.requirement-content {

    min-width: 0;

}


.requirement-content h3 {

    margin: 0;

    color: #111111;

    font-size: 17px;

    line-height: 1.25;

    font-weight: 700;

}


.requirement-status {

    display: block;

    margin-top: 4px;

    font-size: 16px;

    font-weight: 500;

}


.requirement-status.wajib {

    color: #FF0000;

}


.requirement-status.optional {

    color: #888888;

}


/* ================================================= */
/* META */
/* ================================================= */

.requirement-meta {

    display: flex;

    align-items: center;

    flex-wrap: wrap;

    gap: 12px;

    margin-top: 6px;

    color: #222222;

    font-size: 12px;

}


.requirement-meta span {

    display: inline-flex;

    align-items: center;

    gap: 4px;

    white-space: nowrap;

}


.requirement-meta i {

    font-size: 13px;

}


/* ================================================= */
/* EMPTY */
/* ================================================= */

.empty-requirement {

    margin-top: 20px;

    color: #777;

}


/* ================================================= */
/* LOGIN INFO */
/* ================================================= */

.login-info {

    margin-top: 25px;

    padding: 12px 15px;

    border: 1px solid #BFDBFE;

    border-radius: 10px;

    background: #EFF6FF;

    color: #1D4ED8;

    font-size: 14px;

}


/* ================================================= */
/* ACTION */
/* ================================================= */

.action-section {

    display: flex;

    justify-content: flex-end;

    margin-top: 58px;

}


.submit-button {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 10px;

    min-width: 164px;

    height: 40px;

    padding:
        0 18px;

    border: none;

    border-radius: 10px;

    background: #1458F5;

    color: white;

    font-size: 13px;

    font-weight: 500;

    cursor: pointer;

    transition:
        background 0.2s ease,
        transform 0.2s ease;

}


.submit-button:hover {

    background: #064BEA;

    transform: translateY(-1px);

}


.submit-button i {

    font-size: 16px;

}


/* ================================================= */
/* TABLET */
/* ================================================= */

@media (
    max-width: 1000px
) {

    .detail-header {

        padding-left: 35px;

        padding-right: 35px;

    }


    .detail-card {

        width:
            calc(
                100% - 50px
            );

        padding:
            40px 40px 30px;

    }


    .requirement-grid {

        grid-template-columns:
            repeat(
                2,
                minmax(
                    0,
                    1fr
                )
            );

        gap: 20px;

    }

}


/* ================================================= */
/* MOBILE */
/* ================================================= */

@media (
    max-width: 650px
) {

    .detail-background {

        height: 210px;

        background-position:
            center top;

    }


    .detail-header {

        padding:
            25px 20px 0;

    }


    .header-left {

        gap: 14px;

    }


    .logo {

        width: 40px;

        height: 52px;

    }


    .back-link {

        font-size: 13px;

    }


    .back-link i {

        font-size: 18px;

    }


    .title-section {

        margin-top: 25px;

        padding:
            0 20px;

    }


    .title-section h1 {

        font-size:
            clamp(
                32px,
                10vw,
                45px
            );

    }


    .title-section p {

        font-size: 13px;

    }


    .detail-card {

        width:
            calc(
                100% - 28px
            );

        margin-top: 28px;

        padding:
            30px 20px 25px;

        border-radius: 20px;

    }


    .description-section h2,
    .requirement-section h2 {

        font-size: 19px;

    }


    .description-section p {

        padding-left: 0;

        font-size: 14px;

        line-height: 1.6;

    }


    .divider {

        margin:
            28px 0;

    }


    .requirement-grid {

        grid-template-columns:
            1fr;

        gap: 15px;

    }


    .requirement-card {

        min-height: 100px;

    }


    .requirement-content h3 {

        font-size: 16px;

    }


    .requirement-status {

        font-size: 14px;

    }


    .action-section {

        margin-top: 35px;

    }


    .submit-button {

        width: 100%;

    }

}


/* ================================================= */
/* SMALL MOBILE */
/* ================================================= */

@media (
    max-width: 400px
) {

    .back-link span {

        font-size: 12px;

    }


    .detail-card {

        width:
            calc(
                100% - 20px
            );

        padding:
            25px 15px;

    }


    .requirement-card {

        padding:
            14px 12px;

    }


    .requirement-icon,
    .requirement-icon img {

        width: 48px;

        height: 48px;

    }

}

</style>