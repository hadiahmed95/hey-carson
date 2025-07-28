<script setup lang="ts">
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/store/auth.ts'
import { api } from '@/services/api.service'

const router = useRouter()
const route = useRoute()

const token = route.query.token as string

if (!token) {
  router.push('/login')
} else {
  localStorage.setItem('CURRENT_TOKEN', token)
  api.defaults.headers.common['Authorization'] = `Bearer ${token}`

  api.get('/auth-check')
      .then(res => {
        const user = res.data.user
        localStorage.setItem('CURRENT_USER', JSON.stringify(user))
        const auth = useAuthStore()
        auth.setCurrentUser(user, token)
        if (user.role_id === 2) {
          router.push('/client/dashboard')
        } else if (user.role_id === 3) {
          router.push('/expert/dashboard')
        }
      })
      .catch(() => {
        localStorage.removeItem('CURRENT_TOKEN')
        localStorage.removeItem('CURRENT_USER')
        router.push('/login')
      })
}
</script>

<template>
  <div class="p-4">Logging you in...</div>
</template>
