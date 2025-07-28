<template>
  <main class="flex-1 p-6 overflow-y-auto bg-secondary font-light">
    <div class="grid grid-cols-[75%_1fr] h-screen bg-secondary py-10 px-5 gap-x-5 w-full">
      <div>
        <div class="flex flex-row mb-6 justify-between">
          <div class="flex items-center gap-2">
            <BackButton />
            <h2>
              {{ leadDetail ? leadDetail.project.name : 'Collection Page Changes' }}
            </h2>
          </div>
        </div>

        <!-- Loading State -->
        <div
          v-if="isLoading"
          class="flex items-center justify-center p-8"
        >
          <div
            class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"
          ></div>
        </div>

        <!-- Error State -->
        <div
          v-else-if="hasError"
          class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6"
        >
          <p class="text-red-700">{{ error }}</p>
          <button
            @click="fetchLeadData"
            class="mt-2 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
          >
            Retry
          </button>
        </div>

        <!-- Content -->
        <TabNav v-else-if="leadDetail" :tabs="tabs" />

        <!-- No Data State -->
        <div v-else class="text-center p-8">
          <p class="text-gray-500">No lead details found</p>
        </div>
      </div>

      <LeadDetails
        v-if="leadDetail"
        :lead="leadDetail"
        @show-project-quote="isShowProjectQuoteModal = true"
      />
    </div>
  </main>

  <ProjectQuote
    v-if="isShowProjectQuoteModal"
    @close="isShowProjectQuoteModal = false"
  />
</template>

<script setup lang="ts">
import TabNav from "../../../components/TabNav.vue";
import QuoteRequest from "../../../components/common/QuoteRequest.vue";
import Chatroom from "../../../components/common/Chatroom.vue";
import Invoices from "../../../components/common/Invoices.vue";
import StatusHistory from "../../../components/common/StatusHistory.vue";
import BackButton from "../../../assets/icons/back-button.svg";
import LeadDetails from "../../../components/common/cards/LeadDetails.vue";
import ProjectQuote from "../../../components/expert/modals/ProjectQuote.vue";
import { ref, onMounted, computed, watch, onUnmounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";
import type { ILeadDetail } from "@/types.ts";

const route = useRoute();
const router = useRouter();

const isShowProjectQuoteModal = ref<boolean>(false);
const leadDetail = ref<ILeadDetail | null>(null);
const isLoading = ref<boolean>(false);
const hasError = ref<boolean>(false);
const error = ref<string>("");

const tabs = computed(() => [
  {
    value: "quote-request",
    label: "Quote Request",
    component: QuoteRequest,
    props: {
      request: leadDetail.value,
      isClientSide: false,
    },
  },
  {
    value: "chatroom",
    label: "Chatroom",
    component: Chatroom,
    props: {
      messages: leadDetail.value?.project?.messages || [],
      leadDetail: leadDetail.value,
    },
  },
  {
    value: "invoices",
    label: "Invoices",
    component: Invoices,
    props: {
      request: leadDetail.value,
      isClientSide: false,
    },
  },
  {
    value: "status-history",
    label: "Status History",
    component: StatusHistory,
    props: {
      leadDetail: leadDetail.value,
    },
  },
]);

const fetchLeadData = async () => {
  const leadId = parseInt(route.params.id as string);

  console.log('Route params:', route.params);
  console.log('Lead ID:', leadId);

  if (!leadId || isNaN(leadId)) {
    console.error('Invalid lead ID:', leadId);
    router.push("/expert/leads");
    return;
  }

  isLoading.value = true;
  hasError.value = false;
  error.value = "";

  try {
    console.log('Making API request to:', `/api/v2/expert/leads/${leadId}`);
    const response = await axios.get(`/api/v2/expert/leads/${leadId}`);
    console.log('API Response:', response.data);
    leadDetail.value = response.data.lead;
  } catch (err: any) {
    console.error('API Error:', err);
    console.error('Error response:', err.response?.data);
    hasError.value = true;
    error.value = err.response?.data?.message || "Failed to fetch lead details";
  } finally {
    isLoading.value = false;
  }
};

// Watch for route changes
watch(
  () => route.params.id,
  (newLeadId) => {
    if (newLeadId) {
      // Clear previous data
      leadDetail.value = null;
      fetchLeadData();
    }
  },
  { immediate: true }
);

onMounted(() => {
  fetchLeadData();
});

// Cleanup on unmount
onUnmounted(() => {
  leadDetail.value = null;
});
</script>