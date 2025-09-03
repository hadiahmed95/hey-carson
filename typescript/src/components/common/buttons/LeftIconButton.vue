<script setup lang="ts">
defineProps<{
  isLightTheme?: boolean,
  icon: String
}>()
</script>

<template>
  <button
    :class="[
      'flex rounded-md py-2 px-4 items-center gap-1',
      isLightTheme ? 'bg-white text-primary' : 'bg-primary text-white'
    ]"
  >
    <component :is="icon" v-if="icon" class="w-5 h-5" />
    <p class="font-semibold"><slot></slot></p>
  </button>
</template>