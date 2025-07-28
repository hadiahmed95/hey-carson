<!-- StepWrapper.vue -->
<template>
  <div class="flex flex-col h-full px-8 py-6 gap-14 overflow-auto bg-white border rounded-md w-full md:w-[34.625rem]">
    <Logo />
    <slot />
  </div>
</template>

<script setup lang="ts">
import Logo from '../../../assets/icons/logo.svg'
</script>
