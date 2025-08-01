<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import BaseButton from '../../common/InputFields/BaseButton.vue'
import { useAuthStore } from '@/store/auth.ts'
import { getS3URL } from '@/utils/helpers.ts'

const emit = defineEmits<{
  (e: 'close'): void
}>()

const props = defineProps<{
  expert: any
}>()

const router = useRouter()
const authStore = useAuthStore()
const isLoading = ref(false)

// Helper function to generate initials avatar (same as Listings.vue)
const generateInitialsAvatar = (name: string): { initials: string; bgColor: string } => {
  if (!name) return { initials: 'NA', bgColor: 'bg-gray-400' };
  
  const words = name.trim().split(' ');
  const initials = words.slice(0, 2).map(word => word.charAt(0).toUpperCase()).join('');
  
  const colors = [
    'bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-pink-500', 
    'bg-indigo-500', 'bg-yellow-500', 'bg-red-500', 'bg-teal-500',
    'bg-orange-500', 'bg-cyan-500', 'bg-lime-500', 'bg-amber-500'
  ];
  
  const charSum = initials.split('').reduce((sum, char) => sum + char.charCodeAt(0), 0);
  const colorIndex = charSum % colors.length;
  
  return {
    initials: initials || 'NA',
    bgColor: colors[colorIndex] || 'bg-gray-400'
  };
};

// Computed properties for expert image display (same pattern as Listings.vue)
const expertName = computed(() => props.expert.display_name || `${props.expert.first_name} ${props.expert.last_name}`);
const hasRealPhoto = computed(() => props.expert.photo && props.expert.photo !== null && props.expert.photo !== '');
const displayUrl = computed(() => hasRealPhoto.value ? getS3URL(props.expert.photo) : 'https://randomuser.me/api/portraits/men/32.jpg');
const avatarInfo = computed(() => hasRealPhoto.value ? undefined : generateInitialsAvatar(expertName.value));

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
          <!-- Profile Image or Initials Avatar (same pattern as Listings.vue) -->
          <div class="w-12 h-12 rounded-full overflow-hidden">
            <!-- Show initials avatar only if it's the default placeholder AND has avatarInfo -->
            <div
                v-if="displayUrl === 'https://randomuser.me/api/portraits/men/32.jpg' && avatarInfo"
                :class="[
                  'w-full h-full rounded-full flex items-center justify-center text-white font-semibold text-sm',
                  avatarInfo.bgColor
                ]"
            >
              {{ avatarInfo.initials }}
            </div>
            <!-- Show actual image for real photos -->
            <img
                v-else-if="displayUrl && displayUrl !== 'https://randomuser.me/api/portraits/men/32.jpg'"
                :src="displayUrl"
                alt="Expert avatar"
                class="w-full h-full rounded-full object-cover"
            />
            <!-- Fallback to default placeholder if no photo and no avatarInfo -->
            <img
                v-else
                src="https://randomuser.me/api/portraits/men/32.jpg"
                alt="Expert avatar"
                class="w-full h-full rounded-full object-cover"
            />
          </div>
          <div>
            <h3 class="font-medium text-primary">{{ expertName }}</h3>
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
