<template>
  <div v-if="visible" class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50">
    <div
        :class="[
        'px-6 py-4 rounded shadow-lg text-white',
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
