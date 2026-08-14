import { ref } from 'vue'
import axios from '../lib/axios'

export function useDashboard() {
    const data = ref(null)
    const loading = ref(false)
    const error = ref(null)

    async function fetchDashboard() {
        loading.value = true
        error.value = null

        try {
            const response = await axios.get('/api/dashboard')
            data.value = response.data
        } catch (err) {
            console.error('Dashboard error:', err)

            error.value =
                err.response?.data?.message ||
                'Gagal mengambil data dashboard.'
        } finally {
            loading.value = false
        }
    }

    return {
        data,
        loading,
        error,
        fetchDashboard,
    }
}