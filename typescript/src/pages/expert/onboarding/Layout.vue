<template>
  <div class="min-h-screen w-full flex items-start justify-center bg-white px-4 pt-5">
    <div class="w-full max-w-4xl">

      <!-- Step Navigation Bar -->
      <OnboardingStepNavigation />

      <!-- Step Content -->
      <div class="mt-6">
        <RouterView />
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import OnboardingStepNavigation from '../../../components/expert/onboarding/OnboardingStepNavigation.vue'
</script>
