<template>
  <main class="flex-1 p-6 overflow-y-auto bg-secondary font-light">
    <div class="grid grid-cols-[75%_1fr] h-screen bg-secondary py-10 px-5 gap-x-5 w-full">
      <div>
        <div class="flex flex-row mb-6 justify-between">
          <div class="flex items-center gap-2">
            <BackButton class="hover:cursor-pointer" @click="router.push('/client/dashboard')"/>
            <h2 v-if="route.query.type !== 'Direct Message'">
              {{ clientStore.request.request?.project?.name }}
            </h2>
            <h2 v-else>
              Direct Message / Michael Richards
            </h2>
          </div>
        </div>
        <TabNav
            v-if="route.query.type !== 'Direct Message'"
            :tabs="tabs"
        />
        <Chatroom v-else />
      </div>
      <ExpertDetails
          @show-project-quote="isShowProjectQuoteModal = true"
          :type="selectedType"
          :expert="clientStore.request.request?.expert"
          :request-type="clientStore.request.request?.type"
          :request-created-at="clientStore.request.request?.created_at"
      />
    </div>
  </main>
  <ProjectQuote
      v-if="isShowProjectQuoteModal"
      @close="isShowProjectQuoteModal = false"
  />
</template>

<script setup lang="ts">
import TabNav from "../../components/TabNav.vue";
import QuoteRequest from "../../components/common/QuoteRequest.vue";
import Chatroom from "../../components/common/Chatroom.vue";
import Invoices from "../../components/common/Invoices.vue";
import StatusHistory from "../../components/common/StatusHistory.vue";
import BackButton from "../../assets/icons/back-button.svg"
import ProjectQuote from "../../components/expert/modals/ProjectQuote.vue";
import {ref, computed, onMounted} from "vue";
import { useClientStore } from "@/store/client.ts";
import ExpertDetails from "../../components/common/cards/ExpertDetails.vue";
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const clientStore = useClientStore();

const selectedType = computed(() => {
  const typeParam = route.query.type
  if (typeParam === 'Quote Request' || typeParam === 'Matched' || typeParam === 'Direct Message') {
    return typeParam
  }
  return 'Quote Request'
})

const tabs = computed(() => [
  {
    value: 'quote-request',
    label: 'Quote Request',
    component: QuoteRequest,
    props: {
      isClientSide: true,
      request: clientStore.request.request
    }
  },
  { value: 'chatroom', label: 'Chatroom', component: Chatroom },
  {
    value: 'invoices',
    label: 'Invoices',
    component: Invoices,
    props: {
      isClientSide: true,
      request: clientStore.request.request
    }
  },
  {
    value: 'status-history',
    label: 'StatusHistory',
    component: StatusHistory,
    props: {
      request: clientStore.request.request
    }
  },
]);
const isShowProjectQuoteModal = ref<boolean>(false);

onMounted(async () => {
  if (route.params.requestId) {
    await clientStore.fetchRequest(route.params.requestId as string);
  }
});

</script>
