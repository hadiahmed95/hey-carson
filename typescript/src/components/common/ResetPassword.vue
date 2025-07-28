<template>
  <div
      class="h-screen overflow-hidden flex items-center justify-center bg-cover bg-center px-4"
      :style="{ backgroundImage: `url(${backgroundImage}), linear-gradient(180deg, #FFC2B1, #FFFFFF)`, backgroundSize: 'cover', backgroundPosition: 'center' }"
  >
    <!--    <div class="absolute inset-0 bg-black/30 backdrop-saturate-50 backdrop-brightness-90 z-0"></div>-->
    <div class="flex flex-col gap-8 bg-white rounded-md shadow-md p-8 w-full max-w-md max-h-screen overflow-auto">
      <!-- Logo -->
      <h2 class="text-xl font-bold flex items-center mb-6">
        <Logo />
      </h2>

      <div v-if="step === 1" class="p-4 bg-accent border rounded-md">
        <h4 class="font-semibold">Check your Inbox!</h4>
        <h4 class="font-normal text-darkGreen">A link to reset your password has been emailed to you.</h4>
      </div>

      <!-- Dynamic Titles -->
      <div class="flex flex-col gap-8">
        <div class="flex flex-col gap-2">
          <h2 class="font-semibold text-primary font-archivo mb-1">{{ title }}</h2>
          <p class="font-sm text-primary font-archivo mb-6">{{ subtitle }}</p>
        </div>
        <!-- Slot: Login Form -->
        <slot name="form" />

        <!-- Slot: Custom buttons or links -->
        <slot name="footer" />
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import Logo from "../../assets/icons/logo.svg";

defineProps<{
  title: string
  subtitle: string
  backgroundImage: string
  step: number
}>()
</script>
