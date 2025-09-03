<script setup lang="ts">
import TiltArrow from '@/assets/icons/arrow-up-right.svg';

defineProps<{
  question: {
    id: number
    title: string
  }
}>()

</script>

<template>
  <div class="flex border rounded-md shadow-sm bg-white mb-4 p-4">
      <div class="flex flex-1 justify-between h-full">
          <h5 class="text-sm mb-2">{{ question.title }}</h5>
          <TiltArrow/>
      </div>
  </div>
</template>
