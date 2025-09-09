<script setup lang="ts">
import EmptyState from "@/components/expert/listing-settings/EmptyState.vue";
import GreenPlus from "@/assets/icons/green-plus.svg";
import LeftIconButton from "@/components/common/buttons/LeftIconButton.vue";
import StoriesCard from "@/components/expert/cards/StoriesCard.vue";
import BaseModal from "@/components/expert/BaseModal.vue";
import AddCustomerStoryForm from "@/components/expert/forms/AddCustomerStoryForm.vue";
import LoadingCard from "@/components/common/LoadingCard.vue";
import { ref, onMounted, computed } from "vue";
import { useExpertStore } from "@/store/expert.ts";
import { useLoaderStore } from "@/store/loader.ts";
import type { ExpertStories } from '@/types.ts';
import Box from "@/assets/icons/box.svg";

const expertStore = useExpertStore();
const loader = useLoaderStore();

const showAddStoryModal = ref(false);
const currentStoryData = ref<ExpertStories | null>(null);
const customerStories = computed(() => {
  return expertStore.customerStories || [];
});
const isLoading = computed(() => loader.isLoadingState);

onMounted(async () => {
  await expertStore.fetchCustomerStories();
});

// Handle edit story
const handleEditStory = (story: ExpertStories) => {
  currentStoryData.value = story;
  showAddStoryModal.value = true;
};

// Handle delete story
const handleDeleteStory = async (storyId: number) => {
  try {
    await expertStore.deleteCustomerStory(storyId);
  } catch (error) {
    console.error('Error deleting customer story:', error);
  }
};

// Close modal and reset data
const closeModal = () => {
  showAddStoryModal.value = false;
  currentStoryData.value = null;
};
</script>

<template>
  <main class="w-full">
    <div class="flex justify-between items-center mb-3">
      <h3 class="font-semibold">
        Customer Stories
      </h3>
      <LeftIconButton :icon="GreenPlus" @click="showAddStoryModal = true">Add Customer Story</LeftIconButton>
    </div>

    <LoadingCard v-if="isLoading" />
    <EmptyState v-else-if="!customerStories.length" class="w-full" :icon="Box" content="No customer stories yet - but showcasing your success builds trust!<br/>Add stories about challenges you solved and the amazing results you delivered."/>
    <StoriesCard
        v-else
        v-for="story in customerStories"
        :key="story.id"
        :project="story"
        @edit="handleEditStory"
        @delete="handleDeleteStory"
    />

    <!-- Add / Edit Customer Story Modal -->
    <BaseModal
      v-if="showAddStoryModal"
      @close="closeModal"
      :title="currentStoryData ? 'Edit Customer Story' : 'Add Customer Stories'"
      :description="currentStoryData ? `Update your customer success story to showcase your expertise and build trust.` : `Highlight your impact by sharing a client's challenge, your solution, and the results.`"
      :form="AddCustomerStoryForm"
      :custom-props="{ storyData: currentStoryData }"
      :isShowQuestionSection="false"
      :isShowRecaptchaSection="false"
    />
  </main>
</template>
