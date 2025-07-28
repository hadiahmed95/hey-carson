<script setup lang="ts">
import {ref} from "vue";
import ApiService from "@/services/api.service.ts";
import FormStatusMessage from "@/components/common/FormStatusMessage.vue";

const props = defineProps<{
  title: string
  tagline: string
  email?: string
  isChangePassword?: boolean
}>()
const status = ref<'success' | 'error' | ''>('')
const message = ref('')
const loading = ref(false)

const sendChangePasswordEmail = async () => {
  if (!props.email) {
    status.value = 'error'
    message.value = 'No email provided.'
    return
  }

  loading.value = true
  try {
    const res = await ApiService.post('/forgot-password', { email: props.email })
    status.value = 'success'
    message.value = res.data.status || 'Password reset email sent.'
  } catch (error: any) {
    status.value = 'error'
    message.value = error?.response?.data?.message || 'Failed to send password reset email.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="mx-auto p-6 bg-white rounded-md border border-gray-200 w-[45rem]">
    <button @click="isChangePassword ? sendChangePasswordEmail() : ''" class="inline-block font-semibold text-h4 px-3 py-2 border-2 border-gray-200 rounded-md mb-4">{{ title }}</button>
    <FormStatusMessage
        class="mb-4"
        :trigger="Date.now()"
        :status="status"
        :message="message"
        @updateStatus="() => { status = ''; message = '' }"
    />
    <p class="text-h4 text-greyCool">{{ tagline }}</p>
  </div>
</template>