<template>
  <div>
    <LoadingCard v-if="isLoading" />
    <EmptyDataPlaceholder title="Looks like you don’t have any leads yet" v-else-if="expertStore.leads?.leads?.length === 0"/>
    <div v-else class="mb-14">
      <LeadCard
        v-for="lead in expertStore.leads?.leads"
        :key="lead.id"
        :project-name="lead.project.name"
        :name="lead.client.first_name + ' ' + lead.client.last_name"
        :email="lead.client.email"
        :store-name="lead.client.url"
        :store-url="lead.client.url"
        :shopify-plan="lead.client.shopify_plan"
        :avatar-url="lead.client.photo ?? ''"
        :submitted-date="formatDate(lead.created_at)"
        :type="lead.type"
        class="flex flex-col gap-3"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import LeadCard from './cards/LeadCard.vue'
import {computed, watch} from "vue";
import {useExpertStore} from "@/store/expert.ts";
import {formatDate} from "@/utils/date.ts";
import LoadingCard from "@/components/common/LoadingCard.vue";
import EmptyDataPlaceholder from "@/components/common/EmptyDataPlaceholder.vue";
import {useLoaderStore} from "@/store/loader.ts";

const props = defineProps<{
  isQuoteRequest?: boolean,
  isDirectMessage?: boolean,
}>()

const loader = useLoaderStore();
const isLoading = computed(() => loader.isLoadingState);
const expertStore = useExpertStore();

const fetchFilteredLeads = async () => {
  const filters: { type?: string } = {}
  if (props.isQuoteRequest) {
    filters.type = 'Quote Request'
  }
  if (props.isDirectMessage) {
    filters.type = 'Direct Message'
  }

  await expertStore.fetchLeads(filters)
}

watch( () => [
  props.isQuoteRequest,
  props.isDirectMessage
], () => {
  fetchFilteredLeads()
}, {
  immediate: true
})
</script>