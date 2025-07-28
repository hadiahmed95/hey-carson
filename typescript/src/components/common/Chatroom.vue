<script setup lang="ts">
import {ref} from "vue";
import SendIcon from "../../assets/icons/send.svg";
import PlusIcon from "../../assets/icons/plus.svg";

const currentSelectedLead = ref({
  leadName: 'Mariah B.',
  email: 'jessica@kitty.com',
  avatar: 'https://randomuser.me/api/portraits/women/91.jpg',
  conversation: [
    {
      content: 'Hey Jonathan,<br/><br/>' +
          'I saw your profile in the Shopexperts directory, and we have a really interesting ' +
          'project that I think you might be interested in. Let’s discuss the details when you ' +
          'have some free time.<br/><br/>' +
          'To give you a brief overview: We’re planning to build a Shopify visual builder, ' +
          'something similar to Instant but with many more AI features, and it will be free ' +
          'for users.<br/><br/>' +
          'If this sounds interesting, let’s schedule a call early next week to discuss further.<br/>' +
          'Thank you!',
      sentBy: 1,
      sentAt: '08:30 PM',
    },
    {
      content: "Hey Jessica,<br/><br/>" +
          "This sounds really interesting to me! " +
          "I'd definitely love to know more details.<br/><br/>" +
          "I'm on a family holiday this week and next, " +
          "so could you please schedule a meeting after May 23rd? " +
          "I hope that’s not a problem for you!<br/>" +
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
},)
</script>

<template>
  <main class="flex flex-col gap-6 bg-white rounded-lg border border-grey">
    <div class="flex items-center px-4 py-3">
      <div class="flex-grow h-px bg-gray-300"></div>
      <h5 class="px-4 text-gray-500 whitespace-nowrap text-sm">
        Wednesday, 18 May, 2025
      </h5>
      <div class="flex-grow h-px bg-gray-300"></div>
    </div>
    <div class="flex flex-col p-6 gap-6">
      <h4
          v-for="(conversation, index) in currentSelectedLead.conversation"
          :key="index"
          class="w-2/3 p-4 rounded-md leading-relaxed shadow-sm max-w-xl border"
          :class="[
              conversation.sentBy === 1 ? 'bg-secondary': 'bg-white ml-auto'
            ]"
          v-html="conversation.content"
      >
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
</template>

<style scoped>

</style>