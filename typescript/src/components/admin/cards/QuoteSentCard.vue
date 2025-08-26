<script setup lang="ts">
import type { IQuotee } from '../../../types.ts'
import { computed } from 'vue'
import ExternalLink from "../../../assets/icons/externalLink.svg";
import { getS3URL, generateInitialsAvatar } from "@/utils/helpers.ts";

const props = defineProps<{
  quote: IQuotee
}>()

const statusStyle = computed(() => {
  switch (props.quote.payment_status) {
    case 'Paid':
      return 'bg-softgreen text-success'
    case 'Rejected':
    case 'Expired' :
      return 'bg-softpink text-darkpink'
    case 'Pending Payment':
    default:
      return 'bg-pending-light text-pending'
  }
})

const formatDate = (dateString: string) => {
  if (!dateString) return 'N/A';
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
}

const formatCurrency = (amount: number) => {
  return '$' + amount.toFixed(2);
}

const calculateTotal = () => {
  return formatCurrency(props.quote.rate * props.quote.hours);
}

const formatUrl = (url: string) => {
  if (!url) return '';
  return url.startsWith('http') ? url : `https://${url}`;
}
</script>

<template>
  <div class="bg-white rounded-md p-card-padding space-y-4 border border-grey">

    <div class="flex justify-between items-start">
      <div>
        <p class="font-semibold text-primary">{{ quote.project_name }}</p>
        <a 
          :href="formatUrl(quote.project_url)" 
          target="_blank" 
          class="flex items-center gap-1 text-h4 text-link hover:underline"
        >
          {{ quote.project_url }}
          <ExternalLink />
        </a>
      </div>
      <h5 class="flex flex-col items-end text-tertiary">
        <span :class="['px-3 py-1 rounded-md font-medium', statusStyle]">
          {{ quote.payment_status }}
        </span>
        <span>Quote Sent: {{ formatDate(quote.created_at) }}</span>
      </h5>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-7 text-sm pt-4">
      <h4 class="flex flex-col">
        <span>Hourly Rate</span>
        <span class="font-medium text-h3">{{ formatCurrency(quote.rate) }}/hour</span>
      </h4>
      <h4 class="flex flex-col">
        <span>Estimated Time</span>
        <span class="font-medium text-h3">{{ quote.hours }} {{ quote.hours === 1 ? 'hour' : 'hours' }}</span>
      </h4>
      <h4 class="flex flex-col">
        <span>Deadline Date</span>
        <span class="font-medium text-h3">{{ formatDate(quote.deadline) }}</span>
      </h4>
      <h4 class="flex flex-col">
        <span>Total to Pay</span>
        <span class="font-medium text-h3">{{ calculateTotal() }}</span>
      </h4>
    </div>

    <div class="grid grid-cols-4 md:grid-cols-4 gap-4 border-t pt-4">
      <div class="flex items-start space-x-3">
        <!-- Client Avatar -->
        <div class="w-16 h-16 rounded-full overflow-hidden">
          <!-- Show actual image if photo exists in backend -->
          <img
              v-if="quote.client_photo && quote.client_photo !== null && quote.client_photo !== ''"
              :src="getS3URL(quote.client_photo)"
              alt="Client avatar"
              class="w-16 h-16 rounded-full object-cover"
          />
          <!-- Show initials avatar if no photo -->
          <div
              v-else
             :class="[
               'w-full h-full rounded-full flex items-center justify-center text-white font-semibold text-h3',
               generateInitialsAvatar(quote.client_name || 'Client').bgColor
             ]"
          >
           {{ generateInitialsAvatar(quote.client_name || 'Client').initials }}
          </div>
        </div>
        <div>
          <h4 class="text-tertiary">Client</h4>
          <p class="text-primary font-medium">{{ quote.client_name }}</p>
          <a 
            v-if="quote.client_url"
            :href="formatUrl(quote.client_url)" 
            target="_blank"
            class="flex items-center gap-1 text-h4 text-link hover:underline"
          >
            {{ quote.client_url }}
          </a>
          <h4 class="text-primary">Shopify Plan: {{ quote.client_shopify_plan || 'N/A' }}</h4>
        </div>
      </div>

      <div class="flex items-start space-x-3">
        <!-- Expert Avatar with same logic as ListingCard -->
        <div class="w-16 h-16 rounded-full overflow-hidden">
          <!-- Show actual image if photo exists in backend -->
          <img
              v-if="quote.expert_photo && quote.expert_photo !== null && quote.expert_photo !== ''"
              :src="getS3URL(quote.expert_photo)"
              alt="Expert avatar"
              class="w-16 h-16 rounded-full object-cover"
          />
          <!-- Show initials avatar if no photo -->
          <div
              v-else
             :class="[
               'w-full h-full rounded-full flex items-center justify-center text-white font-semibold text-h3',
               generateInitialsAvatar(quote.expert_name).bgColor
             ]"
          >
           {{ generateInitialsAvatar(quote.expert_name).initials }}
          </div>
        </div>
        <div>
          <h4 class="text-tertiary">Expert</h4>
          <p class="text-primary font-medium">{{ quote.expert_name }}</p>
          <h4 class="text-primary">{{ quote.expert_type }}</h4>
          <a 
            v-if="quote.expert_url"
            :href="formatUrl(quote.expert_url)" 
            target="_blank"
            class="flex items-center gap-1 text-h4 text-link hover:underline"
          >
            {{ quote.expert_url }}
          </a>
        </div>
      </div>

    </div>
  </div>
</template>