<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import BaseButton from '../../common/InputFields/BaseButton.vue'
import { useAuthStore } from '@/store/auth.ts'

const emit = defineEmits<{
  (e: 'close'): void
}>()

const props = defineProps<{
  user: any
  userId: number
  userName: string
  userEmail: string
  userPhoto?: string | null
  userAvatarInfo?: { initials: string; bgColor: string }
  modalTitle: string
  modalDescription: string
  buttonText: string
  redirectPath: string
}>()

const router = useRouter()
const authStore = useAuthStore()
const isLoading = ref(false)

// Dynamic content passed from parent
const modalTitle = computed(() => props.modalTitle);
const modalDescription = computed(() => props.modalDescription);
const buttonText = computed(() => props.buttonText);

// User display data passed from parent
const userName = computed(() => props.userName);
const userEmail = computed(() => props.userEmail);
const userPhoto = computed(() => props.userPhoto);
const userAvatarInfo = computed(() => props.userAvatarInfo);

// Get user ID from props
const getUserId = () => {
  return props.userId;
};

const loginAsUser = async () => {
  const userId = getUserId();
  
  if (!userId) {
    console.error('User ID not found');
    return;
  }

  try {
    isLoading.value = true;
    await authStore.loginAs(userId);
    
    // Use the redirect path provided by parent
    await router.push(props.redirectPath);
    
    emit('close');
  } catch (error) {
    console.error('Login as user failed:', error);
  } finally {
    isLoading.value = false;
  }
};
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
      <div class="flex items-center justify-between border-b p-4">
        <h2 class="text-h3 font-semibold text-primary">{{ modalTitle }}</h2>
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
                v-if="!userPhoto && userAvatarInfo"
                :class="[
                  'w-full h-full rounded-full flex items-center justify-center text-white font-semibold text-h5',
                  userAvatarInfo.bgColor
                ]"
            >
              {{ userAvatarInfo.initials }}
            </div>
            <!-- Show actual image for real photos -->
            <img
                v-else-if="userPhoto"
                :src="userPhoto"
                alt="User avatar"
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
            <h3 class="font-medium text-primary">{{ userName }}</h3>
            <p class="text-h5 text-gray-600">{{ userEmail }}</p>
          </div>
        </div>

        <p class="text-gray-600 text-h5 mb-6">
          {{ modalDescription }}
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
            {{ buttonText }}
          </BaseButton>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>