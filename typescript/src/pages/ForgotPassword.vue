<template>
  <ForgotPasswordRequest
      title="Forgot your password"
      subtitle="Don’t worry! Enter your email and we’ll email instructions on how to reset it."
      :backgroundImage="background"
      :step="step"
  >
    <template #form>
      <div class="flex flex-col gap-8">
        <BaseInput label="Email" type="email" placeholder="Enter your email" v-model="email" />
        <div class="flex flex-col gap-2">
          <BaseButton :loading-button="true" class="w-full text-primary text-h5" :isPrimary="true" @click="reset">Reset Password</BaseButton>
          <BaseButton class="w-full text-primary text-h5" :isPrimary="true" @click="login">Back to login</BaseButton>
        </div>
      </div>
    </template>
  </ForgotPasswordRequest>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import {useRoute, useRouter} from 'vue-router'
import BaseInput from '../components/common/InputFields/BaseInput.vue'
import BaseButton from '../components/common/InputFields/BaseButton.vue'
import ForgotPasswordRequest from "../components/common/ForgotPasswordRequest.vue";
import {validateEmail} from "@/utils/helpers.ts";
import {useAuthStore} from "@/store/auth.ts";

const email = ref('')
const authStore = useAuthStore()

const background = new URL('../assets/icons/background.svg', import.meta.url).href
const step = ref(0)
const router = useRouter()
const route = useRoute()

const reset = async () => {
  if (validateEmail(email.value)) {
    const response = await authStore.forgotPassword(email.value);
    if (response.status === 200) {
      step.value = 1
    }
  }
}
const login = () => { router.push('/' + route.query.role + '/login') }
</script>
