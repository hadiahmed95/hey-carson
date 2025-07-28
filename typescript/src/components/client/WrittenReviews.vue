<template>
  <main>
    <LoadingCard v-if="isLoading" />
    <EmptyDataPlaceholder title="Looks like you don’t have any written reviews yet" v-else-if="writtenReviews?.length === 0"/>
    <div v-else-if="writtenReviews.length">
      <ReviewCard
          v-for="(review, index) in writtenReviews"
          :key="`${review.reviewer.id}-${index}`"
          :review="review"
          isClient
          isWrittenReview
      />
    </div>
  </main>
</template>

<script setup lang="ts">
import { onMounted, computed } from "vue";
import { useClientStore } from "@/store/client.ts";
import ReviewCard from "../common/cards/ReviewCard.vue";
import LoadingCard from "@/components/common/LoadingCard.vue";
import {useLoaderStore} from "@/store/loader.ts";
import EmptyDataPlaceholder from "@/components/common/EmptyDataPlaceholder.vue";

const loader = useLoaderStore();
const isLoading = computed(() => loader.isLoadingState);

const clientStore = useClientStore();

onMounted(async () => {
  await clientStore.fetchReviews();
});

const writtenReviews = computed(() =>
    clientStore.reviews?.written_reviews ?? []
);
</script>
