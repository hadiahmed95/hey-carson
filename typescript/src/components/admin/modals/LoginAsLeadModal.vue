<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/store/auth.ts'
import { getS3URL, generateInitialsAvatar } from '@/utils/helpers.ts'
import BaseButton from '../../common/InputFields/BaseButton.vue'

const emit = defineEmits<{
  (e: 'close'): void
}>()

const props = defineProps<{
  user: any
}>()

const router = useRouter()
const authStore = useAuthStore()
const isLoading = ref(false)

// Computed properties for user image display using helper
const userName = computed(() => `${props.user.first_name} ${props.user.last_name}`);
const hasRealPhoto = computed(() => props.user.photo && props.user.photo !== null && props.user.photo !== '');
const displayUrl = computed(() => hasRealPhoto.value ? getS3URL(props.user.photo) : null);
const avatarInfo = computed(() => hasRealPhoto.value ? undefined : generateInitialsAvatar(userName.value));

const loginAsUser = async () => {
  isLoading.value = true
  try {
    // Login as user using their user ID
    await authStore.loginAs(props.user.id)
    // Redirect to client dashboard
    await router.push('/client/dashboard')
    emit('close')
  } catch (error) {
    console.error('Login as user failed:', error)
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
      <div class="flex items-center justify-between border-b p-4">
        <h2 class="text-h3 font-semibold text-primary">Login As Lead</h2>
        <button @click="emit('close')" class="text-gray-500 hover:text-gray-700 text-xl leading-none">
          ×
        </button>
      </div>

      <div class="p-6">
        <div class="flex items-center space-x-4 mb-6">
          <!-- Profile Image or Initials Avatar using helper -->
          <div class="w-12 h-12 rounded-full overflow-hidden">
            <!-- Show actual image for real photos -->
            <img
                v-if="displayUrl"
                :src="displayUrl"
                alt="User avatar"
                class="w-full h-full rounded-full object-cover"
            />
            <!-- Show initials avatar if no real photo -->
            <div
                v-else-if="avatarInfo"
                :class="[
                  'w-full h-full rounded-full flex items-center justify-center text-white font-semibold text-h5',
                  avatarInfo.bgColor
                ]"
            >
              {{ avatarInfo.initials }}
            </div>
            <!-- Fallback -->
            <div
                v-else
                class="w-full h-full rounded-full flex items-center justify-center text-white font-semibold text-h5 bg-coolGray"
            >
              NA
            </div>
          </div>
          <div>
            <h3 class="font-medium text-primary">{{ userName }}</h3>
            <p class="text-h5 text-gray-600">{{ user.email }}</p>
          </div>
        </div>

        <p class="text-gray-600 text-h5 mb-6">
          You are about to login as this lead. You will be redirected to their dashboard where you can view and manage their account.
        </p>

        <div class="flex gap-3 justify-end">
          <BaseButton
              variant="secondary"
              size="medium"
              @click="emit('close')"
              :disabled="isLoading"
              class="px-4 py-2"
          >
            Cancel
          </BaseButton>
          <BaseButton
              variant="primary"
              size="medium"
              @click="loginAsUser"
              :loading="isLoading"
              class="px-4 py-2"
          >
            Login As Lead
          </BaseButton>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>