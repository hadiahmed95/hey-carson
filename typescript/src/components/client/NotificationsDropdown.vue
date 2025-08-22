<template>
  <div class="relative" ref="dropdownRef">
    <!-- Trigger -->
    <button @click="toggleDropdown" class="p-2 rounded-sm hover:bg-muted relative">
      <BellIcon />
      <span
          v-if="notificationCount > 0"
          class="absolute top-0 -right-1 w-5 h-5 text-[11px] bg-red-500 text-white rounded-full flex items-center justify-center"
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
        <button
            v-if="hasNotifications"
            @click="markAllAsRead"
            class="text-h4 font-normal text-lightBlue hover:underline"
        >
          Mark all as Read
        </button>
      </div>

      <!-- Empty State -->
      <div
          v-if="!hasNotifications"
          class="bg-veryLightGray text-start rounded-md font-normal darkGray p-4"
      >
        <h4 class="font-normal">You don't have any notification yet.</h4>
        <h4>All important alerts for you will show here.</h4>
      </div>

      <router-link
          v-for="notification in notifications"
          :key="notification.id"
          :to="getNotificationLink(notification)"
          @click="handleNotificationClick(notification.id)"
          class="flex flex-col gap-1 px-2 py-2 rounded-md hover:bg-gray-100 hover:p-3 hover:border hover:text-grey cursor-pointer"
      >
        <h4 class="font-normal text-greyExtraDark truncate">{{ notification.project.name }}</h4>
        <h4 class="font-semibold text-primary truncate" :title="notification.event.title">
          {{ notification.event.title }}
        </h4>
        <h6 class="font-normal text-greyExtraDark text-xs">{{ formatTimeAgo(notification.created_at) }}</h6>
      </router-link>

      <div v-if="hasNotifications" class="border-t border-gray-200 mt-4 pt-2">
        <div class="flex justify-center">
          <button class="text-h4 text-lightBlue font-normal hover:underline">
            Read All Notifications
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { onClickOutside } from '@vueuse/core'
import BellIcon from '@/assets/icons/bell.svg'
import { formatTimeAgo } from "@/utils/date.ts"
import { useCommonStore } from "@/store/common.ts"

const props = defineProps<{
  isExpert?: boolean
}>()

const commonStore = useCommonStore()
const isOpen = ref(false)
const dropdownRef = ref(null)

const notifications = computed(() => commonStore.appNotifications?.events || [])
const hasNotifications = computed(() => notifications.value.length > 0)
const notificationCount = computed(() => notifications.value.length)

const toggleDropdown = () => {
  isOpen.value = !isOpen.value
}

const getNotificationLink = (notification: any) => {
  const requestId = notification.project?.request?.id
  return props.isExpert
      ? `/expert/lead/${requestId}`
      : `/client/request/${requestId}`
}

const handleNotificationClick = async (eventId: number) => {
  try {
    await commonStore.updateAppNotification(eventId)
    await commonStore.fetchAppNotifications({ event: 'New' })
    isOpen.value = false
  } catch (error) {
    console.error('Error handling notification click:', error)
  }
}

const markAllAsRead = async () => {
  try {
    await commonStore.updateAppNotifications()
    await commonStore.fetchAppNotifications({ event: 'New' })
  } catch (error) {
    console.error('Error marking all notifications as read:', error)
  }
}

onMounted(async () => {
  try {
    await commonStore.fetchAppNotifications({ event: 'New' })
  } catch (error) {
    console.error('Error fetching notifications:', error)
  }
})

onClickOutside(dropdownRef, () => {
  isOpen.value = false
})
</script>