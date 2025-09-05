<script setup lang="ts">
import EmptyState from "@/components/expert/listing-settings/EmptyState.vue";
import FAQ from "@/assets/icons/faq.svg";
import GreenPlus from "@/assets/icons/green-plus.svg";
import LeftIconButton from "@/components/common/buttons/LeftIconButton.vue";
import FaqCard from "@/components/expert/cards/FaqCard.vue";
import BaseModal from "@/components/expert/BaseModal.vue";
import AddFaqForm from "@/components/expert/forms/AddFaqForm.vue";
import {ref} from "vue";

const showAddFaqModal = ref(false);
const questionsList = ref([
  {
    id: 1,
    title: "What kind of results can you expect from working with Blue Horizons?",
    answer: "",
  },
  {
    id: 2,
    title: "Do we work with both small businesses and established brands?",
    answer: "",
  },
  {
    id: 3,
    title: "How do we create a marketing strategy for my store?",
    answer: "",
  },
  {
    id: 4,
    title: "What’s our typical pricing structure?",
    answer: "",
  },
  {
    id: 5,
    title: "How do we measure success?",
    answer: "",
  },
]);

// Close modal
const closeModal = () => {
  showAddFaqModal.value = false;
};
</script>

<template>
  <main class="w-full">
    <div class="flex justify-between items-center mb-3">
      <h3 class="font-semibold">
        FAQ
      </h3>
      <LeftIconButton :icon="GreenPlus" @click="showAddFaqModal = true">Add FAQ</LeftIconButton>
    </div>

    <EmptyState class="w-full" :icon="FAQ" v-if="!questionsList?.length" content="No customer stories yet - but your past work matters! Show your skills<br/> in action, like “Shopify redesign that boosted sales by 35%."/>


    <FaqCard
      v-for="question in questionsList"
      :key="question.id"
      :question="question"
    />

    <!-- Add FAQ Modal -->

    <BaseModal
      v-if="showAddFaqModal"
      @close="closeModal"
      title="Add FAQ"
      description="Answer common client questions about your workflow, payments, tools, or process and save your time."
      :form="AddFaqForm"
      :isShowQuestionSection="false"
      :isShowRecaptchaSection="false"
    />
  </main>
</template>