<script setup lang="ts">

import TabNav from "../../components/TabNav.vue";
import WrittenReviews from "../../components/client/WrittenReviews.vue";
import ReviewRequests from "../../components/client/ReviewRequests.vue";

const tabs = [
  { value: 'review-requests', label: 'Review Requests', component: ReviewRequests },
  { value: 'written-reviews', label: 'Published Reviews', component: WrittenReviews },
]


</script>

<template>
  <main class="flex-1 p-8 overflow-y-auto bg-secondary font-light">
    <div class="flex flex-row justify-between mb-12">
      <div>
        <h1 class="mb-2">
          Reviews
        </h1>
        <p class="text-primary font-sm">
          Share your experience with experts both on and off the platform.<br/>Find the right experts for your needs and leave great reviews when they do a great job.
        </p>
      </div>
    </div>
    <TabNav :tabs="tabs"/>
  </main>
</template>
