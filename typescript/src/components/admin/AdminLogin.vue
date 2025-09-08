<template>
    <LoginPage
        title="Admin Login"
        subtitle="Login to your admin dashboard."
        :backgroundImage="background"
    >
        <template #form>
            <div class="flex flex-col gap-8">
                <BaseInput label="Email" type="email" placeholder="Enter your email" v-model="email" />
                <div class="flex flex-col gap-2">
                    <div class="flex justify-between items-center">
                        <label for="password" class="text-h5 font-sm text-primary">Password</label>
                        <a @click="forgot" class="font-sm text-info text-h5 hover:underline cursor-pointer">Forgot Password</a>
                    </div>
                    <PasswordInput v-model="password" />
                </div>
                <BaseButton :loading-button="true" class="w-full text-primary text-h5" :isPrimary="true" @click="login">Login</BaseButton>
            </div>
        </template>

        <template #footer>
            <div class="flex flex-col gap-2">
                <h5 class="font-archivo text-center text-primary">Administrative Access Only</h5>
                <p class="text-xs text-center text-gray-500">Contact support if you need access to this portal</p>
            </div>
        </template>
    </LoginPage>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/store/auth.ts'
import { redirectToDashboard } from '@/router/index.ts'
import LoginPage from "@/pages/LoginPage.vue"
import BaseInput from '@/components/common/InputFields/BaseInput.vue'
import BaseButton from '@/components/common/InputFields/BaseButton.vue'
import PasswordInput from '@/components/common/InputFields/PasswordInput.vue'

const router = useRouter()
const authStore = useAuthStore()

const email = ref('')
const password = ref('')

const background = new URL('@/assets/icons/background.svg', import.meta.url).href

const login = async () => {
    try {
        const result = await authStore.TempLogin(email.value, password.value)
        
        // Use centralized redirection logic
        redirectToDashboard(result.user, router)
    } catch (error: any) {
        console.error('Login failed:', error)
    }
}

const forgot = () => { 
    router.push('/forgot-password?role=admin') 
}
</script>
