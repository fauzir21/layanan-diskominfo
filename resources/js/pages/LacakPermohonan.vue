<template>
    <main class="tracking-page">

        <!-- ===================================================== -->
        <!-- BACKGROUND IMAGE ELLIPSE -->
        <!-- ===================================================== -->

        <section class="hero-background">

            <div class="hero-image"></div>

            <!-- Dekorasi titik -->
            <div class="dot-pattern"></div>

        </section>


        <!-- ===================================================== -->
        <!-- CONTENT -->
        <!-- ===================================================== -->

        <div class="tracking-content">

            <!-- ================================================= -->
            <!-- HEADER -->
            <!-- ================================================= -->

            <header class="tracking-header">

                <router-link
                    to="/"
                    class="back-link"
                >
                    <i class="bi bi-arrow-left"></i>

                    <span>Kembali Ke Beranda</span>
                </router-link>


                <div class="title-section">

                    <h1>
                        Lacak Permohonan
                    </h1>

                    <p>
                        Masukkan nomor tiket Anda untuk melihat status terbaru dari
                        <br class="desktop-only">
                        permohonan anda
                    </p>

                </div>

            </header>


            <!-- ================================================= -->
            <!-- TRACKING CARD -->
            <!-- ================================================= -->

            <section class="tracking-card-wrapper">

                <!-- ================================================= -->
                <!-- ICON DI ATAS CARD -->
                <!-- ================================================= -->

                <div class="tracking-icon">

                    <div class="icon-inner">

                        <i class="bi bi-file-earmark-text"></i>

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- CARD -->
                <!-- ================================================= -->

                <div class="tracking-card">

                    <!-- ============================================= -->
                    <!-- MESSAGE -->
                    <!-- ============================================= -->

                    <div
                        v-if="message"
                        class="message-box"
                        :class="messageType === 'error'
                            ? 'message-error'
                            : 'message-success'"
                    >
                        {{ message }}
                    </div>


                    <!-- ============================================= -->
                    <!-- FORM -->
                    <!-- ============================================= -->

                    <form
                        @submit.prevent="handleSearch"
                        class="tracking-form"
                    >

                        <!-- ========================================= -->
                        <!-- NOMOR TIKET -->
                        <!-- ========================================= -->

                        <div class="form-section">

                            <div class="section-title">

                                <div class="section-icon">
                                    <i class="bi bi-ticket-perforated"></i>
                                </div>

                                <h2>
                                    Nomor Tiket
                                </h2>

                            </div>


                            <input
                                v-model="ticketNumber"
                                type="text"
                                placeholder="Masukan nomor tiket ( Contoh : TK-20260720-xxxx )"
                                class="text-input"
                            />

                        </div>


                        <div class="form-divider"></div>


                        <!-- ========================================= -->
                        <!-- CAPTCHA -->
                        <!-- ========================================= -->

                        <div class="form-section captcha-section">

                            <div class="section-title">

                                <div class="section-icon">
                                    <i class="bi bi-shield-check"></i>
                                </div>

                                <h2>
                                    Captcha
                                </h2>

                            </div>


                            <div class="captcha-row">

                                <!-- CAPTCHA -->
                                <div class="captcha-display">

                                    <span>
                                        {{ captchaCode }}
                                    </span>

                                </div>


                                <!-- REFRESH -->
                                <button
                                    type="button"
                                    class="captcha-refresh"
                                    @click="refreshCaptcha"
                                    aria-label="Refresh captcha"
                                >

                                    <i class="bi bi-arrow-repeat"></i>

                                </button>

                            </div>


                            <!-- CAPTCHA INPUT -->

                            <input
                                v-model="captchaInput"
                                type="text"
                                placeholder="Masukan Code Captcha"
                                class="text-input captcha-input"
                            />

                        </div>


                        <!-- ========================================= -->
                        <!-- SUBMIT -->
                        <!-- ========================================= -->

                        <button
                            type="submit"
                            class="search-ticket-button"
                            :disabled="loading"
                        >

                            <span>
                                {{ loading ? 'Mencari...' : 'Cari Tiket' }}
                            </span>

                        </button>

                    </form>


                    <!-- ================================================= -->
                    <!-- HASIL PELACAKAN -->
                    <!-- ================================================= -->

                    <div
                        v-if="result"
                        class="tracking-result"
                    >

                        <div class="result-header">

                            <h3>
                                {{ result.layanan }}
                            </h3>

                            <span
                                class="status-badge"
                                :class="statusStyle(result.status).badge"
                            >
                                {{ statusStyle(result.status).label }}
                            </span>

                        </div>


                        <p class="result-date">

                            Diajukan
                            {{ formatTanggal(result.tanggal_pengajuan) }}

                            <template v-if="result.tanggal_selesai">

                                · Selesai
                                {{ formatTanggal(result.tanggal_selesai) }}

                            </template>

                        </p>


                        <!-- RIWAYAT -->

                        <div
                            v-if="result.riwayat?.length"
                            class="history-list"
                        >

                            <div
                                v-for="(item, index) in result.riwayat"
                                :key="index"
                                class="history-item"
                            >

                                <div class="history-line">

                                    <div class="history-dot"></div>

                                    <div
                                        v-if="
                                            index <
                                            result.riwayat.length - 1
                                        "
                                        class="history-connector"
                                    ></div>

                                </div>


                                <div class="history-content">

                                    <p class="history-title">
                                        {{ statusStyle(item.status).label }}
                                    </p>

                                    <p
                                        v-if="item.keterangan"
                                        class="history-description"
                                    >
                                        {{ item.keterangan }}
                                    </p>

                                    <p class="history-date">
                                        {{
                                            formatTanggal(
                                                item.tanggal_disposisi,
                                                true
                                            )
                                        }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </section>

        </div>

    </main>
</template>


<script setup>

import {
    ref,
    onMounted,
} from 'vue'

import {
    useRoute,
} from 'vue-router'

import axios from '../lib/axios'


/*
|--------------------------------------------------------------------------
| ROUTE
|--------------------------------------------------------------------------
*/

const route = useRoute()


/*
|--------------------------------------------------------------------------
| STATE
|--------------------------------------------------------------------------
*/

const ticketNumber = ref('')

const captchaInput = ref('')

const captchaCode = ref('A7X92')

const loading = ref(false)

const message = ref('')

const messageType = ref('')

const result = ref(null)


/*
|--------------------------------------------------------------------------
| STATUS MAP
|--------------------------------------------------------------------------
*/

const STATUS_MAP = {

    menunggu_diproses: {
        label: 'Menunggu Diproses',
        badge: 'status-waiting',
    },

    diproses: {
        label: 'Diproses',
        badge: 'status-processing',
    },

    perbaikan: {
        label: 'Perlu Perbaikan',
        badge: 'status-repair',
    },

    ditolak: {
        label: 'Ditolak',
        badge: 'status-rejected',
    },

    selesai: {
        label: 'Selesai',
        badge: 'status-completed',
    },

}


/*
|--------------------------------------------------------------------------
| STATUS STYLE
|--------------------------------------------------------------------------
*/

function statusStyle(status) {

    return (
        STATUS_MAP[status] || {
            label: status,
            badge: 'status-default',
        }
    )

}


/*
|--------------------------------------------------------------------------
| FORMAT DATE
|--------------------------------------------------------------------------
*/

function formatTanggal(
    value,
    withTime = false
) {

    if (!value) {
        return ''
    }


    return new Date(value).toLocaleString(
        'id-ID',
        {
            day: 'numeric',
            month: 'long',
            year: 'numeric',

            ...(withTime
                ? {
                    hour: '2-digit',
                    minute: '2-digit',
                }
                : {}
            ),
        }
    )

}


/*
|--------------------------------------------------------------------------
| REFRESH CAPTCHA
|--------------------------------------------------------------------------
*/

function refreshCaptcha() {

    const chars =
        'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'


    captchaCode.value =
        Array
            .from(
                {
                    length: 5,
                },
                () =>
                    chars[
                        Math.floor(
                            Math.random() *
                            chars.length
                        )
                    ]
            )
            .join('')

}


/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

async function handleSearch() {

    message.value = ''

    result.value = null


    /*
    |--------------------------------------------------------------------------
    | VALIDATE TICKET
    |--------------------------------------------------------------------------
    */

    if (!ticketNumber.value.trim()) {

        message.value =
            'Nomor tiket wajib diisi.'

        messageType.value =
            'error'

        return

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE CAPTCHA
    |--------------------------------------------------------------------------
    */

    if (
        captchaInput.value
            .trim()
            .toUpperCase()
        !== captchaCode.value
    ) {

        message.value =
            'Kode captcha tidak sesuai.'

        messageType.value =
            'error'


        refreshCaptcha()

        captchaInput.value = ''

        return

    }


    /*
    |--------------------------------------------------------------------------
    | REQUEST
    |--------------------------------------------------------------------------
    */

    loading.value = true


    try {

        const response =
            await axios.get(
                `/api/pengajuan/lacak/${encodeURIComponent(
                    ticketNumber.value.trim()
                )}`
            )


        result.value =
            response.data.data


        message.value = ''

    } catch (error) {

        console.error(
            'Gagal melacak tiket:',
            error
        )


        if (
            error.response?.status === 404
        ) {

            message.value =
                'Nomor tiket tidak ditemukan.'

        } else {

            message.value =
                'Terjadi kesalahan, silakan coba lagi.'

        }


        messageType.value =
            'error'


        refreshCaptcha()

        captchaInput.value = ''

    } finally {

        loading.value = false

    }

}


/*
|--------------------------------------------------------------------------
| ON MOUNTED
|--------------------------------------------------------------------------
*/

onMounted(() => {

    if (route.query.tiket) {

        ticketNumber.value =
            route.query.tiket

    }

})

</script>


<style scoped>

/*
|--------------------------------------------------------------------------
| PAGE
|--------------------------------------------------------------------------
*/

.tracking-page {

    position: relative;

    min-height: 100vh;

    overflow-x: hidden;

    background: #ffffff;

    font-family:
        'Poppins',
        sans-serif;

}


/*
|--------------------------------------------------------------------------
| BACKGROUND AREA
|--------------------------------------------------------------------------
|
| Area ini dibuat sebagai layer terpisah supaya gambar dapat
| dicrop membentuk ellipse.
|
*/

.hero-background {

    position: absolute;

    top: 0;

    left: 0;

    width: 100%;

    height:
        clamp(
            360px,
            38vw,
            405px
        );

    overflow: hidden;

    z-index: 0;

}


/*
|--------------------------------------------------------------------------
| IMAGE
|--------------------------------------------------------------------------
*/

.hero-image {

    position: absolute;

    top: 0;

    left: 50%;

    width:
        max(
            120%,
            1450px
        );

    height:
        clamp(
            390px,
            40vw,
            430px
        );

    transform:
        translateX(-50%);

    background-image:
        url('/public/images/bglayanan.png');

    background-size: cover;

    background-position:
        center center;

    background-repeat: no-repeat;

    /*
    | Bentuk elips
    */

    border-radius:
        0
        0
        50%
        50% /
        0
        0
        18%
        18%;

}


/*
|--------------------------------------------------------------------------
| BLUE/PURPLE OVERLAY
|--------------------------------------------------------------------------
|
| Kalau gambar bg.png di repo memiliki warna yang lebih terang/
| berbeda, overlay ini membantu mendekati screenshot UI UX.
|
*/

.hero-image::after {

    content: '';

    position: absolute;

    inset: 0;

    background:
        linear-gradient(
            110deg,
            rgba(54, 157, 247, 0.66),
            rgba(104, 121, 239, 0.68),
            rgba(139, 100, 224, 0.66)
        );

}


/*
|--------------------------------------------------------------------------
| DOT PATTERN
|--------------------------------------------------------------------------
*/

.dot-pattern {

    position: absolute;

    top:
        clamp(
            70px,
            7vw,
            100px
        );

    right:
        clamp(
            25px,
            5vw,
            70px
        );

    width:
        clamp(
            80px,
            8vw,
            120px
        );

    height:
        clamp(
            100px,
            10vw,
            145px
        );

    opacity: 0.45;

    background-image:
        radial-gradient(
            circle,
            rgba(
                255,
                255,
                255,
                0.8
            )
            1.8px,
            transparent 1.8px
        );

    background-size:
        14px 14px;

}


/*
|--------------------------------------------------------------------------
| CONTENT
|--------------------------------------------------------------------------
*/

.tracking-content {

    position: relative;

    z-index: 2;

    min-height: 100vh;

    padding:
        clamp(
            32px,
            3vw,
            48px
        )
        clamp(
            20px,
            6vw,
            96px
        )
        80px;

}


/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

.tracking-header {

    width: 100%;

}


/*
|--------------------------------------------------------------------------
| BACK LINK
|--------------------------------------------------------------------------
*/

.back-link {

    display: inline-flex;

    align-items: center;

    gap: 10px;

    color: white;

    font-size:
        clamp(
            13px,
            1vw,
            15px
        );

    font-weight: 400;

    text-decoration: none;

    transition:
        transform 0.2s ease;

}


.back-link:hover {

    transform:
        translateX(-3px);

}


.back-link i {

    font-size:
        clamp(
            19px,
            1.5vw,
            22px
        );

}


/*
|--------------------------------------------------------------------------
| TITLE
|--------------------------------------------------------------------------
*/

.title-section {

    margin-top:
        clamp(
            8px,
            1vw,
            14px
        );

    text-align: center;

}


.title-section h1 {

    margin: 0;

    color: white;

    font-size:
        clamp(
            40px,
            4.6vw,
            64px
        );

    font-weight: 700;

    line-height: 1.1;

    letter-spacing:
        -0.045em;

}


.title-section p {

    margin:
        clamp(
            15px,
            1.4vw,
            22px
        )
        auto
        0;

    max-width: 720px;

    color: white;

    font-size:
        clamp(
            13px,
            1.25vw,
            17px
        );

    line-height: 1.5;

}


/*
|--------------------------------------------------------------------------
| CARD WRAPPER
|--------------------------------------------------------------------------
*/

.tracking-card-wrapper {

    position: relative;

    width:
        min(
            100%,
            735px
        );

    margin:
        clamp(
            66px,
            6vw,
            78px
        )
        auto
        0;

}


/*
|--------------------------------------------------------------------------
| TRACKING CARD
|--------------------------------------------------------------------------
*/

.tracking-card {

    position: relative;

    width: 100%;

    padding:
        clamp(
            48px,
            4vw,
            60px
        )
        clamp(
            28px,
            4vw,
            52px
        )
        clamp(
            32px,
            3vw,
            42px
        );

    background: rgba(
        255,
        255,
        255,
        0.98
    );

    border:
        1px solid #B7B7B7;

    border-radius:
        clamp(
            20px,
            2vw,
            28px
        );

    box-shadow:
        0
        12px
        32px
        rgba(
            0,
            0,
            0,
            0.16
        );

}


/*
|--------------------------------------------------------------------------
| ICON BADGE
|--------------------------------------------------------------------------
*/

.tracking-icon {

    position: absolute;

    top:
        -48px;

    left: 50%;

    width:
        clamp(
            82px,
            6vw,
            92px
        );

    height:
        clamp(
            82px,
            6vw,
            92px
        );

    transform:
        translateX(-50%);

    display: flex;

    align-items: center;

    justify-content: center;

    background: white;

    border:
        5px solid
        rgba(
            35,
            94,
            199,
            0.38
        );

    border-radius: 50%;

    box-shadow:
        0
        3px
        7px
        rgba(
            0,
            0,
            0,
            0.08
        );

    z-index: 5;

}


/*
|--------------------------------------------------------------------------
| ICON INNER
|--------------------------------------------------------------------------
*/

.icon-inner {

    display: flex;

    align-items: center;

    justify-content: center;

    width:
        clamp(
            52px,
            4vw,
            60px
        );

    height:
        clamp(
            52px,
            4vw,
            60px
        );

    border-radius: 50%;

    background:
        #EAF3FF;

}


.icon-inner i {

    color:
        #0878EA;

    font-size:
        clamp(
            28px,
            2.4vw,
            37px
        );

}


/*
|--------------------------------------------------------------------------
| FORM
|--------------------------------------------------------------------------
*/

.tracking-form {

    width: 100%;

}


/*
|--------------------------------------------------------------------------
| FORM SECTION
|--------------------------------------------------------------------------
*/

.form-section {

    width: 100%;

}


/*
|--------------------------------------------------------------------------
| SECTION TITLE
|--------------------------------------------------------------------------
*/

.section-title {

    display: flex;

    align-items: center;

    gap: 12px;

}


.section-title h2 {

    margin: 0;

    color: #151515;

    font-size:
        clamp(
            15px,
            1.15vw,
            18px
        );

    font-weight: 500;

}


/*
|--------------------------------------------------------------------------
| SECTION ICON
|--------------------------------------------------------------------------
*/

.section-icon {

    display: flex;

    align-items: center;

    justify-content: center;

    width:
        clamp(
            30px,
            2.5vw,
            34px
        );

    height:
        clamp(
            30px,
            2.5vw,
            34px
        );

    flex-shrink: 0;

    border-radius: 50%;

    background:
        #168CF0;

    box-shadow:
        inset
        0
        0
        0
        1px
        rgba(
            255,
            255,
            255,
            0.4
        );

}


.section-icon i {

    color: white;

    font-size:
        clamp(
            14px,
            1.1vw,
            16px
        );

}


/*
|--------------------------------------------------------------------------
| INPUT
|--------------------------------------------------------------------------
*/

.text-input {

    display: block;

    width: 100%;

    height:
        clamp(
            43px,
            3.3vw,
            48px
        );

    margin-top: 14px;

    padding:
        0
        16px;

    box-sizing: border-box;

    border:
        1px solid #A7A7A7;

    border-radius:
        clamp(
            11px,
            1vw,
            14px
        );

    outline: none;

    background: white;

    color: #222;

    font-family:
        'Poppins',
        sans-serif;

    font-size:
        clamp(
            11px,
            0.9vw,
            13px
        );

    transition:
        border-color 0.2s ease,
        box-shadow 0.2s ease;

}


.text-input::placeholder {

    color: #A4A7B4;

}


.text-input:focus {

    border-color:
        #1761E8;

    box-shadow:
        0
        0
        0
        3px
        rgba(
            23,
            97,
            232,
            0.08
        );

}


/*
|--------------------------------------------------------------------------
| DIVIDER
|--------------------------------------------------------------------------
*/

.form-divider {

    width: 100%;

    height: 1px;

    margin:
        20px
        0;

    background:
        #D8D8D8;

}


/*
|--------------------------------------------------------------------------
| CAPTCHA
|--------------------------------------------------------------------------
*/

.captcha-row {

    display: flex;

    align-items: center;

    gap: 12px;

    margin-top: 14px;

}


/*
|--------------------------------------------------------------------------
| CAPTCHA DISPLAY
|--------------------------------------------------------------------------
*/

.captcha-display {

    display: flex;

    align-items: center;

    justify-content: center;

    width:
        clamp(
            168px,
            15vw,
            220px
        );

    height:
        clamp(
            65px,
            5.5vw,
            72px
        );

    background:
        #ffffff;

    border:
        1px solid #A7A7A7;

    border-radius:
        clamp(
            10px,
            1vw,
            14px
        );

}


.captcha-display span {

    color: #000;

    font-family:
        monospace;

    font-size:
        clamp(
            24px,
            2.2vw,
            32px
        );

    font-weight: 900;

    letter-spacing:
        1px;

}


/*
|--------------------------------------------------------------------------
| REFRESH
|--------------------------------------------------------------------------
*/

.captcha-refresh {

    display: flex;

    align-items: center;

    justify-content: center;

    width:
        clamp(
            39px,
            3vw,
            44px
        );

    height:
        clamp(
            39px,
            3vw,
            44px
        );

    padding: 0;

    background: white;

    border:
        1px solid #A7A7A7;

    border-radius:
        11px;

    color:
        #0A8AE8;

    cursor: pointer;

    transition:
        background 0.2s ease,
        transform 0.2s ease;

}


.captcha-refresh:hover {

    background:
        #F1F7FF;

    transform:
        rotate(10deg);

}


.captcha-refresh i {

    font-size:
        20px;

}


/*
|--------------------------------------------------------------------------
| CAPTCHA INPUT
|--------------------------------------------------------------------------
*/

.captcha-input {

    margin-top: 14px;

}


/*
|--------------------------------------------------------------------------
| SEARCH BUTTON
|--------------------------------------------------------------------------
*/

.search-ticket-button {

    width: 100%;

    height:
        clamp(
            45px,
            3.4vw,
            50px
        );

    margin-top: 22px;

    border: 0;

    border-radius:
        clamp(
            10px,
            0.9vw,
            12px
        );

    background:
        #1755E8;

    color: white;

    font-family:
        'Poppins',
        sans-serif;

    font-size:
        clamp(
            17px,
            1.4vw,
            20px
        );

    font-weight: 500;

    cursor: pointer;

    transition:
        background 0.2s ease,
        transform 0.2s ease;

}


.search-ticket-button:hover:not(:disabled) {

    background:
        #0E42C4;

}


.search-ticket-button:active:not(:disabled) {

    transform:
        scale(0.995);

}


.search-ticket-button:disabled {

    opacity: 0.6;

    cursor: not-allowed;

}


/*
|--------------------------------------------------------------------------
| MESSAGE
|--------------------------------------------------------------------------
*/

.message-box {

    margin-bottom: 20px;

    padding:
        13px
        16px;

    border-radius:
        12px;

    font-size: 13px;

}


.message-error {

    border:
        1px solid #F3B5B5;

    background:
        #FFF4F4;

    color:
        #B42318;

}


.message-success {

    border:
        1px solid #B5D5F3;

    background:
        #F1F8FF;

    color:
        #1554A8;

}


/*
|--------------------------------------------------------------------------
| RESULT
|--------------------------------------------------------------------------
*/

.tracking-result {

    margin-top: 30px;

    padding-top: 24px;

    border-top:
        1px solid #E2E2E2;

}


.result-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

}


.result-header h3 {

    margin: 0;

    color: #222;

    font-size: 17px;

    font-weight: 600;

}


.result-date {

    margin:
        7px
        0
        0;

    color: #777;

    font-size: 12px;

}


/*
|--------------------------------------------------------------------------
| STATUS
|--------------------------------------------------------------------------
*/

.status-badge {

    padding:
        5px
        11px;

    border-radius: 999px;

    font-size: 11px;

    font-weight: 600;

}


.status-waiting {

    background:
        #FFF7E6;

    color:
        #A86100;

}


.status-processing {

    background:
        #EAF3FF;

    color:
        #1755E8;

}


.status-repair {

    background:
        #FFF0E5;

    color:
        #B85A00;

}


.status-rejected {

    background:
        #FFF0F0;

    color:
        #C62828;

}


.status-completed {

    background:
        #EAF8EF;

    color:
        #16803C;

}


.status-default {

    background:
        #F1F1F1;

    color:
        #555;

}


/*
|--------------------------------------------------------------------------
| HISTORY
|--------------------------------------------------------------------------
*/

.history-list {

    margin-top: 22px;

}


.history-item {

    display: flex;

    gap: 13px;

}


.history-line {

    display: flex;

    flex-direction: column;

    align-items: center;

    width: 14px;

    flex-shrink: 0;

}


.history-dot {

    width: 10px;

    height: 10px;

    margin-top: 5px;

    border-radius: 50%;

    background:
        #0A66C2;

}


.history-connector {

    width: 1px;

    flex: 1;

    margin-top: 4px;

    background:
        #D9D9D9;

}


.history-content {

    padding-bottom: 18px;

}


.history-title {

    margin: 0;

    color: #222;

    font-size: 13px;

    font-weight: 600;

}


.history-description {

    margin:
        3px
        0
        0;

    color: #666;

    font-size: 12px;

}


.history-date {

    margin:
        4px
        0
        0;

    color: #999;

    font-size: 10px;

}


/*
|--------------------------------------------------------------------------
| TABLET
|--------------------------------------------------------------------------
*/

@media (max-width: 900px) {

    .hero-image {

        width: 145%;

    }


    .tracking-card-wrapper {

        width:
            min(
                100%,
                680px
            );

    }

}


/*
|--------------------------------------------------------------------------
| MOBILE
|--------------------------------------------------------------------------
*/

@media (max-width: 640px) {

    .hero-background {

        height: 350px;

    }


    .hero-image {

        width: 175%;

        height: 370px;

        background-position:
            center center;

        border-radius:
            0
            0
            50%
            50% /
            0
            0
            12%
            12%;

    }


    .dot-pattern {

        top: 75px;

        right: 12px;

        width: 70px;

        height: 90px;

        background-size:
            11px 11px;

    }


    .tracking-content {

        padding:
            28px
            16px
            60px;

    }


    .back-link {

        font-size: 12px;

    }


    .back-link i {

        font-size: 18px;

    }


    .title-section {

        margin-top: 26px;

    }


    .title-section h1 {

        font-size: 38px;

        line-height: 1.08;

    }


    .title-section p {

        margin-top: 12px;

        font-size: 12px;

    }


    .desktop-only {

        display: none;

    }


    .tracking-card-wrapper {

        width: 100%;

        margin-top: 65px;

    }


    .tracking-card {

        padding:
            52px
            20px
            28px;

        border-radius: 22px;

    }


    .tracking-icon {

        top: -42px;

        width: 78px;

        height: 78px;

    }


    .icon-inner {

        width: 50px;

        height: 50px;

    }


    .icon-inner i {

        font-size: 28px;

    }


    .section-title h2 {

        font-size: 14px;

    }


    .text-input {

        font-size: 11px;

    }


    .captcha-display {

        width: 165px;

    }


    .captcha-display span {

        font-size: 23px;

    }


    .search-ticket-button {

        font-size: 17px;

    }


    .result-header {

        align-items: flex-start;

        flex-direction: column;

    }

}


/*
|--------------------------------------------------------------------------
| SMALL MOBILE
|--------------------------------------------------------------------------
*/

@media (max-width: 380px) {

    .title-section h1 {

        font-size: 32px;

    }


    .captcha-row {

        gap: 8px;

    }


    .captcha-display {

        width: 145px;

    }

}

</style>