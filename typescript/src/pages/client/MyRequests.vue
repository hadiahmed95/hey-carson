<template>
  <div class="flex-1 overflow-y-auto font-light bg-secondary p-8">
    <!-- Header Section -->
    <div class="flex justify-between mb-8">
      <div>
        <h1 class="font-sm text-primary">My Requests</h1>
        <p class="mt-2 text-primary font-sm">
          Easily manage all your project requests in one place.<br/>Stay organized and move your projects forward faster.
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

    <LoadingCard v-if="isLoading" />
    <EmptyDataPlaceholder title="Looks like you haven’t created any requests yet" v-else-if="clientStore.requests.requests?.length === 0"/>
    <div
        v-else
        v-for="(request, index) in clientStore.requests.requests"
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
      <div v-else>fdafas</div>
    </div>

    <RequestModal v-if="showRequestModal" @close="showRequestModal = false" my-requests-page />
  </div>
</template>

<script setup lang="ts">
import Arrow from "@/assets/icons/arrow.svg";
import ExpertDirectMessageCard from "@/components/client/cards/ExpertDirectMessageCard.vue";
import ExpertQuoteRequestCard from "@/components/client/cards/ExpertQuoteRequestCard.vue";
import {computed, ref} from "vue";
import ExpertMatchedRequestCard from "@/components/client/cards/ExpertMatchedRequestCard.vue";
import { useClientStore } from "@/store/client.ts";
import {onMounted} from "vue";
import {useLoaderStore} from "@/store/loader.ts";
import LoadingCard from "@/components/common/LoadingCard.vue";
import RequestModal from "@/components/client/modals/RequestModal.vue";
import EmptyDataPlaceholder from "@/components/common/EmptyDataPlaceholder.vue";

const loader = useLoaderStore();
const isLoading = computed(() => loader.isLoadingState);
const showRequestModal = ref(false);
const clientStore = useClientStore();

onMounted(async () => {
  await clientStore.fetchRequests();
});
</script>
