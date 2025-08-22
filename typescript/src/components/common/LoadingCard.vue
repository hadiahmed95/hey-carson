<template>
  <div :class="containerClass">
    <div
        v-for="n in count"
        :key="n"
        class="p-4 border rounded-2xl shadow-md animate-pulse bg-white"
        :class="cardClass"
    >
      <!-- Quote Layout -->
      <div v-if="type === 'quote'">
        <div class="flex justify-between items-center mb-4">
          <div class="h-3 bg-gray-200 rounded-full w-24"></div>
          <div class="h-6 bg-gray-200 rounded w-16"></div>
        </div>

        <div class="grid grid-cols-4 gap-4 mb-4">
          <div>
            <div class="h-2 bg-gray-200 rounded-full w-16 mb-1"></div>
            <div class="h-4 bg-gray-200 rounded w-20"></div>
          </div>
          <div>
            <div class="h-2 bg-gray-200 rounded-full w-20 mb-1"></div>
            <div class="h-4 bg-gray-200 rounded w-16"></div>
          </div>
          <div>
            <div class="h-2 bg-gray-200 rounded-full w-16 mb-1"></div>
            <div class="h-4 bg-gray-200 rounded w-24"></div>
          </div>
          <div>
            <div class="h-2 bg-gray-200 rounded-full w-20 mb-1"></div>
            <div class="h-4 bg-gray-200 rounded w-20"></div>
          </div>
        </div>

        <div class="border-t pt-4">
          <div class="flex justify-between items-center">
            <div class="h-4 bg-gray-200 rounded w-24"></div>
            <div class="h-5 bg-gray-200 rounded w-20"></div>
          </div>
        </div>
      </div>

      <!-- Default Layout -->
      <div v-else class="space-y-2.5">
        <div class="h-2.5 bg-gray-200 rounded-full w-48"></div>
        <div class="h-2 bg-gray-200 rounded-full max-w-[360px]"></div>
        <div class="h-2 bg-gray-200 rounded-full"></div>
        <div class="h-2 bg-gray-200 rounded-full max-w-[330px]"></div>
        <div class="h-2 bg-gray-200 rounded-full max-w-[300px]"></div>
      </div>

      <span class="sr-only">Loading...</span>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  count?: number
  horizontal?: boolean
  type?: 'default' | 'quote'
}

const props = withDefaults(defineProps<Props>(), {
  count: 3,
  horizontal: false,
  type: 'default'
})

const containerClass = computed(() => {
  return props.horizontal ? 'flex gap-4' : 'space-y-4'
})

const cardClass = computed(() => {
  return props.horizontal ? `w-[${Math.floor(100 / props.count)}%]` : ''
})
</script>