<template>
  <main class="flex-1 p-6 overflow-y-auto bg-secondary font-light">
    <div class="grid grid-cols-[75%_1fr] h-screen bg-secondary py-10 px-5 gap-x-5 w-full">
      <div>
        <div class="flex flex-row mb-6 justify-between">
          <div class="flex items-center gap-2">
            <BackButton/>
            <h2 v-if="isLoading">
              Loading...
            </h2>
            <h2 v-else-if="hasError" class="text-red-600">
              Error loading lead
            </h2>
            <h2 v-else-if="leadDetail">
              {{ leadDetail.project?.name || 'Lead Details' }}
            </h2>
            <h2 v-else>
              Lead Details
            </h2>
          </div>
        </div>
        <TabNav
            :tabs="tabs"
        />
      </div>
      <LeadDetails 
        v-if="leadDetail"
        :lead-detail="leadDetail"
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
import BackButton from "../../../assets/icons/back-button.svg"
import LeadDetails from "../../../components/common/cards/LeadDetails.vue";
import ProjectQuote from "../../../components/expert/modals/ProjectQuote.vue";
import { ref, onMounted, computed, watch, onUnmounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useExpertStore } from "@/store/expert.ts";
import type { ILeadDetail } from "@/types.ts";

const route = useRoute();
const router = useRouter();
const expertStore = useExpertStore();

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
  console.log("Full route object:", route);
  const leadId = parseInt(route.params.leadId as string);

  if (!leadId || isNaN(leadId)) {
    router.push("/expert/leads");
    return;
  }

  isLoading.value = true;
  hasError.value = false;
  error.value = "";

  try {
    const response = await expertStore.fetchLeadsDetails(leadId);
    leadDetail.value = response.data.lead;
  } catch (err: any) {
    hasError.value = true;
    error.value = err.response?.data?.message || "Failed to fetch lead details";
    console.error("Failed to fetch lead details:", err);
  } finally {
    isLoading.value = false;
  }
};

// Watch for route changes
watch(
  () => route.params.leadId,
  (newLeadId) => {
    if (newLeadId) {
      leadDetail.value = null;
      fetchLeadData();
    }
  },
  { immediate: true }
);

// Cleanup on unmount
onUnmounted(() => {
  leadDetail.value = null;
});
</script>
