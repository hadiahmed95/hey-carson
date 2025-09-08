<!-- StepWrapper.vue -->
<template>
  <div class="w-full md:w-[34.5rem] h-full border flex flex-col bg-white px-8 py-8 overflow-y-auto gap-14">
    <img :src="logo" alt="Shop Experts Logo" class="h-8 w-auto self-start" />
    <slot />
  </div>
</template>

<script setup lang="ts">
const logo = new URL('../../../assets/icons/logo.svg', import.meta.url).href
</script>
