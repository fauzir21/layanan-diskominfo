<template>
    <MainLayout>
        <router-view />
    </MainLayout>
</template>

<script setup>
import { onMounted } from 'vue'
import MainLayout from './layouts/MainLayout.vue'
import { useAuthStore } from './stores/auth'

const authStore = useAuthStore()

onMounted(() => {
    authStore.fetchUser()
})
</script>