<script setup lang="ts">
import EmptyState from "@/components/expert/listing-settings/EmptyState.vue";
import FAQ from "@/assets/icons/faq.svg";
import GreenPlus from "@/assets/icons/green-plus.svg";
import LeftIconButton from "@/components/common/buttons/LeftIconButton.vue";
import FaqCard from "@/components/expert/cards/FaqCard.vue";
import BaseModal from "@/components/expert/BaseModal.vue";
import AddFaqForm from "@/components/expert/forms/AddFaqForm.vue";
import LoadingCard from "@/components/common/LoadingCard.vue";
import { ref, onMounted, computed } from "vue";
import { useExpertStore } from "@/store/expert.ts";
import { useLoaderStore } from "@/store/loader.ts";
import type { ExpertFaq } from '@/types.ts';

const expertStore = useExpertStore();
const loader = useLoaderStore();

const showAddFaqModal = ref(false);
const currentFaqData = ref<ExpertFaq | null>(null);
const questionsList = computed(() => {
  return expertStore.faqs || [];
});
const isLoading = computed(() => loader.isLoadingState);

onMounted(async () => {
  await expertStore.fetchFaqs();
});

const handleEditFaq = (faq: ExpertFaq) => {
  currentFaqData.value = faq;
  showAddFaqModal.value = true;
};

const handleDeleteFaq = async (faqId: number) => {
  try {
    await expertStore.deleteFaq(faqId);
  } catch (error) {
    console.error('Error deleting FAQ:', error);
  }
};

// Close modal
const closeModal = () => {
  showAddFaqModal.value = false;
  currentFaqData.value = null;
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

    <!-- Loading state -->
    <LoadingCard v-if="isLoading" />

    <EmptyState class="w-full" :icon="FAQ" v-else-if="!questionsList?.length" content="No FAQs yet - but answering common questions upfront saves time!<br/>Add questions like 'What's your typical turnaround time?' or 'How do you handle revisions?'"/>

    <FaqCard
      v-else
      v-for="question in questionsList"
      :key="question.id"
      :question="question"
      @edit="handleEditFaq"
      @delete="handleDeleteFaq"
    />

    <!-- Add / Edit FAQ Modal -->
    <BaseModal
      v-if="showAddFaqModal"
      @close="closeModal"
      :title="currentFaqData ? 'Edit FAQ' : 'Add FAQ'"
      :description="currentFaqData ? 'Update your FAQ to provide better information to potential clients.' : 'Answer common client questions about your workflow, payments, tools, or process and save your time.'"
      :form="AddFaqForm"
      :custom-props="{ faqData: currentFaqData }"
      :isShowQuestionSection="false"
      :isShowRecaptchaSection="false"
    />
  </main>
</template>