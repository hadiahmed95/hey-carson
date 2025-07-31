<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import BaseButton from '../../common/InputFields/BaseButton.vue'
import { useAuthStore } from '@/store/auth.ts'

const emit = defineEmits<{
  (e: 'close'): void
}>()

const props = defineProps<{
  expert: any
}>()

const router = useRouter()
const authStore = useAuthStore()
const isLoading = ref(false)

const loginAsExpert = async () => {
  isLoading.value = true
  try {
    // Login as expert using their credentials
    await authStore.loginAs(props.expert.email, 'expert')
    // Redirect to expert dashboard
    await router.push('/expert/dashboard')
    emit('close')
  } catch (error) {
    console.error('Login as expert failed:', error)
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
      <div class="flex items-center justify-between border-b p-4">
        <h2 class="text-lg font-semibold text-primary">Login As Expert</h2>
        <button @click="emit('close')" class="text-gray-500 hover:text-gray-700 text-xl leading-none">
          ×
        </button>
      </div>

      <div class="p-6">
        <div class="flex items-center space-x-4 mb-6">
          <img 
            :src="expert.photo ? getS3URL(expert.photo) : 'https://randomuser.me/api/portraits/men/32.jpg'" 
            alt="Expert avatar" 
            class="w-12 h-12 rounded-full object-cover"
          />
          <div>
            <h3 class="font-medium text-primary">{{ expert.first_name }} {{ expert.last_name }}</h3>
            <p class="text-sm text-gray-600">{{ expert.email }}</p>
          </div>
        </div>

        <p class="text-gray-600 text-sm mb-6">
          You are about to login as this expert. You will be redirected to their dashboard where you can view and manage their account.
        </p>

        <div class="flex justify-end space-x-3">
          <button 
            @click="emit('close')"
            class="px-4 py-2 text-sm border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
          >
            Cancel
          </button>
          <BaseButton 
            @click="loginAsExpert"
            :loading-button="isLoading"
            :isPrimary="true"
            class="px-4 py-2 text-sm"
          >
            Login As Expert
          </BaseButton>
        </div>
      </div>
    </div>
  </div>
</template>
