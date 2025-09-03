<script setup lang="ts">
import EmptyState from "@/components/expert/listing-settings/EmptyState.vue";
import FAQ from "@/assets/icons/faq.svg";
import GreenPlus from "@/assets/icons/green-plus.svg";
import LeftIconButton from "@/components/common/buttons/LeftIconButton.vue";
import FaqCard from "@/components/expert/cards/FaqCard.vue";
import {ref} from "vue";

const questionsList = ref([
  {
    id: 1,
    title: "What kind of results can you expect from working with Blue Horizons?",
  },
  {
    id: 2,
    title: "Do we work with both small businesses and established brands?",
  },
  {
    id: 3,
    title: "How do we create a marketing strategy for my store?",
  },
  {
    id: 4,
    title: "What’s our typical pricing structure?",
  },
  {
    id: 5,
    title: "How do we measure success?",
  },
]);

</script>

<template>
  <main class="w-full">
    <div class="flex justify-between items-center mb-3">
      <h3 class="font-semibold">
        FAQ
      </h3>
      <LeftIconButton :icon="GreenPlus">Add FAQ</LeftIconButton>
    </div>

    <EmptyState class="w-full" :icon="FAQ" v-if="!questionsList?.length" content="No customer stories yet - but your past work matters! Show your skills<br/> in action, like “Shopify redesign that boosted sales by 35%."/>


    <FaqCard
      v-for="question in questionsList"
      :key="question.id"
      :question="question"
    />

  </main>
</template>