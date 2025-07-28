<script setup lang="ts">

import { ref, computed } from 'vue'
import Search from "../../assets/icons/search.svg"
import ReferralCard from "../../components/admin/cards/ReferralCard.vue";

const referralStatus = ref('')
const shopifyPlan = ref('')
const searchQuery = ref('')

import type { IReferral } from '../../types.ts'

const referrals = ref<IReferral[]>( [
  {
    referrer: {
      name: 'Robert Fox',
      email: 'jeromebell@belldev.com',
      avatar: 'https://randomuser.me/api/portraits/men/32.jpg',
    },
    referral: {
      name: 'Dianne Russell',
      email: 'elenor@suta.co',
      avatar: 'https://randomuser.me/api/portraits/men/32.jpg',
      shopifyPlan: 'Plus',
    },
    createdAccount: '17 Dec, 2025',
    completedProject: '09 May, 2026',
    amount: '30.00',
    status: 'Pending Review',
    referredOn: 'Dec 10, 2025',
    approvedOn: null,
    rejectedOn: null,
  },
  {
    referrer: {
      name: 'Annette Black',
      email: 'jeromebell@belldev.com',
      avatar: 'https://randomuser.me/api/portraits/men/32.jpg',
    },
    referral: {
      name: 'Devon Lane',
      email: 'elenor@suta.co',
      avatar: 'https://randomuser.me/api/portraits/men/32.jpg',
      shopifyPlan: 'Basic',
    },
    createdAccount: '17 Dec, 2025',
    completedProject: '09 May, 2026',
    amount: '15.00',
    status: 'Approved',
    referredOn: 'Dec 10, 2025',
    approvedOn: 'May 14, 2026',
    rejectedOn: null,
  },
  {
    referrer: {
      name: 'Leslie Alexander',
      email: 'elenor@suta.co',
      avatar: 'https://randomuser.me/api/portraits/men/32.jpg',
    },
    referral: {
      name: 'Jane Cooper',
      email: 'elenor@suta.co',
      avatar: 'https://randomuser.me/api/portraits/men/32.jpg',
      shopifyPlan: 'Plus',
    },
    createdAccount: '17 Dec, 2025',
    completedProject: '09 May, 2026',
    amount: '30.00',
    status: 'Rejected',
    referredOn: 'Dec 10, 2025',
    approvedOn: null,
    rejectedOn: 'May 14, 2026',
  },
])



const filteredReferrals = computed(() => {
  return referrals.value.filter((referral) => {
    const matchesStatus = !referralStatus.value || referral.status === referralStatus.value
    const matchesShopifyPlan = !shopifyPlan.value || referral.status === shopifyPlan.value
    const matchesSearch =
      !searchQuery.value ||
      referral.referrer.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      referral.referral.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      referral.referrer.email.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      referral.referral.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    return matchesStatus && matchesSearch && matchesShopifyPlan
  })
})
</script>

<template>
  <main class="flex-1 p-8 overflow-y-auto bg-gray-50 font-light space-y-8">
    <div class="flex justify-between items-center">
      <h1>
        Referrals <span class="text-tertiary">({{ referrals.length }})</span>
      </h1>
      <div class="flex items-center border border-grey rounded-sm bg-white py-1 px-3 w-[300px] max-w-md shadow-sm">
        <Search />
        <input
            type="text"
            placeholder="Search Referrals ..."
            class="w-full ml-3 text-h4 outline-none placeholder-tertiary"
            v-model="searchQuery"
        />
      </div>
    </div>

    <div class="flex items-center space-x-3">
      <select v-model="referralStatus" class="border border-grey w-[155px] rounded-sm px-3 py-1 text-h4 bg-white">
        <option value="">Status: All</option>
        <option value="Pending Review">Pending Review</option>
        <option value="Approved">Approved</option>
        <option value="Rejected">Rejected</option>
      </select>

      <select v-model="shopifyPlan" class="border border-grey w-[155px] rounded-sm px-3 py-1 text-h4 bg-white">
        <option value="">Shopify Plan: All</option>
        <option value="Lite">Lite</option>
        <option value="Basic">Basic</option>
        <option value="Shopify">Shopify</option>
        <option value="Advanced">Advanced</option>
        <option value="Plus">Plus</option>
      </select>
    </div>

    <div class="space-y-4">
      <ReferralCard v-for="(referral, index) in filteredReferrals" :key="index" :referral="referral" />
    </div>
  </main>
</template>
