<template>
  <header class="flex items-center justify-between py-4 px-8 border-b bg-white">
    <!-- Left side - Close button for onboarding, Logo for normal -->
    <div class="text-xl font-bold flex items-center">
      <Logo />
    </div>

    <!-- Right side - Save Updates button for onboarding, normal dropdowns otherwise -->
    <div class="flex items-center gap-4">
      <template v-if="isListingSettings">
        <div class="hover:bg-greyLight transition-colors hover:cursor-pointer justify-center px-2 py-2 border border-grey rounded-md">
          <button
            class="flex items-center justify-center w-5 h-5"
            @click="$emit('close')"
          >
            <CrossGrey />
          </button>
        </div>

        <div class="hover:cursor-pointer hover:bg-greyLight transition-colors justify-center px-2 py-2 border border-grey rounded-md">
          <button
              class="flex items-center justify-center w-5 h-5"
              @click="$emit('close')"
          >
            <EyeGrey />
          </button>
        </div>

        <button
          class="border border-grey rounded-md px-4 py-2 text-primary font-semibold text-paragraph hover:bg-greyLight transition-colors"
          @click="$emit('save')"
        >
          Save Updates
        </button>
      </template>

      <template v-else>
        <NotificationsDropdown :is-expert="true" />
        <MessageDropDown :messageCount="messageCount" />
        <ExpertProfileDropdown :profileImage="profileImage ?? ''" />
      </template>
    </div>
  </header>
</template>

<script setup lang="ts">
import Logo from '@/assets/icons/logo.svg';
import EyeGrey from '@/assets/icons/eye-grey.svg';
import CrossGrey from '@/assets/icons/cross-grey.svg';
import NotificationsDropdown from "@/components/client/NotificationsDropdown.vue";
import MessageDropDown from "@/components/client/MessageDropDown.vue";
import ExpertProfileDropdown from "@/components/expert/ExpertProfileDropdown.vue";

defineProps<{
  messageCount: number
  profileImage: string | null
  isListingSettings?: boolean
}>()

defineEmits<{
  close: []
  save: []
}>()
</script>