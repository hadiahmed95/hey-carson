<script setup lang="ts">
import type { IQuotee } from '../../../types.ts'
import { computed } from 'vue'
import ExternalLink from "../../../assets/icons/externalLink.svg";

const props = defineProps<{
  quote: IQuotee
}>()

const statusStyle = computed(() => {
  switch (props.quote.status) {
    case 'accepted':
      return 'bg-softgreen text-success'
    case 'declined':
      return 'bg-softpink text-darkpink'
    case 'send':
    default:
      return 'bg-pending-light text-pending'
  }
})

// Follow the EXACT pattern from old templates
const displayStatus = computed(() => {
  switch (props.quote.status) {
    case 'accepted':
      return 'Paid' // same as old template: "Completed", "Paid"
    case 'declined':
      return 'Rejected' // same as old template: "Archived"
    case 'send':
      return 'Pending Payment' // same as old template: "Pending Payment"
    default:
      return 'Unknown Status'
  }
})
</script>

<template>
  <div class="bg-white rounded-md p-card-padding space-y-4 border border-grey">

    <div class="flex justify-between items-start">
      <div>
        <div class="text-paragraph font-semibold text-primary">{{ quote.title }}</div>
        <a :href="quote.link" target="_blank" class="flex items-center gap-1 text-h4 text-link hover:underline">
          {{ quote.link }}
          <ExternalLink />
        </a>
      </div>
      <h5 class="flex flex-col items-end text-tertiary">
        <span :class="['px-3 py-1 rounded-md font-medium', statusStyle]">{{ displayStatus }}</span>
        <span v-if="quote.status === 'declined'" class="mt-1">Quote Rejected: {{ quote.rejectedDate }}</span>
        <span v-if="quote.status === 'accepted'" class="mt-1">Quote Paid: {{ quote.paidDate }}</span>
        <span>Quote Sent: {{ quote.sentDate }}</span>
      </h5>
    </div>

    <!-- Rest of template remains same -->
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
        <!-- Client Avatar with initials fallback -->
        <div v-if="quote.client.avatarInfo" 
             :class="['w-[64px] h-[64px] rounded-full flex items-center justify-center text-white font-semibold', quote.client.avatarInfo.bgColor]">
          {{ quote.client.avatarInfo.initials }}
        </div>
        <img v-else
            :src="quote.client.avatar"
            alt="Client avatar"
            class="w-[64px] h-[64px] rounded-full object-cover"
        />
        <div>
          <div class="text-h4 text-tertiary">Client</div>
          <p class="text-primary font-medium">{{ quote.client.name }}</p>
          <a :href="`mailto:${quote.client.email}`" class="flex items-center gap-1 text-h4 text-link hover:underline">
            {{ quote.client.email }}
          </a>
          <h4 class="text-primary">Shopify Plan: {{ quote.client.plan }}</h4>
        </div>
      </div>

      <div class="flex items-start space-x-3">
        <!-- Expert Avatar with initials fallback -->
        <div v-if="quote.expert.avatarInfo" 
             :class="['w-[64px] h-[64px] rounded-full flex items-center justify-center text-white font-semibold', quote.expert.avatarInfo.bgColor]">
          {{ quote.expert.avatarInfo.initials }}
        </div>
        <img v-else
            :src="quote.expert.avatar"
            alt="Expert avatar"
            class="w-[64px] h-[64px] rounded-full object-cover"
        />
        <div>
          <div class="text-h4 text-tertiary">Expert</div>
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
