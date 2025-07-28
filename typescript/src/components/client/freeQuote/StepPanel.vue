<!-- StepWrapper.vue -->
<template>
  <div class="flex flex-col h-full px-8 py-8 overflow-y-auto gap-14 border rounded-md md:w-1/3 bg-white">
    <Logo />
    <slot />
  </div>
</template>

<script setup lang="ts">
import Logo from '../../../assets/icons/logo.svg'
</script>
