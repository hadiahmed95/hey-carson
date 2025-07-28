<template>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <div>
      <h3 class="font-semibold mb-2">FAQ</h3>
      <h4 class="mb-4">
        Based on your plan, you can select 1 category and up to 3
        <br />subcategories within the selected service category.
      </h4>
      <h4>
        Upgrade your plan if you want to select up to 3 categories
        <br />and 6 subcategories.
      </h4>
    </div>

    <div class="md:col-span-2 space-y-4">
      <QuestionCard
          v-for="question in questions"
          :key="question.id"
          :question="question"
      />
    </div>
  </div>
</template>

<script setup lang="ts">

import {ref} from "vue";
import QuestionCard from "./cards/QuestionCard.vue";

const questions = ref([
  {
    id: 1,
    title: "How do I price my services, and what payment methods do I accept?",
    answer: "I price my services based on the complexity of the project. I offer fixed-price quotes for specific tasks and hourly rates for ongoing support. Payments can be made via Shopify Payments, PayPal, or bank transfer.",
  },
  {
    id: 2,
    title: "How do I communicate with clients during a project?",
    answer: " I keep communication clear and efficient, primarily using email and Slack. For larger projects, I also set up Trello or Asana boards to track progress. I provide regular updates to ensure everything stays on track.",
  },
  {
    id: 3,
    title: "Do I offer revisions or ongoing support after a project is completed?",
    answer: "Yes! I include a set number of revisions based on the project scope. Once the project is completed, I offer 30 days of free support to ensure everything runs smoothly. For ongoing work, I also provide flexible hourly support packages.",
  },
]);

</script>
