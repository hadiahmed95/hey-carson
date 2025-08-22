<script setup lang="ts">

import { ref } from "vue";
import SendIcon from '../../../assets/icons/send.svg'
import PlusIcon from '../../../assets/icons/plus.svg'
import QuoteLightIcon from '../../../assets/icons/quote-light.svg'
import ExternalLink from "../../../assets/icons/externalLink.svg";
import DownArrow from "../../../assets/icons/down-arrow.svg";

const leads = [
  {
    leadName: 'Jessica R.',
    email: 'jessica@kitty.com',
    avatar: 'https://randomuser.me/api/portraits/men/1.jpg',
    conversation: [
      {
        content: 'Hey Jonathan,\n' +
            'I saw your profile in the Shopexperts directory, and we have a really interesting ' +
            'project that I think you might be interested in. Let’s discuss the details when you ' +
            'have some free time.\n' +
            'To give you a brief overview: We’re planning to build a Shopify visual builder, ' +
            'something similar to Instant but with many more AI features, and it will be free ' +
            'for users.\n' +
            'If this sounds interesting, let’s schedule a call early next week to discuss further.\n' +
            'Thank you!',
        sentBy: 1,
        sentAt: '08:30 PM',
      },
      {
        content: "Hey Jessica,\n" +
            "This sounds really interesting to me! " +
            "I'd definitely love to know more details.\n" +
            "I'm on a family holiday this week and next, " +
            "so could you please schedule a meeting after May 23rd? " +
            "I hope that’s not a problem for you!\n" +
            "Here’s my calendar link: https://calendly.com/jonathankennedy",
        sentBy: 2,
        sentAt: '08:35 PM',
      },
    ],
    shopifyPlan: 'Basic',
    websiteUrl: 'https://www.kittyshop.com',
    leadType: 'Free Quote Request',
    budget: '2500.00',
    conversationStarted: '18 May, 2025',
    leadId: 182910,
    leadStatus: 'In Progress',
  },
  {
    leadName: 'Jessica W.',
    email: 'jessica@kitty.com',
    avatar: 'https://randomuser.me/api/portraits/women/11.jpg',
    conversation: [
      {
        content: 'Hey Ali,\n' +
            'I saw your profile in the Shopexperts directory, and we have a really interesting ' +
            'project that I think you might be interested in. Let’s discuss the details when you ' +
            'have some free time.\n' +
            'To give you a brief overview: We’re planning to build a Shopify visual builder, ' +
            'something similar to Instant but with many more AI features, and it will be free ' +
            'for users.\n' +
            'If this sounds interesting, let’s schedule a call early next week to discuss further.\n' +
            'Thank you!',
        sentBy: 1,
        sentAt: '08:30 PM',
      },
      {
        content: "Hey Jessica,\n" +
            "This sounds really interesting to me! " +
            "I'd definitely love to know more details.\n" +
            "I'm on a family holiday this week and next, " +
            "so could you please schedule a meeting after May 23rd? " +
            "I hope that’s not a problem for you!\n" +
            "Here’s my calendar link: https://calendly.com/jonathankennedy",
        sentBy: 2,
        sentAt: '08:35 PM',
      },
    ],
    shopifyPlan: 'Basic',
    websiteUrl: 'https://www.kittyshop.com',
    leadType: 'Free Quote Request',
    budget: '2500.00',
    conversationStarted: '18 May, 2025',
    leadId: 182910,
    leadStatus: 'In Progress',
  },
  {
    leadName: 'Olivia P.',
    email: 'jessica@kitty.com',
    avatar: 'https://randomuser.me/api/portraits/women/21.jpg',
    conversation: [
      {
        content: 'That is nice! Thank you a lot!',
        sentBy: 1,
        sentAt: '08:30 PM',
      },
      {
        content: "Hey Jessica,\n" +
            "This sounds really interesting to me! " +
            "I'd definitely love to know more details.\n" +
            "I'm on a family holiday this week and next, " +
            "so could you please schedule a meeting after May 23rd? " +
            "I hope that’s not a problem for you!\n" +
            "Here’s my calendar link: https://calendly.com/jonathankennedy",
        sentBy: 2,
        sentAt: '08:35 PM',
      },
    ],
    shopifyPlan: 'Basic',
    websiteUrl: 'https://www.kittyshop.com',
    leadType: 'Free Quote Request',
    budget: '2500.00',
    conversationStarted: '18 May, 2025',
    leadId: 182910,
    leadStatus: 'In Progress',
  },
  {
    leadName: 'Paul W.',
    email: 'jessica@kitty.com',
    avatar: 'https://randomuser.me/api/portraits/men/1.jpg',
    conversation: [
      {
        content: 'Hey Jonathan,\n' +
            'I saw your profile in the Shopexperts directory, and we have a really interesting ' +
            'project that I think you might be interested in. Let’s discuss the details when you ' +
            'have some free time.\n' +
            'To give you a brief overview: We’re planning to build a Shopify visual builder, ' +
            'something similar to Instant but with many more AI features, and it will be free ' +
            'for users.\n' +
            'If this sounds interesting, let’s schedule a call early next week to discuss further.\n' +
            'Thank you!',
        sentBy: 1,
        sentAt: '08:30 PM',
      },
      {
        content: "Hey Jessica,\n" +
            "This sounds really interesting to me! " +
            "I'd definitely love to know more details.\n" +
            "I'm on a family holiday this week and next, " +
            "so could you please schedule a meeting after May 23rd? " +
            "I hope that’s not a problem for you!\n" +
            "Here’s my calendar link: https://calendly.com/jonathankennedy",
        sentBy: 2,
        sentAt: '08:35 PM',
      },
    ],
    shopifyPlan: 'Basic',
    websiteUrl: 'https://www.kittyshop.com',
    leadType: 'Free Quote Request',
    budget: '2500.00',
    conversationStarted: '18 May, 2025',
    leadId: 182910,
    leadStatus: 'In Progress',
  },
  {
    leadName: 'Jack R.',
    email: 'jessica@kitty.com',
    avatar: 'https://randomuser.me/api/portraits/men/11.jpg',
    conversation: [
      {
        content: 'Hey Jonathan,\n' +
            'I saw your profile in the Shopexperts directory, and we have a really interesting ' +
            'project that I think you might be interested in. Let’s discuss the details when you ' +
            'have some free time.\n' +
            'To give you a brief overview: We’re planning to build a Shopify visual builder, ' +
            'something similar to Instant but with many more AI features, and it will be free ' +
            'for users.\n' +
            'If this sounds interesting, let’s schedule a call early next week to discuss further.\n' +
            'Thank you!',
        sentBy: 1,
        sentAt: '08:30 PM',
      },
      {
        content: "Hey Jessica,\n" +
            "This sounds really interesting to me! " +
            "I'd definitely love to know more details.\n" +
            "I'm on a family holiday this week and next, " +
            "so could you please schedule a meeting after May 23rd? " +
            "I hope that’s not a problem for you!\n" +
            "Here’s my calendar link: https://calendly.com/jonathankennedy",
        sentBy: 2,
        sentAt: '08:35 PM',
      },
    ],
    shopifyPlan: 'Basic',
    websiteUrl: 'https://www.kittyshop.com',
    leadType: 'Free Quote Request',
    budget: '2500.00',
    conversationStarted: '18 May, 2025',
    leadId: 182910,
    leadStatus: 'In Progress',
  },
  {
    leadName: 'Michael O.',
    email: 'jessica@kitty.com',
    avatar: 'https://randomuser.me/api/portraits/men/15.jpg',
    conversation: [
      {
        content: 'Hey Jonathan,\n' +
            'I saw your profile in the Shopexperts directory, and we have a really interesting ' +
            'project that I think you might be interested in. Let’s discuss the details when you ' +
            'have some free time.\n' +
            'To give you a brief overview: We’re planning to build a Shopify visual builder, ' +
            'something similar to Instant but with many more AI features, and it will be free ' +
            'for users.\n' +
            'If this sounds interesting, let’s schedule a call early next week to discuss further.\n' +
            'Thank you!',
        sentBy: 1,
        sentAt: '08:30 PM',
      },
      {
        content: "Hey Jessica,\n" +
            "This sounds really interesting to me! " +
            "I'd definitely love to know more details.\n" +
            "I'm on a family holiday this week and next, " +
            "so could you please schedule a meeting after May 23rd? " +
            "I hope that’s not a problem for you!\n" +
            "Here’s my calendar link: https://calendly.com/jonathankennedy",
        sentBy: 2,
        sentAt: '08:35 PM',
      },
    ],
    shopifyPlan: 'Basic',
    websiteUrl: 'https://www.kittyshop.com',
    leadType: 'Free Quote Request',
    budget: '2500.00',
    conversationStarted: '18 May, 2025',
    leadId: 182910,
    leadStatus: 'In Progress',
  },
  {
    leadName: 'Antoan G.',
    email: 'jessica@kitty.com',
    avatar: 'https://randomuser.me/api/portraits/men/18.jpg',
    conversation: [
      {
        content: 'Hey Jonathan,\n' +
            'I saw your profile in the Shopexperts directory, and we have a really interesting ' +
            'project that I think you might be interested in. Let’s discuss the details when you ' +
            'have some free time.\n' +
            'To give you a brief overview: We’re planning to build a Shopify visual builder, ' +
            'something similar to Instant but with many more AI features, and it will be free ' +
            'for users.\n' +
            'If this sounds interesting, let’s schedule a call early next week to discuss further.\n' +
            'Thank you!',
        sentBy: 1,
        sentAt: '08:30 PM',
      },
      {
        content: "Hey Jessica,\n" +
            "This sounds really interesting to me! " +
            "I'd definitely love to know more details.\n" +
            "I'm on a family holiday this week and next, " +
            "so could you please schedule a meeting after May 23rd? " +
            "I hope that’s not a problem for you!\n" +
            "Here’s my calendar link: https://calendly.com/jonathankennedy",
        sentBy: 2,
        sentAt: '08:35 PM',
      },
    ],
    shopifyPlan: 'Basic',
    websiteUrl: 'https://www.kittyshop.com',
    leadType: 'Free Quote Request',
    budget: '2500.00',
    conversationStarted: '18 May, 2025',
    leadId: 182910,
    leadStatus: 'In Progress',
  },
  {
    leadName: 'Jacob Z.',
    email: 'jessica@kitty.com',
    avatar: 'https://randomuser.me/api/portraits/men/45.jpg',
    conversation: [
      {
        content: 'Hey Jonathan,\n' +
            'I saw your profile in the Shopexperts directory, and we have a really interesting ' +
            'project that I think you might be interested in. Let’s discuss the details when you ' +
            'have some free time.\n' +
            'To give you a brief overview: We’re planning to build a Shopify visual builder, ' +
            'something similar to Instant but with many more AI features, and it will be free ' +
            'for users.\n' +
            'If this sounds interesting, let’s schedule a call early next week to discuss further.\n' +
            'Thank you!',
        sentBy: 1,
        sentAt: '08:30 PM',
      },
      {
        content: "Hey Jessica,\n" +
            "This sounds really interesting to me! " +
            "I'd definitely love to know more details.\n" +
            "I'm on a family holiday this week and next, " +
            "so could you please schedule a meeting after May 23rd? " +
            "I hope that’s not a problem for you!\n" +
            "Here’s my calendar link: https://calendly.com/jonathankennedy",
        sentBy: 2,
        sentAt: '08:35 PM',
      },
    ],
    shopifyPlan: 'Basic',
    websiteUrl: 'https://www.kittyshop.com',
    leadType: 'Free Quote Request',
    budget: '2500.00',
    conversationStarted: '18 May, 2025',
    leadId: 182910,
    leadStatus: 'In Progress',
  },
  {
    leadName: 'Mariah B.',
    email: 'jessica@kitty.com',
    avatar: 'https://randomuser.me/api/portraits/women/91.jpg',
    conversation: [
      {
        content: 'Hey Jonathan,\n' +
            'I saw your profile in the Shopexperts directory, and we have a really interesting ' +
            'project that I think you might be interested in. Let’s discuss the details when you ' +
            'have some free time.\n' +
            'To give you a brief overview: We’re planning to build a Shopify visual builder, ' +
            'something similar to Instant but with many more AI features, and it will be free ' +
            'for users.\n' +
            'If this sounds interesting, let’s schedule a call early next week to discuss further.\n' +
            'Thank you!',
        sentBy: 1,
        sentAt: '08:30 PM',
      },
      {
        content: "Hey Jessica,\n" +
            "This sounds really interesting to me! " +
            "I'd definitely love to know more details.\n" +
            "I'm on a family holiday this week and next, " +
            "so could you please schedule a meeting after May 23rd? " +
            "I hope that’s not a problem for you!\n" +
            "Here’s my calendar link: https://calendly.com/jonathankennedy",
        sentBy: 2,
        sentAt: '08:35 PM',
      },
    ],
    shopifyPlan: 'Basic',
    websiteUrl: 'https://www.kittyshop.com',
    leadType: 'Free Quote Request',
    budget: '2500.00',
    conversationStarted: '18 May, 2025',
    leadId: 182910,
    leadStatus: 'In Progress',
  },
]
const currentSelectedLead = ref(leads[0])
</script>

