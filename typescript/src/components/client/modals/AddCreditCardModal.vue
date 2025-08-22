<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { loadStripe } from '@stripe/stripe-js'
import {useClientStore} from "@/store/client.ts";
import {useCommonStore} from "@/store/common.ts";
import {useExpertStore} from "@/store/expert.ts";

const props = defineProps<{
  userType?: string
}>()

const clientStore = useClientStore();
const expertStore = useExpertStore();
const commonStore = useCommonStore();
const stripe = ref<any | null>(null);
const elements = ref<any | null>(null);
const cardElement = ref<any | null>(null);
const user = props.userType === 'expert' ?  expertStore.user : clientStore.user;

const loading = ref(false)
const resultContainer = ref('')
const isMobile = window.innerWidth <= 760

const emit = defineEmits(['close', 'saved'])

onMounted(async () => {
  stripe.value = await loadStripe(import.meta.env.VITE_STRIPE_PUBLISHABLE_KEY)
  elements.value = stripe.value.elements()
  cardElement.value = elements.value.create('card')
  cardElement.value.mount('#card-element')
})

const saveCard = async () => {
  loading.value = true
  const { paymentMethod, error } = await stripe.value.createPaymentMethod({
    type: 'card',
    card: cardElement.value,
    billing_details: {
      name: `${user.first_name} ${user.last_name}`,
    },
  })

  if (error) {
    resultContainer.value = error.message
  } else {
    let expMonth = paymentMethod.card.exp_month.toString().padStart(2, '0')
    let expDate = `${paymentMethod.card.exp_year}/${expMonth}`

    try {

      await commonStore.addCreditCard({
        payment_id: paymentMethod.id,
        card_type: paymentMethod.card.brand,
        exp_date: expDate,
        last_digits: paymentMethod.card.last4,
      })
      emit('saved')
    } catch (err: any) {
      resultContainer.value = err?.message
    }
  }
  loading.value = false
}

</script>

<template>
  <!-- Mobile Modal -->
  <div v-if="isMobile" class="fixed inset-0 z-50 bg-black/30 flex items-end">
    <div class="w-full bg-white rounded-t-lg shadow-lg p-4">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-medium text-gray-700">Add Credit Card</h2>
        <button @click="emit('close')" class="text-gray-500 hover:text-gray-700">
          ✕
        </button>
      </div>

      <div class="mb-4">
        <div id="card-element" class="p-2 border rounded"></div>
        <p v-if="resultContainer" class="text-red-500 text-sm mt-2">{{ resultContainer }}</p>
      </div>

      <div class="flex justify-end gap-2">
        <button @click="emit('close')"
                class="px-4 py-2 text-sm border rounded text-gray-700 hover:bg-gray-100">
          Cancel
        </button>

        <button @click="saveCard"
                :disabled="loading"
                class="px-4 py-2 text-sm text-white bg-primary rounded hover:bg-primary-700 disabled:opacity-50">
          <span v-if="loading">Saving...</span>
          <span v-else>Save</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Desktop Modal -->
  <div v-else class="fixed inset-0 z-50 bg-black/30 flex items-center justify-center">
    <div class="bg-white w-full max-w-md rounded-lg shadow-lg">
      <div class="flex items-center justify-between border-b p-4">
        <h2 class="text-lg font-medium">Add Credit Card</h2>
        <button @click="emit('close')" class="text-gray-500 hover:text-gray-700">
          ✕
        </button>
      </div>

      <div class="p-4">
        <div id="card-element" class="p-2 border rounded"></div>
        <p v-if="resultContainer" class="text-red-500 text-sm mt-2">{{ resultContainer }}</p>
      </div>

      <div class="flex justify-end gap-2 border-t p-4">
        <button @click="emit('close')"
                class="px-4 py-2 text-sm border rounded text-gray-700 hover:bg-gray-100">
          Cancel
        </button>

        <button @click="saveCard"
                :disabled="loading"
                class="px-4 py-2 text-sm text-white bg-primary rounded hover:bg-black disabled:opacity-50">
          <span v-if="loading">Saving...</span>
          <span v-else>Save</span>
        </button>
      </div>
    </div>
  </div>
</template>
