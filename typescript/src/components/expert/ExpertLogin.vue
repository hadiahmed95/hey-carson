<template>
  <LoginPage
      title="Expert Login"
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
        <h5 class="font-archivo text-center text-primary">Want to become a Shopexpert?</h5>
        <BaseButton class="w-full text-paragraph font-semibold" @click="signUp">Sign up as expert</BaseButton>
        <BaseButton class="w-full text-paragraph font-semibold" @click="claimAgency">Claim your agency profile</BaseButton>
      </div>
    </template>
  </LoginPage>
</template>

<script setup lang="ts">
import {onMounted, ref} from 'vue'
import { useRouter } from 'vue-router'
import LoginPage from "../../pages/LoginPage.vue"
import BaseInput from '../common/InputFields/BaseInput.vue'
import BaseButton from '../common/InputFields/BaseButton.vue'
import PasswordInput from '../common/InputFields/PasswordInput.vue'
import { useAuthStore } from '@/store/auth.ts'

const email = ref('')
const password = ref('')
const router = useRouter()
const authStore = useAuthStore()

const background = new URL('../../assets/icons/background.svg', import.meta.url).href

onMounted(async () => {
  // Check if user is already logged in
  if (authStore.token && authStore.user) {
    if (authStore.user.role_id === 1) {
      await router.push('/admin/dashboard')
    } else if (authStore.user.role_id === 2) {
      await router.push('/client/dashboard')
    } else if (authStore.user.role_id === 3) {
      await router.push('/expert/dashboard')
    }
  }
})

const login = async () => {
  await authStore.login(email.value, password.value, 'expert')
  
  // Redirect based on role_id after login
  if (authStore.user.role_id === 1) {
    await router.push('/admin/dashboard')
  } else if (authStore.user.role_id === 2) {
    await router.push('/client/dashboard')
  } else if (authStore.user.role_id === 3) {
    await router.push('/expert/dashboard')
  }
}

const signUp = () => { router.push('/expert/signup') }
const claimAgency = () => { /* agency claim logic */ }
const forgot = () => { router.push('/forgot-password?role=expert') }
</script>
