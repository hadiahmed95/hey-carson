<!-- StepWrapper.vue -->
<template>
  <div class="w-full md:w-[34.5rem] h-full border flex flex-col bg-white px-8 py-8 overflow-y-auto gap-14">
    <Logo />
    <slot />
  </div>
</template>

<script setup lang="ts">
import Logo from '../../../assets/icons/logo.svg'
</script>
