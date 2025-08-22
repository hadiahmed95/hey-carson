<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="flex flex-col gap-6 bg-white w-full max-w-md border border-gray-300 rounded-xl p-8 shadow-xl relative max-h-[90vh] overflow-y-auto">
      <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center">
          <h2 class="font-semibold font-archivo text-gray-800">
            {{ title }}
          </h2>
          <button @click="close" class="text-2xl text-gray-500 hover:text-black">
            &times;
          </button>
        </div>

        <p class="text-gray-700 text-sm">
          Are you sure you want to decline this expert’s offer? If you have any doubts, we suggest talking with the expert about it to help you make the best decision.
        </p>

        <div class="flex flex-col gap-1">
          <h5 class="font-normal font-archivo text-gray-600">Expert's Offer</h5>
          <div class="border border-gray-300 rounded-md p-6 flex flex-col gap-6">
            <div>
              <div class="flex justify-between items-center">
                <p class="font-semibold text-gray-800 font-archivo">Project Quote</p>
                <span class="bg-yellow-100 text-yellow-800 rounded-sm font-semibold font-archivo px-2 py-1 text-sm">
                  {{ quote.status === 'decline' ? 'Declined' : 'Pending' }}
                </span>
              </div>
              <h5 class="font-normal text-gray-600">{{ formatDate(quote.created_at, true) }}</h5>
            </div>

            <div class="flex flex-col gap-4 text-gray-800">
              <div class="flex flex-col gap-1">
                <div class="flex justify-between">
                  <h5>Hourly rate</h5>
                  <p>${{ quote.rate }}</p>
                </div>
                <div class="flex justify-between">
                  <h5>Estimated time</h5>
                  <p>{{ quote.hours }} hours</p>
                </div>
                <div class="flex justify-between">
                  <h5>Deadline</h5>
                  <p>{{ formatDate(quote.deadline) }}</p>
                </div>
              </div>
              <div class="flex flex-col gap-4">
                <hr class="border-gray-300" />
                <div class="flex justify-between font-semibold text-lg">
                  <h3>Total to pay</h3>
                  <h2 class="font-semibold">${{ total }}</h2>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3">

          <button
              @click="handleDeclineQuote()"
              :disabled="loading"
              :class="[
              'py-2 rounded-md font-semibold transition-colors w-full',
              loading ? 'opacity-50 cursor-not-allowed' : '',
              'bg-primary text-white hover:bg-gray-800'
            ]"
          >
            <span v-if="loading" class="flex items-center justify-center gap-2">
              <LoadingSpinner/>
              Processing...
            </span>
            <span v-else>
              Decline
            </span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { IQuotee } from "@/types.ts";
import { formatDate } from "@/utils/date.ts";
import { computed, ref } from 'vue';
import LoadingSpinner from '@/assets/icons/loading-spinner.svg';
import {useClientStore} from "@/store/client.ts";

const props = defineProps<{
  quote: IQuotee,
  projectId: number,
  title: string
}>()

const emit = defineEmits<{
  close: []
  quoteUpdated: [updatedQuote: IQuotee]
}>()

const quote = ref({ ...props.quote })
const loading = ref(false)
const errorMessage = ref('')

const clientStore = useClientStore()

const handleDeclineQuote = async () => {
  loading.value = true
  errorMessage.value = ''

  try {
    const res = await clientStore.declineOffer(props.projectId, quote.value.id)

    if (res?.data?.offer_or_quote) {
      const updatedQuote = { ...res.data.offer_or_quote }
      quote.value = updatedQuote

      emit('quoteUpdated', updatedQuote)
    }
    close()
  } catch (error) {
    errorMessage.value = 'Failed to decline the quote. Please try again.'
  } finally {
    loading.value = false
  }
}

const total = computed(() => {
  return (quote.value.hours * quote.value.rate).toFixed(2)
})

const close = () => {
  errorMessage.value = ''
  emit('close')
}
</script>