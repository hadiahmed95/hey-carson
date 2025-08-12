<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import BaseButton from '../../common/InputFields/BaseButton.vue'
import { useAuthStore } from '@/store/auth.ts'
import { getS3URL, generateInitialsAvatar } from '@/utils/helpers.ts'

const emit = defineEmits<{
  (e: 'close'): void
}>()

const props = defineProps<{
  expert: any
}>()

const router = useRouter()
const authStore = useAuthStore()
const isLoading = ref(false)

// Computed properties for expert image display
const expertName = computed(() => props.expert.display_name || `${props.expert.first_name} ${props.expert.last_name}`);
const hasRealPhoto = computed(() => props.expert.photo && props.expert.photo !== null && props.expert.photo !== '');
const displayUrl = computed(() => hasRealPhoto.value ? getS3URL(props.expert.photo) : null);
const avatarInfo = computed(() => hasRealPhoto.value ? undefined : generateInitialsAvatar(expertName.value));

const loginAsExpert = async () => {
  isLoading.value = true
  try {
    // Login as expert using their user ID
    await authStore.loginAs(props.expert.id)
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
        <h2 class="text-h3 font-semibold text-primary">Login As Expert</h2>
        <button @click="emit('close')" class="text-gray-500 hover:text-gray-700 text-xl leading-none">
          ×
        </button>
      </div>

      <div class="p-6">
        <div class="flex items-center space-x-4 mb-6">
          <!-- Profile Image or Initials Avatar -->
          <div class="w-12 h-12 rounded-full overflow-hidden">
            <!-- Show initials avatar if no real photo -->
            <div
                v-if="!displayUrl && avatarInfo"
                :class="[
                  'w-full h-full rounded-full flex items-center justify-center text-white font-semibold text-h5',
                  avatarInfo.bgColor
                ]"
            >
              {{ avatarInfo.initials }}
            </div>
            <!-- Show actual image for real photos -->
            <img
                v-else-if="displayUrl"
                :src="displayUrl"
                alt="Expert avatar"
                class="w-full h-full rounded-full object-cover"
            />
            <!-- Fallback initials if neither condition is met -->
            <div
                v-else
                class="w-full h-full rounded-full flex items-center justify-center text-white font-semibold text-h5 bg-coolGray"
            >
              NA
            </div>
          </div>
          <div>
            <h3 class="font-medium text-primary">{{ expertName }}</h3>
            <p class="text-h5 text-gray-600">{{ expert.email }}</p>
          </div>
        </div>

        <p class="text-gray-600 text-h5 mb-6">
          You are about to login as this expert. You will be redirected to their dashboard where you can view and manage their account.
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
              @click="loginAsExpert"
              :loading="isLoading"
              class="px-4 py-2"
          >
            Login As Expert
          </BaseButton>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
