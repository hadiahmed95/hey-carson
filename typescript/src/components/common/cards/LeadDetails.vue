<script setup lang="ts">
import QuoteLightIcon from "../../../assets/icons/quote-light.svg";
import DownArrow from "../../../assets/icons/down-arrow.svg";
import ExternalLink from "../../../assets/icons/externalLink.svg";
import { computed } from "vue";
import { getS3URL, handleImgError } from "@/utils/helpers.ts";
import { formatDate } from "@/utils/date.ts";
import type { ILeadDetail } from "@/types.ts";
import VerticalLoadingCard from "@/components/common/VerticalLoadingCard.vue";

const props = defineProps<{
  leadDetail?: ILeadDetail | null;
  buttonText: string;
  hasQuoteWithSendStatus: boolean;
  isLoading: boolean;
}>();

const emit = defineEmits<{
  (e: 'showProjectQuote'): void
}>();

const formatStatusText = (status: string | undefined): string => {
  if (!status) return 'In Progress';

  return status
    .split('_')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
    .join(' ');
};

const currentSelectedLead = computed(() => {
  if (!props.leadDetail) {
    return {
      leadName: 'Loading...',
      email: 'Loading...',
      avatar: 'https://randomuser.me/api/portraits/men/1.jpg',
      shopifyPlan: 'Loading...',
      websiteUrl: '#',
      leadType: 'Loading...',
      budget: '0.00',
      conversationStarted: 'Loading...',
      leadId: 0,
      leadStatus: 'Loading...',
    };
  }

  const { client, project, type, created_at, id } = props.leadDetail;

  return {
    leadName: client ? `${client.first_name || ''} ${client.last_name || ''}`.trim() : 'Client',
    email: client?.email || 'No email provided',
    avatar: getS3URL(client.photo ?? ''),
    shopifyPlan: client?.shopify_plan,
    websiteUrl: client?.url ? (client.url.startsWith('http') ? client.url : `https://${client.url}`) : '#',
    leadType: type || 'Quote Request',
    budget: '',
    conversationStarted: created_at ? formatDate(created_at) : 'Not available',
    leadId: id || 0,
    leadStatus: formatStatusText(project.status) || 'In Progress',
  };
});
</script>

<template>
  <aside class="bg-white border border-grey py-4 px-6 flex flex-col rounded-lg h-fit">
    <!-- Loading State -->
    <VerticalLoadingCard v-if="isLoading"/>
    <div v-else>
      <h4 class="flex justify-between items-center font-medium mb-4 text-primary w-full">
        Lead Details
        <svg class="w-4 h-4 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path d="M19 9l-7 7-7-7" />
        </svg>
      </h4>
      <div class="flex flex-col items-left gap-3 mb-4">
        <img
            :src="currentSelectedLead.avatar"
            alt="avatar"
            class="w-14 h-14 rounded-full object-cover"
            @error="handleImgError"
        />
        <div>
          <h3 class="font-medium text-primary">{{ currentSelectedLead.leadName }}</h3>
          <a
              :href="`mailto:${currentSelectedLead.email}`"
              class="text-h4 text-link underline"
          >
            {{ currentSelectedLead.email }}
          </a>
        </div>
      </div>

      <div class="text-gray-500 space-y-2">
        <div class="flex items-center justify-between">
          <h5 class="text-light-grey font-normal">Store: </h5>
          <a
              v-if="currentSelectedLead.websiteUrl !== '#'"
              :href="currentSelectedLead.websiteUrl"
              target="_blank"
              class="text-h5 text-link hover:underline flex items-center gap-1"
          >
            {{ props.leadDetail?.client?.url || 'N/A' }}
            <ExternalLink />
          </a>
          <span v-else class="text-h5 text-primary">N/A</span>
        </div>

        <div class="flex items-center justify-between">
          <h5 class="text-light-grey font-normal">Shopify Plan: </h5>
          <h5 class="text-primary font-normal text-right">{{ currentSelectedLead.shopifyPlan ? currentSelectedLead.shopifyPlan : '-' }}</h5>
        </div>

        <div class="flex items-center justify-between">
          <h5 class="text-light-grey font-normal">Lead Type: </h5>
          <h5
              class="text-primary font-normal px-2 py-0.5 rounded"
              :class="{
              'bg-babyBlue text-deepBlue': currentSelectedLead.leadType === 'Quote Request',
              'bg-lightApricot text-earthyOrangeBrown': currentSelectedLead.leadType === 'Matched',
              'bg-lightPurple text-deepViolet': currentSelectedLead.leadType === 'Direct Message'
            }"
          >
            {{ currentSelectedLead.leadType }}
          </h5>
        </div>

        <div class="flex items-center justify-between">
          <h5 class="text-light-grey font-normal">Budget: </h5>
          <h5 class="text-primary font-normal text-right">{{ currentSelectedLead.budget ? '$' + currentSelectedLead.budget : '-' }}</h5>
        </div>

        <div class="flex items-center justify-between">
          <h5 class="text-light-grey font-normal">Conversation Started: </h5>
          <h5 class="text-primary font-normal text-right">{{ currentSelectedLead.conversationStarted }}</h5>
        </div>

        <div class="flex items-center justify-between">
          <h5 class="text-light-grey font-normal">Lead ID: </h5>
          <h5 class="text-primary font-normal text-right">#{{ currentSelectedLead.leadId }}</h5>
        </div>

        <div class="pt-2 text-h5 text-tertiary">
          Lead Status:
          <div class="mt-1 border border-grey p-4 rounded-sm">
            <div class="flex items-center justify-between">
                <span class="flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span>
                  <h5 class="font-medium text-primary">{{ currentSelectedLead.leadStatus }}</h5>
                </span>
              <DownArrow/>
            </div>
            <h6 class="text-tertiary">You are actively working on this project.</h6>
          </div>
        </div>
      </div>

      <button
          v-if="!hasQuoteWithSendStatus"
          class="bg-primary text-white text-h4 font-medium py-2 px-4 rounded-sm flex items-center justify-center gap-2 mt-6 w-full"
          @click="emit('showProjectQuote')"
      >
        <QuoteLightIcon />
        {{buttonText}}
      </button>
      <button class="text-primary text-h4 border border-grey font-medium py-2 px-4 rounded-sm flex items-center justify-center gap-2 mt-2 w-full">
        <QuoteLightIcon />
        Release Project
      </button>
    </div>
  </aside>
</template>