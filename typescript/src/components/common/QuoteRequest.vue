<script setup lang="ts">
import { defineProps, computed, watch } from 'vue';
import type { IQuotee, IRequest } from '@/types.ts';
import QuoteCard from './cards/QuoteCard.vue';
import { formatDate } from "@/utils/date.ts";

const props = defineProps<{
  isClientSide?: boolean,
  request?: IRequest
}>();

watch(
  () => props.request,
  () => {
  },
  { immediate: true, deep: true }
);

const projectDescription = computed(() => {
  const desc = props.request?.project?.description;
  console.log('Project description:', desc);
  return desc && desc.trim().length > 0 ? desc : 'No project description available.';
});


const quotes = computed<IQuotee[]>(() => {
  const rawQuotes = props.request?.project?.active_assignment ? props.request?.project?.active_assignment?.offers : props.request?.expert?.quotes || [];

  return rawQuotes.map((quote: any) => {
    return {
      id: quote.id,
      type: 'Quote',
      rate: quote.rate.toFixed(2),
      status: quote.status,
      hours: quote.hours,
      deadline: quote.deadline ? formatDate(quote.deadline) : 'N/A',
      created_at: quote.created_at
          ? formatDate(quote.created_at, true)
          : 'N/A'
    };
  });
});

</script>


<template>
  <div class="p-6 border border-grey bg-white rounded-md space-y-6">
    <template v-if="props.request && props.request.project">
      <template v-if="props.isClientSide">
        <div class="flex flex-col gap-2">
          <h4 class="font-semibold">Expert Offers</h4>
          <QuoteCard
              v-for="quote in quotes"
              :key="quote.id"
              :quote="quote"
              :is-client-side="props.isClientSide"
          />
        </div>

        <div class="space-y-4">
          <h4 class="pb-2 border-b border-grey font-semibold">
            Quote Request Description
          </h4>
          <p v-html="projectDescription"></p>
        </div>
      </template>

      <template v-else>
        <div class="space-y-4">
          <h4 class="pb-2 border-b border-grey font-semibold">
            Quote Request Description
          </h4>
          <p v-html="projectDescription"></p>
        </div>

        <div class="space-y-2">
          <h4 class="font-semibold">Your Quotes</h4>
          <QuoteCard v-for="quote in quotes" :key="quote.id" :quote="quote" />
        </div>
      </template>
    </template>

    <template v-else>
      <p class="text-red-500">Project data not available.</p>
    </template>
  </div>
</template>
