<script setup lang="ts">
import TiltArrow from '@/assets/icons/arrow-up-right.svg';
import type { ExpertFaq } from '@/types.ts';

defineProps<{
  question: ExpertFaq
}>()
</script>

<template>
  <div class="flex border rounded-md shadow-sm bg-white mb-4 p-card-padding border-grey">
      <div class="flex flex-1 justify-between h-full">
          <h5 class="text-paragraph text-primary">{{ question.title }}</h5>
          <TiltArrow/>
      </div>
  </div>
</template>
