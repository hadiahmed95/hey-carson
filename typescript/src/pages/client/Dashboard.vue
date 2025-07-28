<template>
  <main class="flex-1 p-8 overflow-y-auto bg-secondary font-light">
    <div class="flex flex-row mb-14 justify-between">
      <div>
        <h1>
          Welcome back, <span class="italic font-besley">Filip!</span>
        </h1>
        <p class="mt-2">
          This is overview of your shopexperts client dashboard.
        </p>
      </div>

      <button
          class="flex bg-primary text-white font-light rounded-md h-fit py-2 px-4 items-center gap-2"
          @click="showRequestModal = true"
      >
        <span>Submit a Request</span>
        <Arrow />
      </button>
    </div>

    <TabNav
        :tabs="tabs"
    />

    <div class="space-y-14 mt-14">
      <FeaturedExpertCard :experts="clientStore.featuredServicesAndExperts.featured_experts" />
    </div>

    <RequestModal v-if="showRequestModal" @close="showRequestModal = false" />
  </main>
</template>

<script setup lang="ts">
import TabNav from "@/components/TabNav.vue";
import LatestRequest from "@/components/client/LatestRequest.vue";
import ReviewRequests from "@/components/client/ReviewRequests.vue";
import Arrow from "@/assets/icons/arrow.svg";
import {onMounted, ref} from "vue";
import { useClientStore } from "@/store/client.ts";
import FeaturedExpertCard from "@/components/client/cards/FeaturedExpertCard.vue";
import RequestModal from "@/components/client/modals/RequestModal.vue";

const clientStore = useClientStore();
const showRequestModal = ref(false);

onMounted(async () => {
  await clientStore.fetchFeaturedServicesAndExperts();
});

const tabs = [
  { value: 'latest-requests', label: 'Latest Requests', component: LatestRequest },
  { value: 'latest-reviews', label: 'Reviews Requests', component: ReviewRequests },
]
</script>
