<script setup lang="ts">
import ApiService from "@/services/api.service.ts";
import {useAlertStore} from "@/store/alert.ts";

const alertStore = useAlertStore();
const props = defineProps<{
  title: string
  tagline: string
  email?: string
  isChangePassword?: boolean
}>()

const sendChangePasswordEmail = async () => {
  const res = await ApiService.post('/forgot-password', { email: props?.email })
  alertStore.show(res.data.status, 'success');
}
</script>

<template>
  <div class="p-6 bg-white rounded-md border border-gray-200 w-[45rem]">
    <button @click="isChangePassword ? sendChangePasswordEmail() : ''" class="inline-block font-semibold text-h4 px-3 py-2 border-2 border-gray-200 rounded-md mb-4">{{ title }}</button>
    <p class="text-h4 text-greyCool">{{ tagline }}</p>
  </div>
</template>