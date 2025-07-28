<template>
  <ResetPassword
      title="Reset Account Password"
      :subtitle="`for the account ${email}`"
      :backgroundImage="background"
      :step="step"
  >
    <template #form>
      <div class="flex flex-col gap-8">
        <!-- New Password -->
        <div>
          <label for="new-password" class="text-h5 font-sm text-primary">New Password</label>
          <PasswordInput id="new-password" v-model="newPassword" />
        </div>

        <!-- Confirm New Password -->
        <div>
          <label for="confirm-password" class="text-h5 font-sm text-primary">Confirm New Password</label>
          <PasswordInput id="confirm-password" v-model="confirmNewPassword" />
        </div>

        <!-- Email Field -->
        <BaseInput
            label="Email"
            type="email"
            placeholder="Enter your email"
            v-model="email"
            disabled
        />

        <!-- Submit Button -->
        <BaseButton
            :loading-button="true"
            class="w-full text-primary text-h5"
            :isPrimary="true"
            @click="reset"
        >
          Reset Password
        </BaseButton>
      </div>
    </template>
  </ResetPassword>
</template>

<script setup lang="ts">
import {onMounted, ref} from 'vue'
import {useRoute, useRouter} from "vue-router";
import BaseInput from '../components/common/InputFields/BaseInput.vue'
import BaseButton from '../components/common/InputFields/BaseButton.vue'
import PasswordInput from '../components/common/InputFields/PasswordInput.vue'
import ResetPassword from '../components/common/ResetPassword.vue'
import {useAuthStore} from "@/store/auth.ts";

onMounted(() => {
  email.value = route.query.email as string;
})

const authStore = useAuthStore()
const route = useRoute()
const router = useRouter()

const email = ref('')
const newPassword = ref('')
const confirmNewPassword = ref('')
const step = ref(0)

const background = new URL('../assets/icons/background.svg', import.meta.url).href

const reset = async () => {

  const response = await authStore.resetPassword({
    email: route.query.email as string,
    token: route.query.token as string,
    password: newPassword.value,
    password_confirmation: confirmNewPassword.value
  })

  if (response.status === 200) {
    await router.push('/' + route.query.type + '/login')
  }
}
</script>
