<template>
  <div class="relative" ref="dropdownRef">
    <button
      @click="toggleDropdown"
    >
      <img
        :src="profileImage"
        alt="Profile"
        class="w-8 h-8 rounded-full object-cover"
        @error="handleImgError"
      />
    </button>

    <div
        v-show="isProfileDropdown" class="absolute right-0 mt-2 w-[343px] bg-white border border-grey rounded-md shadow-lg z-50 p-4 flex flex-col items-start transition-opacity duration-200 ease-in-out select-none"
    >
      <div class="w-full p-2 border border-grey rounded-md">
        <div class="flex items-center justify-between">

          <!-- Profile Section -->
          <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-lightApricot rounded-full flex items-center justify-center overflow-hidden">
              <img
                  :src="profileIcon"
                  alt="Profile"
                  class="w-full h-full object-cover"
              />
            </div>

            <!-- Profile Info -->
            <div>
              <h3 class="text-h2 font-semibold">Jonathan K.</h3>
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
            <span class="text-h4 font-normal text-primary">Freelancer</span>
          </div>

          <div class="flex justify-between items-center">
            <span class="text-h4 font-normal text-greyDark">Current Plan:</span>
            <span class="text-h4 font-normal text-primary">Premium</span>
          </div>

          <div class="flex justify-between items-center">
            <span class="text-h4 font-normal text-greyDark">Next Billing Date:</span>
            <span class="text-h4 font-normal text-primary">17 Dec, 2025</span>
          </div>
        </div>

      </div>

      <!-- Balance Section -->
      <div class="p-1 mt-4 w-full">
        <div class="flex justify-between items-center mb-2">
          <h4 class="text-greyDark font-normal">Your Balance:</h4>
          <h2 class="font-bold">$1450.00</h2>
        </div>

        <button class="w-full text-h4 gap-2 bg-white border border-grey font-semibold rounded-sm py-1 px-4 flex items-center justify-center space-x-2 hover:bg-veryLightGray transition-colors">
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
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/store/auth.ts'
import { onClickOutside } from '@vueuse/core'
import profileIcon from '@/assets/icons/profile.png'
import SettingsIcon from '@/assets/icons/settings.svg'
import PayoutIcon from '@/assets/icons/payout.svg'
import LogoutIcon from '@/assets/icons/logout.svg'
import DownloadIcon from '@/assets/icons/download2.svg'
import RightArrow from '@/assets/icons/right-arrow.svg'
import SwitchOff from '@/assets/icons/switch-off.svg'
import SwitchOn from '@/assets/icons/switch-on.svg'
import Overview from "@/assets/icons/overview.svg";
import {handleImgError} from "@/utils/helpers.ts";

const router = useRouter()
const authStore = useAuthStore()

defineProps<{ profileImage: string }>()

const isProfileDropdown = ref(false)
const expandAccountInfo = ref(false)
const dropdownRef = ref(null)

const switchToOldDashboard = async () => {
  try {
    const { data } = await authStore.switchToOldDashboard();
    window.location.href = data.url;
  } catch (err) {
    console.error('SSO switch failed', err);
  }
};

function toggleDropdown() {
  isProfileDropdown.value = !isProfileDropdown.value
}

onClickOutside(dropdownRef, () => {
  isProfileDropdown.value = false
})

const logout = async () => {
  authStore.logout()
  await router.push('/expert/login')
}
</script>
