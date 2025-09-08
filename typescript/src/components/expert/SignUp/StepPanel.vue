<!-- StepWrapper.vue -->
<template>
  <div class="flex flex-col h-full px-8 py-6 gap-14 overflow-auto bg-white border rounded-md w-full md:w-[34.625rem]">
    <div 
      class="cursor-pointer hover:opacity-80 transition-opacity duration-200 w-fit"
      @click="navigateToLogin"
    >
      <Logo />
    </div>
    <slot />
  </div>
</template>

<script setup lang="ts">
import Logo from '../../../assets/icons/logo.svg'
import { useRouter } from 'vue-router'

const router = useRouter()

const navigateToLogin = () => {
  router.push('/expert/login')
}
</script>
