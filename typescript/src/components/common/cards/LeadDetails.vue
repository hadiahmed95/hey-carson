<script setup lang="ts">
import QuoteLightIcon from "../../../assets/icons/quote-light.svg";
import DownArrow from "../../../assets/icons/down-arrow.svg";
import ExternalLink from "../../../assets/icons/externalLink.svg";
import {ref} from "vue";
const emit = defineEmits<{
  (e: 'showProjectQuote'): void
}>()

const currentSelectedLead = ref({
  leadName: 'Jessica R.',
  email: 'jessica@kitty.com',
  avatar: 'https://randomuser.me/api/portraits/men/1.jpg',
  shopifyPlan: 'Basic',
  websiteUrl: 'https://www.kittyshop.com',
  leadType: 'Free Quote Request',
  budget: '2500.00',
  conversationStarted: '18 May, 2025',
  leadId: 182910,
  leadStatus: 'In Progress',
})
</script>

<template>
  <aside class="bg-white border border-grey p-4 flex flex-col rounded-lg h-fit">
    <h4 class="flex justify-between items-center font-medium mb-4 text-primary">
      Lead Details
      <svg class="w-4 h-4 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M19 9l-7 7-7-7" />
      </svg>
    </h4>
    <div class="flex flex-col items-left gap-3 mb-4">
      <img :src="currentSelectedLead.avatar" alt="avatar" class="w-14 h-14 rounded-full object-cover" />
      <div>
        <h3 class="font-medium text-primary">{{ currentSelectedLead.leadName }}</h3>
        <a href="mailto:jessica@kitty.com" class="text-h4 text-link underline">{{ currentSelectedLead.email }}</a>
      </div>
    </div>

    <div class="text-gray-500 space-y-2">
      <div class="flex items-center justify-between">
        <h4>Store: </h4>
        <a :href="currentSelectedLead.websiteUrl" target="_blank" class="text-h4 text-link hover:underline flex items-center gap-1">
          {{currentSelectedLead.websiteUrl}}
          <ExternalLink />
        </a>
      </div>

      <div class="flex items-center justify-between">
        <h4 class="text-tertiary font-normal">Shopify Plan: </h4>
        <h4 class="text-primary font-normal">{{currentSelectedLead.shopifyPlan}}</h4>
      </div>

      <div class="flex items-center justify-between">
        <h4 class="text-tertiary font-normal">Lead Type: </h4>
        <h4 class="text-primary font-normal">{{currentSelectedLead.leadType}}</h4>
      </div>

      <div class="flex items-center justify-between">
        <h4 class="text-tertiary font-normal">Budget: </h4>
        <h4 class="text-primary font-normal">${{currentSelectedLead.budget}}</h4>
      </div>

      <div class="flex items-center justify-between">
        <h4 class="text-tertiary font-normal">Conversation Started: </h4>
        <h4 class="text-primary font-normal">{{currentSelectedLead.conversationStarted}}</h4>
      </div>

      <div class="flex items-center justify-between">
        <h4 class="text-tertiary ">Lead ID: </h4>
        <h4 class="text-primary font-normal">#{{currentSelectedLead.leadId}}</h4>
      </div>

      <div class="pt-2 text-h4 text-tertiary">
        Lead Status:
        <div class="mt-1 border border-grey p-4 rounded-sm">
          <div class="flex items-center justify-between">
              <span class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span>
                <h4 class="font-medium text-primary">{{currentSelectedLead.leadStatus}}</h4>
              </span>
            <DownArrow/>
          </div>
          <h6 class="text-tertiary">You are actively working on this project.</h6>
        </div>
      </div>
    </div>

    <button
        class="bg-primary text-white text-h4 font-medium py-2 px-4 rounded-sm flex items-center justify-center gap-2 mt-6"
        @click="emit('showProjectQuote')"
    >
      <QuoteLightIcon />
      Send a Project Quote
    </button>
    <button class="text-primary text-h4 border border-grey font-medium py-2 px-4 rounded-sm flex items-center justify-center gap-2 mt-2">
      <QuoteLightIcon />
      Release Project
    </button>
  </aside>
</template>
