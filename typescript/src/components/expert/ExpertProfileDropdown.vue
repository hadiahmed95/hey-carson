<template>
  <div class="relative" ref="dropdownRef">
    <button
      @click="toggleDropdown"
    >
      <!-- Dynamic Profile Image with Fallback -->
      <div class="w-8 h-8 rounded-full overflow-hidden">
        <!-- Show actual image if photo exists -->
        <img
            v-if="profileImage && profileImage !== null && profileImage !== ''"
            :src="getS3URL(profileImage)"
            alt="Profile"
            class="w-full h-full rounded-full object-cover"
            @error="handleImgError"
        />
        <!-- Show initials avatar if no photo -->
        <div
            v-else
            :class="[
              'w-full h-full rounded-full flex items-center justify-center text-white font-semibold text-xs',
              userAvatarInfo.bgColor
            ]"
        >
          {{ userAvatarInfo.initials }}
        </div>
      </div>
    </button>

    <div
        v-show="isProfileDropdown" class="absolute right-0 mt-2 w-[343px] bg-white border border-grey rounded-md shadow-lg z-50 p-4 flex flex-col items-start transition-opacity duration-200 ease-in-out select-none"
    >
      <!-- Loading State -->
      <div v-if="loading" class="w-full flex justify-center items-center py-8">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
      </div>

      <!-- Content -->
      <div v-else class="w-full">
        <div class="w-full p-2 border border-grey rounded-md">
          <div class="flex items-center justify-between">

            <!-- Profile Section -->
            <div class="flex items-center space-x-4">
              <div class="w-16 h-16 bg-lightApricot rounded-full flex items-center justify-center overflow-hidden">
                <!-- Show actual image if photo exists -->
                <img
                    v-if="expertUser?.photo && expertUser.photo !== null && expertUser.photo !== ''"
                    :src="getS3URL(expertUser.photo)"
                    alt="Profile"
                    class="w-full h-full object-cover"
                    @error="handleImgError"
                />
                <!-- Show initials avatar if no photo -->
                <div
                    v-else
                    :class="[
                      'w-full h-full rounded-full flex items-center justify-center text-white font-semibold text-h3',
                      userAvatarInfo.bgColor
                    ]"
                >
                  {{ userAvatarInfo.initials }}
                </div>
              </div>

              <!-- Profile Info -->
              <div>
                <h3 class="text-h2 font-semibold">{{ displayName }}</h3>
                <button class="text-h4 text-lightBlue font-normal">
                  Preview public profile
                </button>
              </div>
            </div>

            <!-- Arrow Icon -->
            <div class="m-8 cursor-pointer" @click="expandAccountInfo = !expandAccountInfo">
              <RightArrow />
            </div>
          </div>

          <!-- User Information -->
          <div class="space-y-2 mt-4" v-if="expandAccountInfo">
            <div class="flex justify-between items-center">
              <span class="text-h4 font-normal text-greyDark">Account Type:</span>
              <span class="text-h4 font-normal text-primary">{{ accountType }}</span>
            </div>

            <div class="flex justify-between items-center">
              <span class="text-h4 font-normal text-greyDark">Current Plan:</span>
              <span class="text-h4 font-normal text-primary">{{ currentPlan }}</span>
            </div>

            <div class="flex justify-between items-center">
              <span class="text-h4 font-normal text-greyDark">Next Billing Date:</span>
              <span class="text-h4 font-normal text-primary">{{ nextBillingDate }}</span>
            </div>
          </div>

        </div>

        <!-- Balance Section -->
        <div class="p-1 mt-4 w-full">
          <div class="flex justify-between items-center mb-2">
            <h4 class="text-greyDark font-normal">Your Balance:</h4>
            <h2 class="font-bold">${{ currentBalance.toFixed(2) }}</h2>
          </div>

          <button 
            @click="openWithdrawModal"
            class="w-full text-h4 gap-2 bg-white border border-grey font-semibold rounded-sm py-1 px-4 flex items-center justify-center space-x-2 hover:bg-veryLightGray transition-colors"
          >
            <DownloadIcon />
            Withdraw
          </button>

          <hr class="border-t border-gray-300 w-full my-4" />
        </div>

        <!-- Menu Items -->
        <div class="text-primary w-full gap-8">
          <button
              @click="switchToOldDashboard"
              class="flex items-center px-4 py-2 text-left gap-2 w-full hover:bg-gray-100 group"
          >
            <Overview />
            <div class="flex w-full justify-between">
              <span class="text-paragraph font-normal">Switch to Old Dashboard</span>
              <div class="relative w-[46px] h-[24px]">
                <SwitchOff class="absolute inset-0 group-hover:opacity-0 transition-opacity duration-300" />
                <SwitchOn class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
              </div>
            </div>
          </button>
          <router-link to="/expert/settings"  @click="isProfileDropdown = false">
            <button class="w-full bg-white hover:bg-gray-100 px-4 flex items-center gap-2 py-2">
              <SettingsIcon />
              <span class="text-paragraph font-normal">Settings</span>
            </button>
          </router-link>

          <router-link to="/expert/payouts"  @click="isProfileDropdown = false">
            <button class="w-full bg-white hover:bg-gray-100 px-4 flex items-center gap-2 py-2">
              <PayoutIcon />
              <span class="text-paragraph font-normal">Payouts</span>
            </button>
          </router-link>

          <button @click="logout()" class="w-full bg-white hover:bg-gray-100 px-4 flex items-center gap-2 py-2">
            <LogoutIcon />
            <span class="text-paragraph font-normal">Logout</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Withdraw Modal -->
    <WithdrawModal
      v-if="showWithdrawModal"
      @close="closeWithdrawModal"
      @success="onWithdrawSuccess"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/store/auth.ts'
