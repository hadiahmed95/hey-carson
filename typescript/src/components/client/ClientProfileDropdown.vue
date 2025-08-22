<template>
  <div class="relative" ref="dropdownRef">
    <!-- Trigger -->
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

    <!-- Dropdown -->
    <div
        v-show="isOpen"
        class="absolute right-0 mt-2 w-[343px] bg-white border rounded-md shadow-lg z-50 p-4 flex flex-col items-start transition-opacity duration-200 ease-in-out"
    >
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
      <router-link class="w-full hover:bg-gray-100" to="/client/settings">
        <button class="flex items-center px-4 py-2 text-left gap-2">
          <SettingsIcon class="w-5 h-5" />
          Settings
        </button>
      </router-link>
      <router-link class="w-full hover:bg-gray-100" to="/client/transactions">
        <button class="flex items-center gap-2 px-4 py-2 text-left">
          <TransactionsIcon class="w-5 h-5" />
          Transactions
        </button>
      </router-link>
<!--      Todo: We do not need My Team page in v1 -->
<!--      <button class="w-full flex items-center px-4 py-2 text-left hover:bg-gray-100 gap-2">-->
<!--        <TeamIcon class="w-5 h-5" />-->
<!--        My Team-->
<!--      </button>-->
      <button @click="logout()" class="w-full flex items-center px-4 py-2 text-left hover:bg-gray-100 gap-2">
        <LogoutIcon class="w-5 h-5" />
        Logout
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/store/auth.ts'
import { onClickOutside } from '@vueuse/core'
import SettingsIcon from '../../assets/icons/settings.svg'
import TransactionsIcon from '../../assets/icons/transactions.svg'
import LogoutIcon from '../../assets/icons/logout.svg'
import Overview from "@/assets/icons/overview.svg";
import SwitchOn from "@/assets/icons/switch-on.svg";
import SwitchOff from "@/assets/icons/switch-off.svg";
import {handleImgError} from "@/utils/helpers.ts";

const router = useRouter()
const authStore = useAuthStore()

defineProps<{ profileImage: string }>()

const isOpen = ref(false)
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
  isOpen.value = !isOpen.value
}

onClickOutside(dropdownRef, () => {
  isOpen.value = false
})

const logout = async () => {
  await authStore.logout()
  await router.push('/client/login')
}
</script>