<template>
  <div class="grid grid-cols-1 md:grid-cols-[300px_1fr_300px] h-screen bg-secondary py-10 px-5 gap-x-5 w-full">
    <aside class="bg-white border border-grey flex flex-col rounded-lg h-fit">
      <h4 class="p-4 font-semibold flex justify-between items-center">
        Messages
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path d="M19 9l-7 7-7-7" />
        </svg>
      </h4>
      <ul class="overflow-y-auto flex-1">
        <li
            v-for="lead in leads"
            :key="lead.leadName"
            class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 cursor-pointer"
            @click="currentSelectedLead = lead"
        >
          <img :src="lead.avatar" alt="avatar" class="w-12 h-12 rounded-full object-cover" />
          <div class="flex-1">
            <h4 class="font-medium leading-tight">{{ lead.leadName }}</h4>
            <h5 class="font-light truncate w-48">{{ lead.conversation?.[0]?.content }}</h5>
            <h6 class="font-light text-gray-500">{{ lead.conversation?.[0]?.sentAt }}</h6>
          </div>
        </li>
      </ul>
    </aside>

    <main class="flex flex-col bg-white rounded-lg border border-grey">
      <h5 class="p-4 text-center text-gray-500">
        Wednesday, 18 May, 2025
      </h5>
      <div class="flex-1 p-6 space-y-4 overflow-y-auto">
        <h4
            v-for="(conversation, index) in currentSelectedLead?.conversation"
            :key="index"
            class="w-2/3 p-4 rounded-md leading-relaxed shadow-sm max-w-xl border"
            :class="[
              conversation.sentBy === 1 ? 'bg-secondary': 'bg-white ml-auto'
            ]"
        >
          {{ conversation.content }}
        </h4>
      </div>

      <div class="border-t border-grey p-4 flex items-center gap-2">
        <button>
          <PlusIcon />
        </button>
        <input
            type="text"
            placeholder="Type a message ..."
            class="flex-1 px-4 py-2 rounded-sm text-h4 bg-white placeholder:"
        />
        <button>
          <SendIcon />
        </button>
      </div>
    </main>

    <aside class="bg-white border border-grey p-4 flex flex-col rounded-lg h-fit">
      <h4 class="flex justify-between items-center font-medium mb-4 text-primary">
        Lead Details
        <svg class="w-4 h-4 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path d="M19 9l-7 7-7-7" />
        </svg>
      </h4>
      <div class="flex flex-col items-left gap-3 mb-4">
        <img :src="currentSelectedLead?.avatar" alt="avatar" class="w-14 h-14 rounded-full object-cover" />
        <div>
          <h3 class="font-medium text-primary">{{ currentSelectedLead?.leadName }}</h3>
          <a href="mailto:jessica@kitty.com" class="text-h4 text-link underline">{{ currentSelectedLead?.email }}</a>
        </div>
      </div>

      <div class="text-gray-500 space-y-2">
        <div class="flex items-center justify-between">
          <h4>Store: </h4>
          <a :href="currentSelectedLead?.websiteUrl" target="_blank" class="text-h4 text-link hover:underline flex items-center gap-1">
            {{currentSelectedLead?.websiteUrl}}
            <ExternalLink />
          </a>
        </div>

        <div class="flex items-center justify-between">
          <h4 class="text-tertiary font-normal">Shopify Plan: </h4>
          <h4 class="text-primary font-normal">{{currentSelectedLead?.shopifyPlan}}</h4>
        </div>

        <div class="flex items-center justify-between">
          <h4 class="text-tertiary font-normal">Lead Type: </h4>
          <h4 class="text-primary font-normal">{{currentSelectedLead?.leadType}}</h4>
        </div>

        <div class="flex items-center justify-between">
          <h4 class="text-tertiary font-normal">Budget: </h4>
          <h4 class="text-primary font-normal">${{currentSelectedLead?.budget}}</h4>
        </div>

        <div class="flex items-center justify-between">
          <h4 class="text-tertiary font-normal">Conversation Started: </h4>
          <h4 class="text-primary font-normal">{{currentSelectedLead?.conversationStarted}}</h4>
        </div>

        <div class="flex items-center justify-between">
          <h4 class="text-tertiary ">Lead ID: </h4>
          <h4 class="text-primary font-normal">#{{currentSelectedLead?.leadId}}</h4>
        </div>

        <div class="pt-2 text-h4 text-tertiary">
          Lead Status:
          <div class="mt-1 border border-grey p-4 rounded-sm">
            <div class="flex items-center justify-between">
              <span class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span>
                <span class="font-medium text-h4 text-primary">{{currentSelectedLead?.leadStatus}}</span>
              </span>
              <DownArrow/>
            </div>
            <h6 class="text-tertiary">You are actively working on this project.</h6>
          </div>
        </div>
      </div>

      <button class="bg-primary text-white text-h4 font-medium py-2 px-4 rounded-sm flex items-center justify-center gap-2 mt-6">
        <QuoteLightIcon />
        Send a Project Quote
      </button>
      <button class="text-primary text-h4 border border-grey font-medium py-2 px-4 rounded-sm flex items-center justify-center gap-2 mt-2">
        <QuoteLightIcon />
        Release Project
      </button>
    </aside>
  </div>
</template>

<style scoped>

</style>