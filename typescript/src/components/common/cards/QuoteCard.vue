<template>
  <div class="rounded-sm bg-secondary border border-grey px-6 py-4 flex flex-col gap-4">
    <!-- Quote content row -->
    <div class="grid grid-flow-col auto-cols-fr items-center">
      <div>
        <div class="flex gap-1 items-center">
          <p class="font-semibold">{{ quoteLabel }}</p>
          <h5 :class="['rounded-sm px-2 py-1 font-semibold', statusStyle]">{{ quote.status === 'send' ? 'Pending' : capitalize(quote.status) }}</h5>
        </div>
        <h5 class="text-greyExtraDark">{{ quote.created_at }}</h5>
      </div>
      <div>
        <h5 class="font-normal">Hourly rate</h5>
        <p class="font-normal">${{ quote.rate }}</p>
      </div>
      <div>
        <h5 class="font-normal">Estimated time</h5>
        <p class="font-normal">{{ quote.hours }} hours</p>
      </div>
      <div>
        <h5 class="font-normal">Deadline</h5>
        <p class="font-normal">{{ quote.deadline }}</p>
      </div>
      <div class="text-right">
        <h5 class="font-normal">Total to pay</h5>
        <p class="font-normal">{{ ( quote.rate * quote.hours ).toFixed(2) }}</p>
      </div>
    </div>

    <div v-if="showClientActions" class="flex justify-start gap-2">
      <button
          class="bg-primary text-white px-3 py-1 rounded-md font-semibold text-h4"
          @click="showPaymentModal = true"
      >Accept & Pay</button>
      <button class="bg-white text-black border border-gray px-3 py-1 rounded-md font-semibold">Decline</button>
    </div>
  </div>
  <PaymentModal v-if="showPaymentModal" @close="showPaymentModal = false"/>
</template>

<script setup lang="ts">
import type { IQuotee } from "@/types.ts";
import { computed, ref } from "vue";
import {capitalize} from "@/utils/helpers.ts";
import PaymentModal from "@/components/client/modals/PaymentModal.vue";

const showPaymentModal = ref(false);

const props = defineProps<{
  quote: IQuotee,
  isClientSide?: boolean
}>()

const statusStyle = computed(() => {
  switch (props.quote.status) {
    case 'paid':
      return 'bg-softgreen text-success'
    case 'declined':
      return 'bg-softpink text-darkpink'
    case 'send':
    default:
      return 'bg-pending-light text-pending'
  }
})

const quoteLabel = computed(() => {
  if (props.quote.type === 'Quote') {
    return props.isClientSide ? 'Project Quote' : 'Your Quote'
  }
  return 'Add to Scope'
})

const showClientActions = computed(() => {
  return props.isClientSide && props.quote.status === 'send'
})
</script>
