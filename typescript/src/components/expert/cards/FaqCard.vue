<script setup lang="ts">
import TiltArrow from '@/assets/icons/arrow-up-right.svg';
import { ref } from 'vue';
import type { ExpertFaq } from '@/types.ts';

defineProps<{
  question: ExpertFaq
}>()

const isExpanded = ref(false);
const toggleAccordion = () => {
  isExpanded.value = !isExpanded.value;
};
</script>

<template>
  <div class="border rounded-md shadow-sm bg-white mb-4 p-card-padding border-grey">
      <div @click="toggleAccordion" class="flex flex-1 justify-between h-full">
          <h5 class="text-paragraph text-primary">{{ question.title }}</h5>
          <TiltArrow :class="{ 'rotate-180': isExpanded }" />
      </div>
      <!-- Answer Content - Expandable -->
      <div 
        class="overflow-hidden transition-all duration-300 ease-in-out"
        :class="isExpanded ? 'opacity-100' : 'max-h-0 opacity-0'"
      >
        <div class="mt-2 pt-2 border-t border-grey">
          <p class="text-grey-black-combo text-h5 leading-relaxed">
            {{ question.answer || 'No answer provided yet.' }}
          </p>
        </div>
      </div>
  </div>
</template>
