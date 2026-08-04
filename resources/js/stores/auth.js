import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from '../lib/axios'

export const useAuthStore = defineStore('auth', () => {
    const user = ref(null)
    const isLoading = ref(true)

    async function fetchUser() {
        isLoading.value = true

        try {
            const response = await axios.get('/api/me')
            user.value = response.data.user
        } catch (error) {
            user.value = null
        } finally {
            isLoading.value = false
        }
    }

    function setUser(userData) {
        user.value = userData
    }

    async function logout() {
        await axios.post('/api/logout')
        user.value = null
    }

    return { user, isLoading, fetchUser, setUser, logout }
})