<template>
  <div class="relative" ref="dropdownRef">
    <!-- Trigger -->
    <button @click="toggleDropdown" class="p-2 rounded-sm hover:bg-muted relative">
      <BellIcon />
      <span
          v-if="notificationCount > 0"
          class="absolute -top-1 -right-1 w-4 h-4 text-[11px] bg-red-500 text-white rounded-full flex items-center justify-center"
      >
        {{ notificationCount }}
      </span>
    </button>

    <!-- Dropdown -->
    <div
        v-show="isOpen"
        class="absolute right-0 mt-2 w-[343px] bg-white border rounded-md shadow-lg z-50 p-4 flex flex-col gap-4 max-h-[400px] overflow-y-auto"
    >
      <!-- Header -->
      <div class="flex justify-between items-center">
        <p class="font-semibold">Latest Notifications</p>
        <button v-if="notifications.length" class="text-h4 font-normal text-blue-600 hover:underline">
          Mark all as Read
        </button>
      </div>

      <!-- Empty State -->
      <div
          v-if="notifications.length === 0"
          class="bg-veryLightGray text-start rounded-md font-normal darkGray p-4"
      >
        <h4 class="font-normal">You don’t have any notification yet.</h4>
        <h4>All important alerts for you will show here.</h4>
      </div>

      <!-- Notification List -->
      <div
          v-else
          v-for="notification in notifications"
          :key="notification.id"
          class="flex flex-col gap-1 px-2 py-2 rounded-md hover:bg-gray-100 cursor-pointer"
      >
        <h4 class="font-normal text-greyExtraDark truncate">{{ notification.project.title }}</h4>
        <h4 class="font-semibold text-primary truncate" :title="notification.content">
          {{ notification.content }}
        </h4>
        <h6 class="font-normal text-greyExtraDark text-xs">{{ notification.createdAt }}</h6>
      </div>

      <!-- Footer -->
      <div v-if="notifications.length" class="border-t border-gray-200 mt-4 pt-2">
        <div class="flex justify-center">
          <button class="text-h4 text-blue-600 font-normal hover:underline">
            Read All Notifications
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { onClickOutside } from '@vueuse/core'
import type { INotification } from '../../types.ts'
import BellIcon from '../../assets/icons/bell.svg'

defineProps<{ notificationCount: number }>()

const isOpen = ref(false)
const dropdownRef = ref(null)

const toggleDropdown = () => {
  isOpen.value = !isOpen.value
}

// ✅ Close dropdown on outside click
onClickOutside(dropdownRef, () => {
  isOpen.value = false
})

const notifications: INotification[] = [
  {
    id: 1,
    content: 'Your quote request was viewed by the expert.',
    createdAt: '17 minutes ago',
    project: {
      title: 'Collection Page Changes'
    }
  },
  {
    id: 2,
    content: 'A new expert has been matched to your project.',
    createdAt: '2 hours ago',
    project: {
      title: 'Build Custom Landing Page'
    }
  }
]
</script>
