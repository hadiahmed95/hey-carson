<template>
  <div
      class="min-h-screen w-screen flex items-center justify-center bg-center bg-contain px-4"
      :style="{ backgroundImage: `url(${backgroundImage}), linear-gradient(180deg, #FFC2B1, #FFFFFF)`, backgroundSize: 'cover', backgroundPosition: 'center' }"
  >
<!--    <div class="absolute inset-0 bg-black/30 backdrop-saturate-50 backdrop-brightness-90 z-0"></div>-->
    <div class="flex flex-col gap-8 bg-white rounded-md shadow-md p-8 w-full max-w-md">
      <!-- Logo -->
      <h2 class="text-xl font-bold flex items-center">
        <Logo />
      </h2>

      <div class="flex flex-col gap-8">
        <div class="flex flex-col gap-2">
          <h2 class="font-semibold text-primary">{{ title }}</h2>
          <p class="font-sm text-primary">{{ subtitle }}</p>
        </div>
        <slot name="form" />
        <slot name="footer" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import Logo from "../assets/icons/logo.svg";

defineProps<{
  title: string
  subtitle: string
  backgroundImage: string
}>()
</script>