import { onClickOutside } from '@vueuse/core'
import { handleImgError, getS3URL, generateInitialsAvatar } from "@/utils/helpers.ts"
import { useExpertStore } from '@/store/expert'
import { formatDate } from '@/utils/date.ts'
import SettingsIcon from '@/assets/icons/settings.svg'
import PayoutIcon from '@/assets/icons/payout.svg'
import LogoutIcon from '@/assets/icons/logout.svg'
import DownloadIcon from '@/assets/icons/download2.svg'
import RightArrow from '@/assets/icons/right-arrow.svg'
import SwitchOff from '@/assets/icons/switch-off.svg'
import SwitchOn from '@/assets/icons/switch-on.svg'
import Overview from "@/assets/icons/overview.svg";
import WithdrawModal from '@/components/expert/modals/WithdrawModal.vue'

const router = useRouter()
const authStore = useAuthStore()
const expertStore = useExpertStore();

defineProps<{ 
  profileImage: string 
}>()

const isProfileDropdown = ref(false)
const expandAccountInfo = ref(false)
const dropdownRef = ref(null)
const loading = ref(false)
const expertUser = ref<any>(null)
const showWithdrawModal = ref(false)

// Fetch expert settings data
const fetchExpertSettings = async () => {
  try {
    loading.value = true
    const response = await expertStore.fetchExpertProfile('');
    expertUser.value = response.expert;
    
    // Update auth store user with fresh data if needed
    if (response.expert) {
      // Optionally sync some data back to auth store
      authStore.user = { ...authStore.user, ...expertUser.value }
    }
  } catch (error) {
    console.error('Failed to fetch expert settings:', error)
    // Fallback to auth store user if API fails
    expertUser.value = authStore.user
  } finally {
    loading.value = false
  }
}

// Dynamic computed properties
const displayName = computed(() => {
  const user = expertUser.value || authStore.user
  if (!user) return 'User'
  
  const firstName = user.first_name || ''
  const lastName = user.last_name || ''
  
  if (firstName && lastName) {
    // Show first name and last initial
    return `${firstName} ${lastName.charAt(0)}.`
  } else if (firstName) {
    return firstName
  } else if (lastName) {
    return lastName
  }
  return 'User'
})

const userAvatarInfo = computed(() => {
  const user = expertUser.value || authStore.user
  const name = user ? `${user.first_name || ''} ${user.last_name || ''}`.trim() : ''
  return generateInitialsAvatar(name || 'User')
})

const accountType = computed(() => {
  const expertPlan = expertUser.value.expert_plan;
  if (!expertPlan?.account_type) return 'N/A'
  
  // Capitalize first letter of expert_type
  return expertPlan.account_type.charAt(0).toUpperCase() + expertPlan.account_type.slice(1)
})

const currentPlan = computed(() => {
  const expertPlan = expertUser.value.expert_plan;
  if (!expertPlan) return 'N/A'
  
  return expertPlan?.plan?.name
})

const nextBillingDate = computed(() => {
  const expertPlan = expertUser.value.expert_plan;
  if (!expertPlan) return 'N/A'
  const date = expertPlan.next_bill_date;
  const formattedDate = formatDate(date);
  
  return expertPlan?.next_bill_date !== null ? formattedDate : 'N/A'
})

const currentBalance = computed(() => {
  // Use the current_balance from expert settings API response
  return parseFloat(expertUser.value?.available_balance || '0')
})

const switchToOldDashboard = async () => {
  try {
    const { data } = await authStore.switchToOldDashboard();
    window.location.href = data.url;
  } catch (err) {
    console.error('SSO switch failed', err);
  }
};

function toggleDropdown() {
  if (!isProfileDropdown.value) {
    // Only fetch data when opening dropdown
    fetchExpertSettings()
  }
  isProfileDropdown.value = !isProfileDropdown.value
}

const openWithdrawModal = () => {
  showWithdrawModal.value = true
  isProfileDropdown.value = false // Close dropdown when opening modal
}

const closeWithdrawModal = () => {
  showWithdrawModal.value = false
}

const onWithdrawSuccess = () => {
  // Refresh the expert settings to get updated balance
  fetchExpertSettings()
  showWithdrawModal.value = false
}

onClickOutside(dropdownRef, () => {
  isProfileDropdown.value = false
})

const logout = async () => {
  authStore.logout()
  await router.push('/expert/login')
}
</script>