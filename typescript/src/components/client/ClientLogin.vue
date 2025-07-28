<template>
  <LoginPage
      title="Client Login"
      subtitle="Login to your shopexpert account."
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
        <h5 class="font-archivo text-center text-primary">Don’t have an shopexperts account?</h5>
        <BaseButton class="w-full text-paragraph font-semibold" @click="signUp">Sign up as Client</BaseButton>
      </div>
    </template>
  </LoginPage>
</template>

<script setup lang="ts">
import {onMounted, ref} from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/store/auth.ts'
import LoginPage from "@/pages/LoginPage.vue"
import BaseInput from '@/components/common/InputFields/BaseInput.vue'
import BaseButton from '@/components/common/InputFields/BaseButton.vue'
import PasswordInput from '@/components/common/InputFields/PasswordInput.vue'

const router = useRouter()
const authStore = useAuthStore()

const email = ref('')
const password = ref('')

const background = new URL('@/assets/icons/background.svg', import.meta.url).href

onMounted(async () => {
  if (authStore.token && authStore.user) {
    await router.push('/client/dashboard')
  }
})

const login = async () => {
  await authStore.login(email.value, password.value, 'client')
  await router.push('/client/dashboard')
}

const signUp = () => { router.push('/client/signup') }
const forgot = () => { router.push('/forgot-password?role=client') }
</script>
