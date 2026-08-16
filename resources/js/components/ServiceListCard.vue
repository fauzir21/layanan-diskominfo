<template>

    <article class="service-card">

        <!-- Icon -->

        <div class="service-icon">

            <i class="bi bi-file-earmark-text"></i>

        </div>


        <!-- Title -->

        <h2 class="service-title">
            {{ title }}
        </h2>


        <!-- Description -->

        <p class="service-description">
            {{ description }}
        </p>


        <!-- Button -->

        <div class="service-action">

            <router-link
                :to="link"
                class="service-button"
            >

                <span>
                    {{ buttonText }}
                </span>

                <i class="bi bi-arrow-right"></i>

            </router-link>

        </div>

    </article>

</template>


<script setup>

defineProps({

    title: {
        type: String,
        default: 'Nama Layanan',
    },

    description: {
        type: String,
        default:
            'Deskripsi layanan.',
    },

    link: {
        type: String,
        default: '/',
    },

    buttonText: {
        type: String,
        default: 'Detail Layanan',
    },

})

</script>


<style scoped>

.service-card {

    display: flex;

    flex-direction: column;

    width: 100%;

    min-height:
        clamp(
            205px,
            16vw,
            230px
        );

    padding:
        clamp(20px, 2vw, 24px)
        clamp(20px, 2.6vw, 37px);

    background: rgba(
        255,
        255,
        255,
        0.94
    );

    border:
        1.3px solid #151515;

    border-radius:
        clamp(17px, 1.8vw, 22px);

    box-shadow:
        0 2px 3px
        rgba(
            0,
            0,
            0,
            0.03
        );

    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease;

}


.service-card:hover {

    transform: translateY(-3px);

    box-shadow:
        0 10px 25px
        rgba(
            0,
            0,
            0,
            0.08
        );

}


/*
|--------------------------------------------------------------------------
| ICON
|--------------------------------------------------------------------------
*/

.service-icon {

    display: flex;

    align-items: center;

    height: 25px;

    color: #151515;

}


.service-icon i {

    font-size:
        clamp(
            20px,
            1.7vw,
            24px
        );

}


/*
|--------------------------------------------------------------------------
| TITLE
|--------------------------------------------------------------------------
*/

.service-title {

    margin:
        clamp(10px, 1vw, 13px)
        0
        0;

    color: #151515;

    font-family:
        'Poppins',
        sans-serif;

    font-size:
        clamp(
            17px,
            1.45vw,
            20px
        );

    font-weight: 500;

    line-height: 1.3;

}


/*
|--------------------------------------------------------------------------
| DESCRIPTION
|--------------------------------------------------------------------------
*/

.service-description {

    display: -webkit-box;

    margin:
        clamp(8px, 0.9vw, 11px)
        0
        0;

    overflow: hidden;

    color: #777B8E;

    font-family:
        'Poppins',
        sans-serif;

    font-size:
        clamp(
            12px,
            1vw,
            14px
        );

    font-weight: 400;

    line-height: 1.65;

    -webkit-box-orient: vertical;

    -webkit-line-clamp: 3;

}


/*
|--------------------------------------------------------------------------
| ACTION
|--------------------------------------------------------------------------
*/

.service-action {

    display: flex;

    justify-content: flex-end;

    margin-top: auto;

    padding-top: 12px;

}


/*
|--------------------------------------------------------------------------
| BUTTON
|--------------------------------------------------------------------------
*/

.service-button {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 8px;

    min-width:
        clamp(
            120px,
            10vw,
            136px
        );

    height:
        clamp(
            27px,
            2.3vw,
            30px
        );

    padding:
        0
        13px;

    background: #1554F0;

    border-radius: 999px;

    color: white;

    font-family:
        'Poppins',
        sans-serif;

    font-size:
        clamp(
            9px,
            0.75vw,
            11px
        );

    font-weight: 400;

    text-decoration: none;

    transition:
        background 0.2s ease,
        transform 0.2s ease;

}


.service-button:hover {

    background: #0d43c7;

    transform: translateX(2px);

}


.service-button i {

    font-size:
        clamp(
            12px,
            1vw,
            14px
        );

}

</style>