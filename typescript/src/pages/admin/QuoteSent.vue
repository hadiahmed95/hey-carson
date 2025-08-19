<script setup lang="ts">
import type { IQuote } from '../../types.ts'
import { ref, computed } from 'vue'
import QuoteSentCard from "../../components/admin/cards/QuoteSentCard.vue";
import Search from "../../assets/icons/search.svg"

const quoteStatus = ref('')
const searchQuery = ref('')

const quotes = ref<IQuote[]>([
  {
    title: 'Remove SKU from Shopify code',
    link: 'https://www.sutacommerce.co',
    hourlyRate: '$95/hour',
    estimatedTime: '10 hours',
    deadline: '17 Dec, 2025',
    total: '$950.00',
    status: 'Pending Payment',
    sentDate: 'Dec 10, 2025',
    client: {
      name: 'Eleanor Pena',
      email: 'elenor@suta.co',
      avatar: 'https://randomuser.me/api/portraits/women/43.jpg',
      plan: 'Plus',
    },
    expert: {
      name: 'Jerome Bell',
      email: 'jeromebell@belldev.com',
      avatar: 'https://randomuser.me/api/portraits/men/44.jpg',
    },
  },
  {
    title: 'Countdown timer on product and password page',
    link: 'https://www.sutacommerce.co',
    hourlyRate: '$95/hour',
    estimatedTime: '10 hours',
    deadline: '17 Dec, 2025',
    total: '$950.00',
    status: 'Rejected',
    sentDate: 'Dec 10, 2025',
    rejectedDate: 'Dec 13, 2025',
    client: {
      name: 'Jacob Jones',
      email: 'jacob@suta.co',
      avatar: 'https://randomuser.me/api/portraits/men/33.jpg',
      plan: 'Advanced',
    },
    expert: {
      name: 'Darrell Steward',
      email: 'darrellsteward@gmail.com',
      avatar: 'https://randomuser.me/api/portraits/men/55.jpg',
    },
  },
  {
    title: 'Help with editing and optimizing header structure on shopify homepage',
    link: 'https://www.sutacommerce.co',
    hourlyRate: '$95/hour',
    estimatedTime: '10 hours',
    deadline: '17 Dec, 2025',
    total: '$950.00',
    status: 'Paid',
    sentDate: 'Dec 10, 2025',
    paidDate: 'Dec 11, 2025',
    client: {
      name: 'Albert Flores',
      email: 'albert@unitedbyblue.com',
      avatar: 'https://randomuser.me/api/portraits/men/22.jpg',
      plan: 'Plus',
    },
    expert: {
      name: 'Guy Hawkins',
      email: 'markusrasedev@yahoo.com',
      avatar: 'https://randomuser.me/api/portraits/men/41.jpg',
    },
  },
])

const filteredQuotes = computed(() => {
  return quotes.value.filter((q) => {
    const matchesStatus = !quoteStatus.value || q.status === quoteStatus.value
    const matchesSearch =
        !searchQuery.value ||
        q.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        q.client.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    return matchesStatus && matchesSearch
  })
})
</script>

<template>
  <main class="flex-1 p-8 overflow-y-auto bg-gray-50 font-light">
    <div class="flex justify-between items-center mb-4">
      <h1>
        Quotes Sent <span class="text-gray-500">({{ quotes.length }})</span>
      </h1>
      <div class="flex items-center border border-grey rounded-sm bg-white py-1 px-3 w-[300px] max-w-md shadow-sm">
        <Search />
        <input
            type="text"
            placeholder="Search Quotes ..."
            class="w-full ml-3 text-h4 outline-none placeholder-tertiary"
            v-model="searchQuery"
        />
      </div>
    </div>

    <div class="flex items-center space-x-3 mb-8">
      <select v-model="quoteStatus" class="border border-grey w-[155px] rounded-sm px-3 py-1 text-h4 bg-white">
        <option value="">Quote Status: All</option>
        <option value="Pending Payment">Pending Payment</option>
        <option value="Rejected">Rejected</option>
        <option value="Paid">Paid</option>
      </select>
    </div>

    <div class="space-y-4">
      <QuoteSentCard v-for="(quote, index) in filteredQuotes" :key="index" :quote="quote" />
    </div>
  </main>
</template>
