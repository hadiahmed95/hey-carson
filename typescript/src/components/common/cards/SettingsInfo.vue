<script setup lang="ts">
defineProps<{
  title: string
  description?: string
}>()
</script>

<template>
  <div  class=" max-w-[400px] w-full dynamic-title-description font-archivo">
    <p class="font-semibold">{{ title }}</p>
    <p
        v-if="description"
        class="text-h4 text-coolGray"
        v-html="description"
    ></p>
  </div>
</template>
