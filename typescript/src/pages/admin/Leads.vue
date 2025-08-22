<script setup lang="ts">

import { ref } from "vue";
import LeadCard from "../../components/admin/cards/LeadCard.vue";
import Search from "../../assets/icons/search.svg";


const shopifyPlan = ref('')
const searchQuery = ref('')

const leads = [
  {
    id: 1,
    name: 'Jacob Jones',
    displayUrl: 'https://randomuser.me/api/portraits/men/32.jpg',
    website: 'www.suta.co',
    email: 'jacob@suta.co',
    plan: 'Advance',
    directChatCount: 0,
    quoteRequestCount: 0,
    lifetimeSpendCount: '$0.00',
    joinedOn: '17 Dec, 2025',
  },
  {
    id: 2,
    name: 'Cody Fisher',
    displayUrl: 'https://randomuser.me/api/portraits/men/32.jpg',
    website: 'www.goodfair.com',
    email: 'codyfister@goodfair.com',
    plan: 'Plus',
    directChatCount: 280,
    quoteRequestCount: 50,
    lifetimeSpendCount: '$48,285.00',
    joinedOn: '17 Dec, 2025',
  },
  {
    id: 3,
    name: 'Jacob Jones',
    displayUrl: 'https://randomuser.me/api/portraits/men/32.jpg',
    website: 'www.suta.co',
    email: 'j.bell@adoredvintage.com',
    plan: 'Basic',
    directChatCount: 3,
    quoteRequestCount: 25,
    lifetimeSpendCount: '$10,670.00',
    joinedOn: '17 Dec, 2025',
  },
  {
    id: 4,
    name: 'Jacob Jones',
    displayUrl: 'https://randomuser.me/api/portraits/men/32.jpg',
    website: 'www.suta.co',
    email: 'marvinmc@theoutrage.com',
    plan: 'Advance',
    directChatCount: 6,
    quoteRequestCount: 43,
    lifetimeSpendCount: '$200.00',
    joinedOn: '17 Dec, 2025',
  },
  {
    id: 5,
    name: 'Jacob Jones',
    displayUrl: 'https://randomuser.me/api/portraits/men/32.jpg',
    website: 'www.suta.co',
    email: 'floyd@maguireshoes.com',
    plan: 'Plus',
    directChatCount: 32,
    quoteRequestCount: 10,
    lifetimeSpendCount: '$650.00',
    joinedOn: '17 Dec, 2025',
  },
  {
    id: 6,
    name: 'Jacob Jones',
    displayUrl: 'https://randomuser.me/api/portraits/men/32.jpg',
    website: 'www.suta.co',
    email: 'jenny.wilson@tentree.com',
    plan: 'Plus',
    directChatCount: 0,
    quoteRequestCount: 0,
    lifetimeSpendCount: '$0.00',
    joinedOn: '17 Dec, 2025',
  },
  {
    id: 7,
    name: 'Jacob Jones',
    displayUrl: 'https://randomuser.me/api/portraits/men/32.jpg',
    website: 'www.suta.co',
    email: 'annette.black@hiutdenim.co',
    plan: 'Plus',
    directChatCount: 0,
    quoteRequestCount: 0,
    lifetimeSpendCount: '$0.00',
    joinedOn: '17 Dec, 2025',
  },
]
</script>

<template>
  <main class="flex-1 p-8 overflow-y-auto bg-secondary font-light">
    <div class="flex flex-row justify-between mb-4">
      <div>
        <h1>
          Leads <span class="text-gray-500">({{ leads.length }})</span>
        </h1>
      </div>

      <div class="flex items-center border border-grey rounded-sm bg-white py-1 px-3 w-[300px] max-w-md shadow-sm">
        <Search />
        <input
            type="text"
            placeholder="Search Leads ..."
            class="w-full ml-3 text-h4 outline-none placeholder-tertiary"
            v-model="searchQuery"
        />
      </div>
    </div>

    <p class="mt-1 space-x-3 mb-8">
      <select v-model="shopifyPlan" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Shopify Plan: All</option>
        <option value="In Progress">In Progress</option>
        <option value="Completed">Completed</option>
        <option value="Closed">Closed</option>
      </select>
    </p>

    <div>
      <LeadCard
          v-for="lead in leads"
          :key="lead.id"
          :lead="lead"
      />
    </div>
    
  </main>
</template>
