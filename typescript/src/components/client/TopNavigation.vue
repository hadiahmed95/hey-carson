<template>
  <header class="flex items-center justify-between p-4 border-b bg-white">
    <div class="text-xl font-bold flex items-center">
      <Logo />
    </div>

    <div class="flex items-center gap-4">
      <NotificationsDropdown :notificationCount="notificationCount" />

      <MessageDropDown :messageCount="messageCount" />
      <ClientProfileDropdown :profileImage="profileImage" />
    </div>
  </header>
</template>

<script setup lang="ts">
import Logo from '@/assets/icons/logo.svg';
import ClientProfileDropdown from "./ClientProfileDropdown.vue";
import MessageDropDown from "./MessageDropDown.vue";
import NotificationsDropdown from "./NotificationsDropdown.vue";

defineProps<{
  messageCount: number
  notificationCount: number
  profileImage: string
}>()
</script>
