<template>
  <main class="flex-1 p-6 overflow-y-auto bg-secondary font-light">
    <!-- Error message display -->
    <div v-if="errorMessage" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-md">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <CrossIcon/>
        </div>
        <div class="ml-3">
          <p class="text-red-700 font-medium">{{ errorMessage }}</p>
        </div>
      </div>
    </div>
    <div class="grid grid-cols-[75%_1fr] h-screen bg-secondary py-10 px-5 gap-x-5 w-full">
      <div>
        <div class="flex flex-row mb-6 justify-between">
          <div class="flex items-center gap-2">
            <BackButton class="hover:cursor-pointer" @click="router.push('/expert/dashboard')"/>
            <div v-if="isLoading" class="h-6 bg-gray-200 rounded w-48 animate-pulse"></div>
            <h2 v-else-if="hasError" class="text-red-600">
              Error loading lead
            </h2>
            <h2 v-else-if="currentLead">
              {{ currentLead.project.name }}
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
        :is-loading="isLoading"
        :lead-detail="currentLead"
        :buttonText="!hasQuoteWithPaidStatus ? 'Send a Project Quote' : 'Add to Scope'"
        :hasQuoteWithSendStatus="hasQuoteWithSendStatus"
        @show-project-quote="isShowProjectQuoteModal = true"
      />
    </div>
  </main>

  <BaseModal
    v-if="isShowProjectQuoteModal"
    @close="isShowProjectQuoteModal = false"
    @quotesUpdated="fetchLeadDetails"
    :title="!hasQuoteWithPaidStatus ? 'Send Project Quote' : 'Add to Scope'"
    :buttonText="!hasQuoteWithPaidStatus ? 'Send quote to client' : undefined"
    :project-id="currentLead.project.id"
    :isShowQuestionSection="false"
    :isShowRecaptchaSection="false"
    :form="AddToScope"
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
import {ref, computed, onMounted} from "vue";
import { useRoute, useRouter } from "vue-router";
import { useExpertStore } from "@/store/expert.ts";
import type { ILeadDetail } from "@/types.ts";
import AddToScope from "@/components/expert/forms/AddToScope.vue";
import BaseModal from "@/components/expert/BaseModal.vue";
import { useLoaderStore } from "@/store/loader.ts";
import CrossIcon from "@/assets/icons/cross-icon.svg";

const router = useRouter();
const route = useRoute();
const expertStore = useExpertStore();

const isShowProjectQuoteModal = ref<boolean>(false);
const leadDetail = ref<ILeadDetail | null>(null);
const loader = useLoaderStore();
const isLoading = computed(() => loader.isLoadingState);
const hasError = ref<boolean>(false);
const errorMessage = ref<string>('');

const currentLead = computed(() => expertStore.lead.lead);

const tabs = computed(() => [
  {
    value: "quote-request",
    label: "Quote Request",
    component: QuoteRequest,
    props: {
      lead: currentLead.value,
      isLoading: isLoading.value,
      isClientSide: false,
    },
  },
  {
    value: "chatroom",
    label: "Chatroom",
    component: Chatroom,
    props: {
      messages: currentLead.value?.project?.messages || [],
      leadDetail: leadDetail.value,
    },
  },
  {
    value: "invoices",
    label: "Invoices",
    component: Invoices,
    props: {
      invoices: currentLead.value?.project.invoices,
    },
  },
  {
    value: "status-history",
    label: "Status History",
    component: StatusHistory,
    props: {
      history: currentLead.value?.project.history,
    },
  },
]);

const hasQuoteWithSendStatus = computed(() => {
  if (!currentLead.value) return false;

  const clientQuotes = currentLead.value.client.quotes_by_client_id || [];
  const hasClientQuoteWithSend = clientQuotes.some(quote => quote.status === 'send');

  const projectOffers = currentLead.value.project.active_assignment?.offers || [];
  const hasProjectOfferWithSend = projectOffers.some(offer => offer.status === 'send');

  return hasClientQuoteWithSend || hasProjectOfferWithSend;
});

const hasQuoteWithPaidStatus = computed(() => {
  if (!currentLead.value) return false;

  const clientQuotes = currentLead.value.client.quotes_by_client_id || [];
  const hasClientQuoteWithPaid = clientQuotes.some(quote => quote.status === 'paid');

  const projectOffers = currentLead.value.project.active_assignment?.offers || [];
  const hasProjectOfferWithPaid = projectOffers.some(offer => offer.status === 'paid');

  return hasClientQuoteWithPaid || hasProjectOfferWithPaid;
});

const fetchLeadDetails = async () => {
  if (route.params.leadId) {
    try {
      await expertStore.fetchLeadsDetails(route.params.leadId as string);
    } catch (error: any) {
      console.error('Error fetching lead details:', error);

      if (error.response?.status === 403) {
        hasError.value = true;
        errorMessage.value = 'This lead has been matched with another expert. You will be redirected to the dashboard shortly.';
        redirectToDashboard();
      } else {
        hasError.value = true;
        errorMessage.value = error.response?.data?.error || 'An error occurred while loading the lead details.';

        redirectToDashboard();
      }
    }
  }
}

const redirectToDashboard = () => {
  setTimeout(() => {
    router.push('/expert/dashboard');
  }, 5000);
}

onMounted(async () => {
  await fetchLeadDetails()
});
</script>