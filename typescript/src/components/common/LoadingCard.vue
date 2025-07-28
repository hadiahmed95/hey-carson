<template>
  <div :class="isHorizontalDirection ? 'flex gap-4' : 'space-y-4'">
    <div v-for="n in 3" :key="n" class="p-4 border rounded-2xl shadow-md animate-pulse bg-white"
         :class="isHorizontalDirection ? 'w-[33%]' : ''"
    >
      <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-48 mb-4"></div>
      <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 max-w-[360px] mb-2.5"></div>
      <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 mb-2.5"></div>
      <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 max-w-[330px] mb-2.5"></div>
      <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 max-w-[300px] mb-2.5"></div>
      <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 max-w-[360px]"></div>
      <span class="sr-only">Loading...</span>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  isHorizontalDirection?: boolean
}>()
</script>