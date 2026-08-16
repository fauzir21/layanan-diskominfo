<template>

    <article
        :class="[
            'w-full rounded-[clamp(16px,1.8vw,24px)] bg-white shadow-md',
            'transition duration-200 hover:-translate-y-0.5 hover:shadow-xl',
            'flex h-full flex-col',
            compact
                ? 'p-[clamp(14px,1.4vw,20px)]'
                : 'p-7'
        ]"
    >

        <!-- Icon -->

        <div
            class="flex items-center"
        >

            <i
                class="bi bi-file-earmark-text text-gray-800"
                :class="
                    compact
                        ? 'text-[clamp(16px,1.8vw,22px)]'
                        : 'text-[26px]'
                "
            ></i>

        </div>


        <!-- Title -->

        <h3
            :class="
                compact
                    ? 'mt-[clamp(6px,0.7vw,10px)] text-[clamp(14px,1.25vw,17px)]'
                    : 'mt-3 text-xl'
            "
            class="line-clamp-1 font-semibold leading-tight text-gray-800"
        >
            {{ title }}
        </h3>


        <!-- Description -->

        <p
            :class="
                compact
                    ? 'mt-[clamp(5px,0.7vw,9px)] line-clamp-3 text-[clamp(11px,1vw,13px)]'
                    : 'mt-3 line-clamp-3 text-[15px]'
            "
            class="flex-1 leading-[1.45] text-gray-500"
        >
            {{ description }}
        </p>


        <!-- Button -->

        <div
            :class="
                compact
                    ? 'mt-[clamp(10px,1vw,14px)]'
                    : 'mt-5'
            "
        >

            <router-link
                :to="link"
                :class="
                    compact
                        ? 'px-[clamp(12px,1.5vw,18px)] py-[clamp(6px,0.8vw,9px)] text-[clamp(10px,0.95vw,13px)]'
                        : 'px-5 py-2.5 text-sm'
                "
                class="inline-flex items-center gap-2 rounded-full bg-[#0A66C2] font-semibold text-white transition hover:bg-[#0959aa]"
            >

                {{ buttonText }}

                <i class="bi bi-arrow-right"></i>

            </router-link>

        </div>

    </article>

</template>


<script setup>
defineProps({

    title: {
        type: String,
        default: 'Nama Kategori Layanan',
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

    compact: {
        type: Boolean,
        default: false,
    },

    buttonText: {
        type: String,
        default: 'Lihat Persyaratan',
    },

})
</script>