<script setup lang="ts">
import { defineProps, computed, ref, watch } from 'vue';
import type {ILeadDetail, IQuotee, IRequest} from '@/types.ts';
import QuoteCard from './cards/QuoteCard.vue';
import LoadingCard from "@/components/common/LoadingCard.vue";

const props = defineProps<{
  isClientSide?: boolean,
  request?: IRequest
  lead?: ILeadDetail
  isLoading: boolean
}>();

const quotesData = ref<IQuotee[]>([])

const projectDescription = computed(() => {
  let data
  if (props.isClientSide) {
    data = props.request;
  } else {
    data = props.lead;
  }

  const desc = data?.project?.description;
  return desc && desc.trim().length > 0 ? desc : 'No project description available.';
});

const getQuotes = (isClient: boolean, data: any) => {
  if (!data) return [];

  const activeOffers = data.project?.active_assignment?.offers;
  if (activeOffers) return activeOffers;

  if (isClient) {
    return data.expert?.quotes || [];
  }

  return data.client?.quotes_by_client_id || [];
};

const updateQuotesData = () => {
  const rawQuotes = getQuotes(props.isClientSide, props.isClientSide ? props.request : props.lead);

  quotesData.value = rawQuotes.map((quote: any) => {
    return {
      id: quote.id,
      type: quote.type ? quote.type : 'Quote',
      rate: quote.rate,
      status: quote.status,
      hours: quote.hours,
      deadline: quote.deadline ? quote.deadline : 'N/A',
      created_at: quote.created_at
    };
  });
}

watch(() => props.request, () => {
  if (props.isClientSide) {
    updateQuotesData()
  }
}, { deep: true, immediate: true })

watch(() => props.lead, () => {
  if (!props.isClientSide) {
    updateQuotesData()
  }
}, { deep: true, immediate: true })

const handleQuoteUpdate = (updatedQuote: IQuotee) => {
  const index = quotesData.value.findIndex(q => q.id === updatedQuote.id)
  if (index !== -1) {
    quotesData.value[index] = { ...updatedQuote }
  }
}

const quotes = computed<IQuotee[]>(() => quotesData.value);
</script>

<template>
  <div class="p-6 border border-grey bg-white rounded-md space-y-6">
    <!-- Loading State -->
    <template v-if="props.isLoading">
      <div class="space-y-4">
        <div class="space-y-2">
          <div class="h-4 bg-gray-200 rounded animate-pulse"></div>
          <div class="h-4 bg-gray-200 rounded w-4/5 animate-pulse"></div>
          <div class="h-4 bg-gray-200 rounded w-3/4 animate-pulse"></div>
          <div class="h-4 bg-gray-200 rounded animate-pulse"></div>
          <div class="h-4 bg-gray-200 rounded w-4/5 animate-pulse"></div>
          <div class="h-4 bg-gray-200 rounded w-4/5 animate-pulse"></div>
          <div class="h-4 bg-gray-200 rounded w-3/4 animate-pulse"></div>
        </div>
      </div>

      <div class="space-y-2">
        <div class="h-6 bg-gray-200 rounded w-28 animate-pulse"></div> <!-- "Your Quotes" title -->
        <LoadingCard
          :count="1"
          type="quote"
        />
      </div>
    </template>

    <!-- Loaded Content -->
    <template v-else-if="(props.request && props.request.project) || (props.lead && props.lead.project)">
      <template v-if="props.isClientSide">
        <div class="flex flex-col gap-2">
          <h4 class="font-semibold">Expert Offers</h4>

          <!-- Show loading or actual quotes -->
          <template v-if="quotes.length > 0">
            <QuoteCard
              v-for="quote in quotes"
              :key="quote.id"
              :quote="quote"
              :project="props.request?.project"
              :is-client-side="props.isClientSide"
              @quoteUpdated="handleQuoteUpdate"
            />
          </template>
          <template v-else>
            <p class="text-gray-500 py-4">No expert offers available yet.</p>
          </template>
        </div>

        <div class="space-y-4">
          <h4 class="pb-2 border-b border-grey font-semibold">
            Quote Request Description
          </h4>
          <p v-html="projectDescription"></p>
        </div>
      </template>

      <template v-else>
        <div class="space-y-4">
          <h4 class="pb-2 border-b border-grey font-semibold">
            Quote Request Description
          </h4>
          <p v-html="projectDescription"></p>
        </div>

        <div class="space-y-2" v-if="quotes.length > 0">
          <h4 class="font-semibold">Your Quotes</h4>
          <QuoteCard
            v-for="quote in quotes"
            :key="quote.id"
            :quote="quote"
            @quoteUpdated="handleQuoteUpdate"
          />
        </div>
        <div v-else class="space-y-2">
          <h4 class="font-semibold">Your Quotes</h4>
          <p class="text-gray-500 py-4">No quotes submitted yet.</p>
        </div>
      </template>
    </template>

    <!-- Error State -->
    <template v-else>
      <p class="text-red-500">Project data not available.</p>
    </template>
  </div>
</template>