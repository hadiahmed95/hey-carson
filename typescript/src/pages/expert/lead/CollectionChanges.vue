<template>

    <main class="flex-1 p-6 overflow-y-auto bg-secondary font-light">
      <div class="grid grid-cols-[75%_1fr] h-screen bg-secondary py-10 px-5 gap-x-5 w-full">
        <div>
          <div class="flex flex-row mb-6 justify-between">
            <div class="flex items-center gap-2">
              <BackButton/>
              <h2>
                Collection Page Changes
              </h2>
            </div>
          </div>
          <TabNav
              :tabs="tabs"
          />
        </div>
        <LeadDetails @show-project-quote="isShowProjectQuoteModal = true" />
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
import {ref} from "vue";

const tabs = [
  { value: 'quote-request', label: 'Quote Request', component: QuoteRequest },
  { value: 'chatroom', label: 'Chatroom', component: Chatroom },
  { value: 'invoices', label: 'Invoices', component: Invoices },
  { value: 'status-history', label: 'Status History', component: StatusHistory },
]
const isShowProjectQuoteModal = ref<boolean>(false);


</script>
