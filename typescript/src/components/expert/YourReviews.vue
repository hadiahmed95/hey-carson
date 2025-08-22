<template>
  <div class="pb-6 mt-14">
    <h3 class="font-semibold pb-2">Your Reviews</h3>
    <h4>Check what reviews potential clients will see on your profile.</h4>
  </div>

  <div>
    <LoadingCard v-if="isLoading" />
    <EmptyDataPlaceholder :is-reviews-page="true" title="Looks like you don't have any reviews yet" v-else-if="reviews?.length === 0"/>
    <ReviewCard v-else
      v-for="(review, index) in reviews"
      :key="index"
      :review="review"
      isExpert
    />
  </div>
</template>

<script setup lang="ts">

import {computed, onMounted} from "vue";
import ReviewCard from "../common/cards/ReviewCard.vue";
import {useExpertStore} from "@/store/expert.ts";
import {useLoaderStore} from "@/store/loader.ts";
import LoadingCard from "@/components/common/LoadingCard.vue";
import EmptyDataPlaceholder from "@/components/common/EmptyDataPlaceholder.vue";

const loader = useLoaderStore();
const isLoading = computed(() => loader.isLoadingState);
const expertStore = useExpertStore();

onMounted(async () => {
  await expertStore.fetchReviews();
})

const reviews = computed(() =>
    expertStore.reviews?.reviews ?? []
);

</script>
