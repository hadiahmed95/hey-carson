<template>
  <header class="flex items-center justify-between py-4 px-8 border-b bg-white">
    <div class="text-xl font-bold flex items-center">
      <Logo />
    </div>

    <div class="flex items-center gap-4">
      <NotificationsDropdown :is-expert="true" :notificationCount="notificationCount" />

      <MessageDropDown :messageCount="messageCount" />
      <ExpertProfileDropdown :profileImage="profileImage ?? ''" />
    </div>
  </header>
</template>

<script setup lang="ts">
import Logo from '../../assets/icons/logo.svg';
import NotificationsDropdown from "@/components/client/NotificationsDropdown.vue";
import MessageDropDown from "@/components/client/MessageDropDown.vue";
import ExpertProfileDropdown from "@/components/expert/ExpertProfileDropdown.vue";

defineProps<{
  messageCount: number
  notificationCount: number
  profileImage: string | null
  isOnboarding?: boolean
}>()
</script>
