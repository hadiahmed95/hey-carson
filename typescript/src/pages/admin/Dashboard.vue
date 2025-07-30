<template>
  <main class="flex-1 p-8 overflow-y-auto bg-secondary font-light">
    <div class="flex flex-row mb-6 justify-between">
      <div>
        <h1>
          Welcome back, <span class="font-serif italic">Jonathan!</span>
        </h1>
        <p class="mt-1">
          This is overview of your shopexperts expert dashboard.
        </p>
      </div>

      <select v-model="range" class="border rounded px-1 w-36 py-2 text-h4 h-fit hover:bg-gray-100">
        <option value="current_week">Current Week</option>
        <option value="last_7_days">Last 7 Days</option>
        <option value="last_week">Last Week</option>
        <option value="last_30_days">Last 30 Days</option>
        <option value="last_month">Last Month</option>
        <option value="last_year">Last Year</option>
      </select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-14">
      <StatsCard title="Total Listings" :value="2782" />
      <StatsCard title="Leads" :value="23963" />
      <StatsCard title="Listing Visits" :value="769126" />
      <StatsCard title="Listing CTA Clicks" :value="472873" />
      <StatsCard title="Reviews" :value="9182" />
      <StatsCard title="Referrals" :value="219" />
      <StatsCard title="Referral Commission" :value="453186.32" />
    </div>

    <TabNav
      :tabs="tabs"
    />
  </main>
</template>

<script setup lang="ts">
import StatsCard from '../../components/common/cards/StatCard.vue'
import TabNav from "../../components/TabNav.vue";
import { ref } from "vue";
import PendingListings from "../../components/admin/PendingListings.vue";
import PendingReviews from "../../components/admin/PendingReviews.vue";

const range = ref('last_30_days')
const tabs = [
  { value: 'pending-listings', label: 'Pending Listings', component: PendingListings, count: 3 },
  { value: 'pending-reviews', label: 'Pending Reviews', component: PendingReviews, count: 3 },
]
</script>
