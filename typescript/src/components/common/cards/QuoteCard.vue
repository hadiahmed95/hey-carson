<template>
  <div class="rounded-sm bg-secondary border border-grey px-6 py-4">
    <!-- Quote content row -->
    <div class="grid items-center" style="grid-template-columns: 2fr 1fr 1fr 1fr 0.5fr; gap: 2rem 1rem;">
      <!-- Quote title and date -->
      <div class="flex flex-col">
        <div class="flex gap-2 items-center mb-1">
          <p class="font-semibold text-base">{{ quoteLabel }}</p>
          <span :class="['rounded-sm px-2 py-1 text-xs font-semibold', statusStyle]">
            {{ localQuote.status === 'send' ? 'Pending' : localQuote.status === 'decline' ? 'Declined' : capitalize(localQuote.status) }}
          </span>
        </div>
        <p class="text-greyExtraDark text-sm">{{ formatDate(localQuote.created_at, true) }}</p>
      </div>

      <!-- Hourly rate -->
      <div class="text-left">
        <p class="text-gray-600 text-sm mb-1">Hourly rate</p>
        <p class="font-normal text-base">${{ localQuote.rate.toFixed(2) }}</p>
      </div>

      <!-- Estimated time -->
      <div class="text-left">
        <p class="text-gray-600 text-sm mb-1">Estimated time</p>
        <p class="font-normal text-base">{{ localQuote.hours }} hours</p>
      </div>

      <!-- Deadline -->
      <div class="text-left">
        <p class="text-gray-600 text-sm mb-1">Deadline</p>
        <p class="font-normal text-base">{{ formatDate(localQuote.deadline) }}</p>
      </div>

      <!-- Total to pay -->
      <div class="text-left">
        <p class="text-gray-600 text-sm mb-1">Total to pay</p>
        <p class="font-semibold text-base">${{ ( localQuote.rate * localQuote.hours ).toFixed(2) }}</p>
      </div>
    </div>

    <!-- Action buttons -->
    <div v-if="showClientActions" class="flex justify-start gap-3 mt-4 pt-4 border-t border-grey">
      <button
        class="bg-primary text-white px-4 py-2 rounded-md font-semibold text-sm hover:bg-primary-dark transition-colors"
        @click="showPaymentModal = true"
      >Accept & Pay</button>
      <button
        class="bg-white text-black border border-gray-300 px-4 py-2 rounded-md font-semibold text-sm hover:bg-gray-50 transition-colors"
        @click="showDeclinePaymentModal = true"
      >
        Decline
      </button>
    </div>
  </div>

  <PaymentModal
    v-if="showPaymentModal && project"
    :quote="localQuote"
    :project-id="project.id"
    @close="showPaymentModal = false"
    @quoteUpdated="handleQuoteUpdate"
  />

  <DeclinePaymentModal
    v-if="showDeclinePaymentModal && project"
    :quote="localQuote"
    :project-id="project.id"
    :title="quoteLabel === 'Project Quote' ? 'Decline Project Quote' : 'Decline Additional Hours'"
    @close="showDeclinePaymentModal = false"
    @quoteUpdated="handleQuoteUpdate"
  />
</template>

<script setup lang="ts">
import type { IQuotee } from "@/types.ts";
import { computed, ref } from "vue";
import {capitalize} from "@/utils/helpers.ts";
import {formatDate} from "@/utils/date.ts";
import PaymentModal from "@/components/client/modals/PaymentModal.vue";
import DeclinePaymentModal from "@/components/client/modals/DeclinePaymentModal.vue";

const showPaymentModal = ref(false);
const showDeclinePaymentModal = ref(false);

const props = defineProps<{
  quote: IQuotee,
  project?: any,
  isClientSide?: boolean
}>()

const emit = defineEmits<{
  quoteUpdated: [updatedQuote: IQuotee]
}>()

// Create local reactive copy of the quote
const localQuote = ref<IQuotee>({ ...props.quote })

const statusStyle = computed(() => {
  switch (localQuote.value.status) {
    case 'paid':
      return 'bg-softgreen text-success'
    case 'decline':
      return 'bg-softpink text-darkpink'
    case 'send':
    default:
      return 'bg-pending-light text-pending'
  }
})

const quoteLabel = computed(() => {
  if (!localQuote.value.type || localQuote.value.type === 'Quote' || localQuote.value.type === 'offer') {
    return props.isClientSide ? 'Project Quote' : 'Your Quote'
  }
  return 'Add to Scope'
})

const showClientActions = computed(() => {
  return props.isClientSide && localQuote.value.status === 'send'
})

const handleQuoteUpdate = (updatedQuote: IQuotee) => {
  localQuote.value = { ...updatedQuote }
  emit('quoteUpdated', updatedQuote)
}
</script>