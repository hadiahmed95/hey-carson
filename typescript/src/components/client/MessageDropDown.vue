<template>
  <div class="relative" ref="dropdownRef">
    <!-- Trigger -->
    <button @click="toggleDropdown" class="p-2 rounded-sm hover:bg-muted relative">
      <MessageIcon />
      <span
          v-if="messageCount > 0"
          class="absolute -top-1 -right-1 w-4 h-4 text-[11px] bg-red-500 text-white rounded-full flex items-center justify-center"
      >
        {{ messageCount }}
      </span>
    </button>

    <!-- Dropdown -->
    <div
        v-show="isOpen"
        class="absolute right-0 mt-2 w-[343px] bg-white border rounded-md shadow-lg z-50 p-4 flex flex-col gap-4 max-h-[400px] overflow-y-auto"
    >
      <!-- Header -->
      <div class="flex justify-between items-center">
        <p class="font-semibold">Latest Messages</p>
        <button v-if="messages.length" class="text-h4 font-normal text-blue-600 hover:underline">
          Mark all as Read
        </button>
      </div>

      <!-- Empty State -->
      <div
          v-if="messages.length === 0"
          class="bg-veryLightGray text-start rounded-md font-normal darkGray p-4"
      >
        <h4>You don’t have any message yet.</h4>
        <h4>All conversations with experts will show here.</h4>
      </div>

      <!-- Message List -->
      <div
          v-else
          v-for="message in messages"
          :key="message.id"
          class="flex gap-2 w-full px-2 py-2 rounded-md hover:bg-gray-100 cursor-pointer"
      >
        <img
            :src="message.expert.avatarUrl"
            class="w-10 h-10 rounded-full object-cover"
            alt="Expert Avatar"
        />

        <div class="flex flex-col w-full min-w-0">
          <div class="flex justify-between items-center w-full">
            <h4 class="font-semibold text-primary truncate">{{ message.expert.name }}</h4>
            <h6 class="font-normal text-greyExtraDark whitespace-nowrap">{{ message.createdAt }}</h6>
          </div>
          <h6 class="font-normal text-greyExtraDark truncate">{{ message.project.title }}</h6>
          <h4 class="font-semibold text-primary truncate" :title="message.content">
            {{ message.content }}
          </h4>
        </div>
      </div>

      <!-- Footer -->
      <div v-if="messages.length" class="border-t border-gray-200 mt-4 pt-2">
        <div class="flex justify-center">
          <button class="text-h4 text-blue-600 font-normal hover:underline">
            Read All Messages
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { onClickOutside } from '@vueuse/core'
import type { IMessage } from '../../types.ts'
import MessageIcon from '../../assets/icons/message.svg'

defineProps<{ messageCount: number }>()

const isOpen = ref(false)
const dropdownRef = ref(null)
const toggleDropdown = () => (isOpen.value = !isOpen.value)

// ✅ Close when clicked outside
onClickOutside(dropdownRef, () => {
  isOpen.value = false
})

const messages: IMessage[] = [
  {
    id: 1,
    content: 'Hi there! I’ve reviewed your request and have a few questions regarding the layout...',
    createdAt: '2h ago',
    expert: {
      name: 'Lena Hoffmann',
      avatarUrl: 'https://randomuser.me/api/portraits/women/45.jpg'
    },
    project: {
      title: 'Build custom collection page layout'
    }
  },
  {
    id: 2,
    content: 'I’ve deployed the latest changes. Let me know if you need anything else!',
    createdAt: '5h ago',
    expert: {
      name: 'Aaron Patel',
      avatarUrl: 'https://randomuser.me/api/portraits/men/23.jpg'
    },
    project: {
      title: 'Fix mobile responsiveness'
    }
  }
]
</script>
