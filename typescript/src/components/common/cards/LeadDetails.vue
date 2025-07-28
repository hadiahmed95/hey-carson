<script setup lang="ts">
import QuoteLightIcon from "../../../assets/icons/quote-light.svg";
import DownArrow from "../../../assets/icons/down-arrow.svg";
import ExternalLink from "../../../assets/icons/externalLink.svg";
import { computed } from "vue";
import { getS3URL, handleImgError } from "@/utils/helpers.ts";
import { formatDate } from "@/utils/date.ts";
import type { ILeadDetail } from "@/types.ts";

const props = defineProps<{
  leadDetail?: ILeadDetail | null;
}>();

const emit = defineEmits<{
  (e: 'showProjectQuote'): void
}>();

// Computed properties to get data from leadDetail or provide fallbacks
const clientName = computed(() => {
  if (!props.leadDetail?.client) return 'Lead Details';
  const { first_name, last_name } = props.leadDetail.client;
  return `${first_name || ''} ${last_name || ''}`.trim() || 'Client';
});

const clientEmail = computed(() => {
  return props.leadDetail?.client?.email || 'No email provided';
});

const clientAvatar = computed(() => {
  const photo = props.leadDetail?.client?.photo;
  return photo ? getS3URL(photo) : 'https://randomuser.me/api/portraits/men/1.jpg';
});

const shopifyPlan = computed(() => {
  return props.leadDetail?.client?.shopify_plan || 'Not specified';
});

const websiteUrl = computed(() => {
  const url = props.leadDetail?.client?.url;
  if (!url) return null;
  // Ensure URL has protocol
  return url.startsWith('http') ? url : `https://${url}`;
});

const leadType = computed(() => {
  return props.leadDetail?.type || 'Quote Request';
});

const conversationStarted = computed(() => {
  if (!props.leadDetail?.created_at) return 'Not available';
  return formatDate(props.leadDetail.created_at);
});

const leadId = computed(() => {
  return props.leadDetail?.id || 'N/A';
});

const leadStatus = computed(() => {
  return props.leadDetail?.project?.status || 'In Progress';
});

// Budget - this might need to be added to your backend data
const budget = computed(() => {
  // If you have budget data in your API, use it
  // For now, using placeholder since it's not in the current API structure
  return 'Not specified';
});
</script>

<template>
  <aside class="bg-white border border-grey p-4 flex flex-col rounded-lg h-fit">
    <h4 class="flex justify-between items-center font-medium mb-4 text-primary">
      Lead Details
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M19 9l-7 7-7-7" />
      </svg>
    </h4>

    <!-- Loading state -->
    <div v-if="!leadDetail" class="flex flex-col items-center gap-3 mb-4 animate-pulse">
      <div class="w-14 h-14 bg-gray-200 rounded-full"></div>
      <div class="space-y-2">
        <div class="w-24 h-4 bg-gray-200 rounded"></div>
        <div class="w-32 h-3 bg-gray-200 rounded"></div>
      </div>
    </div>

    <!-- Client Info -->
    <div v-else class="flex flex-col items-left gap-3 mb-4">
      <img 
        :src="clientAvatar" 
        :alt="clientName" 
        class="w-14 h-14 rounded-full object-cover" 
        @error="handleImgError"
      />
      <div>
        <h3 class="font-medium text-primary">{{ clientName }}</h3>
        <a 
          :href="`mailto:${clientEmail}`" 
          class="text-h4 text-link underline hover:text-blue-800"
        >
          {{ clientEmail }}
        </a>
      </div>
    </div>

    <!-- Lead Details -->
    <div v-if="!leadDetail" class="text-gray-500 space-y-2 animate-pulse">
      <div v-for="n in 6" :key="n" class="flex items-center justify-between">
        <div class="w-20 h-3 bg-gray-200 rounded"></div>
        <div class="w-24 h-3 bg-gray-200 rounded"></div>
      </div>
    </div>

    <div v-else class="text-gray-500 space-y-2">
      <!-- Store -->
      <div class="flex items-center justify-between">
        <h4>Store:</h4>
        <a 
          v-if="websiteUrl" 
          :href="websiteUrl" 
          target="_blank" 
          class="text-h4 text-link hover:underline flex items-center gap-1"
        >
          {{ leadDetail.client?.url || websiteUrl }}
          <ExternalLink />
        </a>
        <span v-else class="text-h4 text-gray-400">Not provided</span>
      </div>

      <!-- Shopify Plan -->
      <div class="flex items-center justify-between">
        <h4 class="text-tertiary font-normal">Shopify Plan:</h4>
        <h4 class="text-primary font-normal">{{ shopifyPlan }}</h4>
      </div>

      <!-- Lead Type -->
      <div class="flex items-center justify-between">
        <h4 class="text-tertiary font-normal">Lead Type:</h4>
        <h4 class="text-primary font-normal">{{ leadType }}</h4>
      </div>

      <!-- Budget -->
      <div class="flex items-center justify-between">
        <h4 class="text-tertiary font-normal">Budget:</h4>
        <h4 class="text-primary font-normal">{{ budget }}</h4>
      </div>

      <!-- Conversation Started -->
      <div class="flex items-center justify-between">
        <h4 class="text-tertiary font-normal">Conversation Started:</h4>
        <h4 class="text-primary font-normal">{{ conversationStarted }}</h4>
      </div>

      <!-- Lead ID -->
      <div class="flex items-center justify-between">
        <h4 class="text-tertiary">Lead ID:</h4>
        <h4 class="text-primary font-normal">#{{ leadId }}</h4>
      </div>

      <!-- Lead Status -->
      <div class="pt-2 text-h4 text-tertiary">
        Lead Status:
        <div class="mt-1 border border-grey p-4 rounded-sm">
          <div class="flex items-center justify-between">
            <span class="flex items-center gap-2">
              <span 
                class="w-2 h-2 rounded-full inline-block"
                :class="{
                  'bg-green-500': leadStatus === 'In Progress' || leadStatus === 'Active',
                  'bg-yellow-500': leadStatus === 'Pending',
                  'bg-blue-500': leadStatus === 'Completed',
                  'bg-gray-500': leadStatus === 'Closed',
                  'bg-green-500': !leadStatus // fallback
                }"
              ></span>
              <h4 class="font-medium text-primary">{{ leadStatus }}</h4>
            </span>
            <DownArrow/>
          </div>
          <h6 class="text-tertiary">
            <span v-if="leadStatus === 'In Progress' || leadStatus === 'Active'">
              You are actively working on this project.
            </span>
            <span v-else-if="leadStatus === 'Pending'">
              This project is pending your response.
            </span>
            <span v-else-if="leadStatus === 'Completed'">
              This project has been completed.
            </span>
            <span v-else-if="leadStatus === 'Closed'">
              This project has been closed.
            </span>
            <span v-else>
              Project status: {{ leadStatus }}
            </span>
          </h6>
        </div>
      </div>
    </div>

    <!-- Action Buttons -->
    <button
        class="bg-primary text-white text-h4 font-medium py-2 px-4 rounded-sm flex items-center justify-center gap-2 mt-6 hover:bg-primary/90 transition-colors"
        @click="emit('showProjectQuote')"
        :disabled="!leadDetail"
    >
      <QuoteLightIcon />
      Send a Project Quote
    </button>
    <button 
      class="text-primary text-h4 border border-grey font-medium py-2 px-4 rounded-sm flex items-center justify-center gap-2 mt-2 hover:bg-gray-50 transition-colors"
      :disabled="!leadDetail"
    >
      <QuoteLightIcon />
      Release Project
    </button>
  </aside>
</template>