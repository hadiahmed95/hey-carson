<template>
  <main class="flex-1 p-6 overflow-y-auto bg-secondary font-light">
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

    <LoadingCard :is-horizontal-direction="true" v-if="isLoading" />
    <div v-else class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <StatsCard title="Leads" :value="expertStore.stats?.expert_stats?.leads ?? 0" />
      <StatsCard title="CTA Clicks" :value="expertStore.stats?.expert_stats?.cta_clicks ?? 0" />
      <StatsCard title="Listing page visits" :value="expertStore.stats?.expert_stats?.listing_page_visits ?? 0" />
      <StatsCard title="Unique visits" :value="expertStore.stats?.expert_stats?.unique_visits ?? 0" />
    </div>

    <TabNav
        :tabs="tabs"
    />
  </main>
</template>

<script setup lang="ts">
import StatsCard from '../../components/common/cards/StatCard.vue'
import TabNav from "../../components/TabNav.vue";
import LatestLeads from "../../components/expert/LatestLeads.vue";
import {ref, computed, onMounted} from "vue";
import YourReviews from "../../components/expert/YourReviews.vue";
import LoadingCard from "@/components/common/LoadingCard.vue";
import {useLoaderStore} from "@/store/loader.ts";
import {useExpertStore} from "@/store/expert.ts";

const loader = useLoaderStore();
const isLoading = computed(() => loader.isLoadingState);
const range = ref('last_30_days')
const expertStore = useExpertStore();
const tabs = [
  { value: 'latest-leads', label: 'Latest Leads', component: LatestLeads },
  { value: 'latest-reviews', label: 'Latest Reviews', component: YourReviews },
]

onMounted(async () => {
  await expertStore.fetchStats()
})
</script>
