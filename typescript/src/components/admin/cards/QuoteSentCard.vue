<script setup lang="ts">
import type { IQuote } from '../../../types.ts'
import { computed } from 'vue'
import ExternalLink from "../../../assets/icons/externalLink.svg";

const props = defineProps<{
  quote: IQuote
}>()

const statusStyle = computed(() => {
  switch (props.quote.status) {
    case 'Paid':
      return 'bg-softgreen text-success'
    case 'Rejected':
      return 'bg-softpink text-darkpink'
    case 'Pending Payment':
    default:
      return 'bg-pending-light text-pending'
  }
})
</script>

<template>
  <div class="bg-white rounded-md p-card-padding space-y-4 border border-grey">

    <div class="flex justify-between items-start">
      <div>
        <p class="font-semibold text-primary">{{ quote.title }}</p>
        <a :href="quote.link" target="_blank" class="flex items-center gap-1 text-h4 text-link hover:underline">
          {{ quote.link }}
          <ExternalLink />
        </a>
      </div>
      <h5 class="flex flex-col items-end text-tertiary">
        <span :class="['px-3 py-1 rounded-md font-medium', statusStyle]">{{ quote.status }}</span>
        <span v-if="quote.status === 'Rejected'" class="mt-1">Quote Rejected: {{ quote.rejectedDate }}</span>
        <span v-if="quote.status === 'Paid'" class="mt-1">Quote Paid: {{ quote.paidDate }}</span>
        <span>Quote Sent: {{ quote.sentDate }}</span>
      </h5>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-7 text-sm pt-4">
      <h4 class="flex flex-col">
        <span>Hourly Rate</span>
        <span class="font-medium text-h3">{{ quote.hourlyRate }}</span>
      </h4>
      <h4 class="flex flex-col">
        <span>Estimated Time</span>
        <span class="font-medium text-h3">{{ quote.estimatedTime }}</span>
      </h4>
      <h4 class="flex flex-col">
        <span>Deadline Date</span>
        <span class="font-medium text-h3">{{ quote.deadline }}</span>
      </h4>
      <h4 class="flex flex-col">
        <span>Total to Pay</span>
        <span class="font-medium text-h3">{{ quote.total }}</span>
      </h4>
    </div>

    <div class="grid grid-cols-4 md:grid-cols-4 gap-4 border-t pt-4">
      <div class="flex items-start space-x-3">
        <img
            :src="quote.client.avatar"
            alt="Client avatar"
            class="w-[64px] h-[64px] rounded-full object-cover"
        />
        <div>
          <h4 class="text-tertiary">Client</h4>
          <p class="text-primary font-medium">{{ quote.client.name }}</p>
          <a :href="`mailto:${quote.client.email}`" class="flex items-center gap-1 text-h4 text-link hover:underline">
            {{ quote.client.email }}
          </a>
          <h4 class="text-primary">Shopify Plan: {{ quote.client.plan }}</h4>
        </div>
      </div>

      <div class="flex items-start space-x-3">
        <img
            :src="quote.expert.avatar"
            alt="Expert avatar"
            class="w-[64px] h-[64px] rounded-full object-cover"
        />
        <div>
          <h4 class="text-tertiary">Expert</h4>
          <p class="text-primary font-medium">{{ quote.expert.name }}</p>
          <h4 class="text-primary">Freelancer</h4>
          <a :href="`mailto:${quote.expert.email}`" class="flex items-center gap-1 text-h4 text-link hover:underline">
            {{ quote.expert.email }}
          </a>
        </div>
      </div>

    </div>
  </div>
</template>
