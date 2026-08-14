<template>
    <section
        class="w-full h-full flex items-center lg:items-stretch justify-center"
    >
        <div v-if="authStore.isLoading"></div>
        <LoginCard v-else-if="!authStore.user" />
        <UserWelcomeCard v-else />
    </section>
</template>

<script setup>
import LoginCard from '../LoginCard.vue'
import UserWelcomeCard from '../UserWelcomeCard.vue'
import { useAuthStore } from '../../stores/auth'

const authStore = useAuthStore()
</script>