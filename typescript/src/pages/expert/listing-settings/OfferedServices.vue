<template>
  <main class="w-full">
    <div class="flex justify-between items-center mb-3">
      <h3 class="font-semibold">
        Offered Services
      </h3>
      <LeftIconButton 
        :icon="GreenPlus"
        @click="showAddServiceModal = true"
      >
        Add Service
      </LeftIconButton>
    </div>

    <LoadingCard v-if="isLoading" />
    <EmptyState v-else-if="!offeredServices.length" class="w-full" :icon="Box" content="No offered services yet - but showcasing your expertise builds trust!<br/>Add services to highlight what you can do for clients."/>
    <ServiceCategoryCard
        v-else
        v-for="category in offeredServices"
        :key="category.id"
        :category="category"
        @edit="handleEditService"
        @delete="handleDeleteService"
    />

    <!-- Add Service Modal -->
    <BaseModal
      v-if="showAddServiceModal"
      @close="closeModal"
      :title="currentServiceData ? 'Edit Offered Service' : 'Add Offered Service'"
      :description="currentServiceData ? 'Update a service category that best reflects your expertise. Add up to 3 subservices to show your strengths.' : 'Choose a service category that best reflects your expertise. Add up to 3 subservices to show your strengths.'"
      :form="AddServiceForm"
      :custom-props="{ serviceData: currentServiceData }"
      :isShowQuestionSection="false"
      :isShowRecaptchaSection="false"
    />
  </main>
</template>

<script setup lang="ts">
import EmptyState from "@/components/expert/listing-settings/EmptyState.vue";
import Box from "@/assets/icons/box.svg";
import GreenPlus from "@/assets/icons/green-plus.svg";
import LeftIconButton from "@/components/common/buttons/LeftIconButton.vue";
import LoadingCard from "@/components/common/LoadingCard.vue";
import ServiceCategoryCard from "@/components/expert/cards/ServiceCategoryCard.vue";
import BaseModal from "@/components/expert/BaseModal.vue";
import AddServiceForm from "@/components/expert/forms/AddServiceForm.vue";
import { ref, onMounted, computed } from "vue";
import { useExpertStore } from "@/store/expert.ts";
import { useLoaderStore } from "@/store/loader.ts";
import type { ExpertServiceCategory } from '@/types.ts';

const expertStore = useExpertStore();
const loader = useLoaderStore();

const showAddServiceModal = ref(false);
const currentServiceData = ref<ExpertServiceCategory | null>(null);
const offeredServices = computed(() => {
  return expertStore.offeredServices || [];
});
const isLoading = computed(() => loader.isLoadingState);

onMounted(async () => {
  await expertStore.fetchOfferedServices();
});

// Handle edit service
const handleEditService = (service: ExpertServiceCategory) => {
  currentServiceData.value = service;
  showAddServiceModal.value = true;
};

// Handle delete service
const handleDeleteService = async (serviceId: number) => {
  try {
    await expertStore.deleteOfferedService(serviceId);
  } catch (error) {
    console.error('Error deleting offered service:', error);
  }
};

// Close modal and reset data
const closeModal = () => {
  showAddServiceModal.value = false;
  currentServiceData.value = null;
};
</script>
