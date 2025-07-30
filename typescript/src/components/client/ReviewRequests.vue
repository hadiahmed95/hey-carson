<template>
  <LoadingCard v-if="isLoading" />
  <EmptyDataPlaceholder title="Looks like you don’t have any review requests yet" v-else-if="reviewRequests?.length === 0"/>
  <main v-else-if="reviewRequests.length">
    <ReviewCard
        v-for="(review, index) in reviewRequests"
        :key="index"
        :review="review"
        isClient
        isReviewRequest
    />
  </main>
</template>

<script setup lang="ts">
import { onMounted, computed } from "vue";
import { useClientStore } from "@/store/client.ts";
import ReviewCard from "../common/cards/ReviewCard.vue";
import type { IReview } from "@/types.ts";
import LoadingCard from "@/components/common/LoadingCard.vue";
import {useLoaderStore} from "@/store/loader.ts";
import EmptyDataPlaceholder from "@/components/common/EmptyDataPlaceholder.vue";

const loader = useLoaderStore();
const isLoading = computed(() => loader.isLoadingState);

const clientStore = useClientStore();

// Fetch data on mount
onMounted(async () => {
  await clientStore.fetchReviewRequests();
});

// Computed to safely access the array
const reviewRequests = computed<IReview[]>(() =>
    clientStore.reviewRequests?.pending_review_requests || []
);
</script>
