<template>
  <div v-if="visible" class="fixed top-2 right-6 z-50 animate-fade-in-down">
    <div
        :class="[
      'px-6 py-4 rounded-md shadow-xl text-white font-medium min-w-[260px] max-w-sm',
      'transition-all duration-300 ease-in-out',
      type === 'error' ? 'bg-red-600' :
      type === 'success' ? 'bg-green-600' :
      'bg-blue-600'
    ]"
    >
      {{ message }}
    </div>
  </div>
</template>

<script setup lang="ts">
import { storeToRefs } from 'pinia'
import { useAlertStore } from '@/store/alert'

const alertStore = useAlertStore()
const { visible, message, type } = storeToRefs(alertStore)
</script>
