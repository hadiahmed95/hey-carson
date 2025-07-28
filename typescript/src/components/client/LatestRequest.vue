<template>
  <LoadingCard v-if="isLoading" />
  <EmptyDataPlaceholder title="Looks like you haven’t created any requests yet" v-else-if="clientStore.latestRequests.latest_requests?.length === 0"/>
  <div
      v-else
      class="space-y-14"
      v-for="(request, index) in clientStore.latestRequests.latest_requests"
      :key="index"
  >
    <ExpertMatchedRequestCard
        v-if="request.type === 'Matched'"
        :request="request"
        class="flex flex-col gap-3"
    />
    <ExpertDirectMessageCard
        v-else-if="request.type === 'Direct Message'"
        :request="request"
        class="flex flex-col gap-3"
    />
    <ExpertQuoteRequestCard
        v-else-if="request.type === 'Quote Request'"
        :request="request"
        class="flex flex-col gap-3"
    />
  </div>
  <PaymentModal v-if="showPaymentModal" @close="showPaymentModal = false"/>
</template>

<script setup lang="ts">
import ExpertDirectMessageCard from "./cards/ExpertDirectMessageCard.vue";
import ExpertQuoteRequestCard from "./cards/ExpertQuoteRequestCard.vue";
import {computed, onMounted, ref} from "vue";
import { useClientStore } from "@/store/client.ts";
import ExpertMatchedRequestCard from "@/components/client/cards/ExpertMatchedRequestCard.vue";
import LoadingCard from "@/components/common/LoadingCard.vue";
import {useLoaderStore} from "@/store/loader.ts";
import EmptyDataPlaceholder from "@/components/common/EmptyDataPlaceholder.vue";
import PaymentModal from "@/components/client/modals/PaymentModal.vue";

const loader = useLoaderStore();
const showPaymentModal = ref(false);
const isLoading = computed(() => loader.isLoadingState);

const clientStore = useClientStore();

onMounted(async () => {
  await clientStore.fetchLatestRequests();
});
</script>
