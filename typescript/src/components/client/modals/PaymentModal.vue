<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="flex flex-col gap-6 bg-white w-full max-w-md border border-gray-300 rounded-xl p-8 shadow-xl relative max-h-[90vh] overflow-y-auto">

      <!-- Success State -->
      <div v-if="step === 'succeeded'" class="flex flex-col gap-6">
        <div class="flex justify-between items-center">
          <h2 class="font-semibold font-archivo text-green-600">Payment Successful</h2>
          <button @click="close" class="text-2xl text-gray-500 hover:text-black">
            &times;
          </button>
        </div>

        <div class="flex flex-col items-center gap-4">
          <SuccessPaymentIcon />
          <p class="text-center text-gray-700">
            We confirm that your payment has been processed successfully, and the amount related to the offer will be charged to your credit card.
          </p>
        </div>

        <button @click="close" class="w-full py-2 bg-green-600 text-white rounded-md font-semibold hover:bg-green-700 transition-colors">
          Close
        </button>
      </div>

      <!-- Failed State -->
      <div v-else-if="step === 'failed'" class="flex flex-col gap-6">
        <div class="flex justify-between items-center">
          <h2 class="font-semibold font-archivo text-red-600">Payment Failed</h2>
          <button @click="close" class="text-2xl text-gray-500 hover:text-black">
            &times;
          </button>
        </div>

        <div class="flex flex-col items-center gap-4">
          <ErrorPaymentIcon />
          <p class="text-center text-gray-700">
            Unfortunately, your payment was not processed successfully, and no amount has been charged to your credit card. Please check your card details or contact your bank for more information.
          </p>
        </div>

        <div class="flex gap-3">
          <button @click="step = 'select'" class="flex-1 py-2 border border-gray-300 text-gray-700 rounded-md font-semibold hover:bg-gray-50 transition-colors">
            Try Again
          </button>
          <button @click="close" class="flex-1 py-2 bg-gray-600 text-white rounded-md font-semibold hover:bg-gray-700 transition-colors">
            Close
          </button>
        </div>
      </div>

      <!-- Main Payment Flow -->
      <div v-else class="flex flex-col gap-6">
        <div class="flex justify-between items-center">
          <h2 class="font-semibold font-archivo text-gray-800">
            {{ step === 'select' ? 'Accept & Pay Project Quote' : step === 'card' ? 'Select Payment Method' : 'Confirm Payment' }}
          </h2>
          <button @click="close" class="text-2xl text-gray-500 hover:text-black">
            &times;
          </button>
        </div>

        <!-- Quote Details -->
        <div v-if="step === 'select'" class="flex flex-col gap-1">
          <h5 class="font-normal font-archivo text-gray-600">Expert's Offer</h5>
          <div class="border border-gray-300 rounded-md p-6 flex flex-col gap-6">
            <div>
              <div class="flex justify-between items-center">
                <p class="font-semibold text-gray-800 font-archivo">Project Quote</p>
                <span class="bg-yellow-100 text-yellow-800 rounded-sm font-semibold font-archivo px-2 py-1 text-sm">
                  {{ quote.status === 'paid' ? 'Paid' : 'Pending' }}
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

        <!-- Payment Method Selection -->
        <div v-if="step === 'card'" class="flex flex-col gap-4">
          <h5 class="font-medium text-gray-700">Choose a payment method</h5>

          <!-- Saved Cards -->
          <div v-if="userCards.length" class="flex flex-col gap-3">
            <div
                v-for="card in userCards"
                :key="card.id"
                @click="selectCard(card)"
                :class="[
                'border rounded-lg p-4 cursor-pointer transition-colors',
                card.selected ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400'
              ]"
            >
              <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center">
                    <CreditCard />
                  </div>
                  <div>
                    <p class="font-medium capitalize">{{ card.card_type }} •••• {{ card.last_digits }}</p>
                    <p class="text-sm text-gray-600">Expires {{ card.exp_date }}</p>
                  </div>
                </div>
                <div v-if="card.selected" class="w-5 h-5 bg-blue-500 rounded-full flex items-center justify-center">
                  <CheckMarkIcon />
                </div>
              </div>
            </div>
          </div>

          <!-- Add New Card -->
          <div v-if="!userCards.length" class="flex flex-col gap-4">
            <p class="text-gray-600">You don't have any saved cards. Add one now to proceed with payment.</p>
            <div class="border border-gray-300 rounded-lg p-4">
              <div id="card-element" class="min-h-[40px]" ref="cardElementDiv"></div>
            </div>
            <p v-if="errorMessage" class="text-red-600 text-sm">{{ errorMessage }}</p>
          </div>
        </div>

        <!-- Payment Confirmation -->
        <div v-if="step === 'payment'" class="flex flex-col gap-4">
          <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <h5 class="font-medium text-gray-800 mb-2">Payment Summary</h5>
            <div class="flex justify-between text-sm text-gray-600 mb-1">
              <span>Amount:</span>
              <span>${{ total }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-600">
              <span>Payment method:</span>
              <span>{{ selectedCard.card_type }} •••• {{ selectedCard.last_digits }}</span>
            </div>
          </div>

          <p class="text-gray-600 text-sm">
            Your card ending with <strong>{{ selectedCard.last_digits }}</strong> will be charged
            <strong>${{ total }}</strong> for this project quote.
          </p>
        </div>

        <!-- Info Text -->
        <p v-if="step === 'select'" class="text-gray-700 text-sm">
          After payments go through, the project transitions to 'In Progress,' and the expert begins work.
          Payment will be released immediately, with compensation provided upon completion.
        </p>

        <!-- Action Buttons -->
        <div class="flex gap-3">
          <button
              v-if="step !== 'select'"
              @click="goBack"
              class="flex-1 py-2 border border-gray-300 text-gray-700 rounded-md font-semibold hover:bg-gray-50 transition-colors"
              :disabled="loading"
          >
            Back
          </button>

          <button
              @click="handlePrimaryAction"
              :disabled="loading || (step === 'card' && userCards.length > 0 && !selectedCard)"
              :class="[
              'py-2 rounded-md font-semibold transition-colors',
              step === 'select' ? 'w-full' : 'flex-1',
              loading ? 'opacity-50 cursor-not-allowed' : '',
              'bg-primary text-white hover:bg-gray-800'
            ]"
          >
            <span v-if="loading" class="flex items-center justify-center gap-2">
              <LoadingSpinner/>
              Processing...
            </span>
            <span v-else>
              {{ getButtonText() }}
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
import { computed, nextTick, onMounted, ref } from 'vue';
import { loadStripe } from '@stripe/stripe-js';
import { useAuthStore } from "@/store/auth.ts";
import { useClientStore } from "@/store/client.ts";
import { useCommonStore } from "@/store/common.ts";
import SuccessPaymentIcon from '@/assets/icons/success-payment-icon.svg';
import ErrorPaymentIcon from '@/assets/icons/error-payment-icon.svg';
import CreditCard from '@/assets/icons/credit-card.svg';
import LoadingSpinner from '@/assets/icons/loading-spinner.svg';
import CheckMarkIcon from '@/assets/icons/checkmark-icon.svg';

// Props
const props = defineProps<{
  quote: IQuotee,
  projectId: number
}>()

// Emits
const emit = defineEmits<{
  close: []
  quoteUpdated: [updatedQuote: IQuotee]
}>()

// Create reactive quote from props
const quote = ref({ ...props.quote })

// Reactive state
const userCards = ref<any[]>([])
const selectedCard = ref<any>(null)
const stripe = ref<any | null>(null)
const elements = ref<any | null>(null)
const cardElement = ref<any | null>(null)
const cardElementDiv = ref<HTMLElement | null>(null)
const loading = ref(false)
const errorMessage = ref('')
const step = ref<'select' | 'card' | 'payment' | 'succeeded' | 'failed'>('select')
const clientStore = useClientStore()

const authStore = useAuthStore()
const commonStore = useCommonStore()

const total = computed(() => {
  return (quote.value.hours * quote.value.rate).toFixed(2)
})

const getUserCards = async () => {
  try {
    await clientStore.fetchClient()
    userCards.value = clientStore.user.saved_cards || []

    userCards.value.forEach((card: any) => {
      card.selected = card.default

      if (card.default) {
        selectedCard.value = card
      }
    })
  } catch (err) {
    console.error('Error fetching user cards:', err)
  }
}

const selectCard = (card: any) => {
  userCards.value.forEach((c: any) => {
    c.selected = c.id === card.id

    if (c.selected) {
      selectedCard.value = c
    }
  })
}

const initializeCardElement = async () => {
  if (!stripe.value || !elements.value || cardElement.value) return

  try {
    cardElement.value = elements.value.create('card', {
      style: {
        base: {
          fontSize: '16px',
          color: '#424770',
          '::placeholder': {
            color: '#aab7c4',
          },
        },
      },
    })

    if (cardElementDiv.value) {
      cardElement.value.mount(cardElementDiv.value)
    }
  } catch (error) {
    console.error('Error initializing card element:', error)
  }
}

const saveCardAndContinue = async () => {
  if (!stripe.value || !cardElement.value) return

  loading.value = true
  errorMessage.value = ''

  try {
    const { paymentMethod, error } = await stripe.value.createPaymentMethod({
      type: 'card',
      card: cardElement.value,
      billing_details: {
        name: authStore.user.first_name + ' ' + authStore.user.last_name,
      },
    })

    if (error) {
      errorMessage.value = error.message
      return
    }

    let expDate = ''
    if (paymentMethod.card.exp_month < 10) {
      expDate = paymentMethod.card.exp_year + '/0' + paymentMethod.card.exp_month
    } else {
      expDate = paymentMethod.card.exp_year + '/' + paymentMethod.card.exp_month
    }

    await commonStore.addCreditCard({
      payment_id: paymentMethod.id,
      card_type: paymentMethod.card.brand,
      exp_date: expDate,
      last_digits: paymentMethod.card.last4
    })

    await getUserCards()
    step.value = 'payment'
  } catch (err: any) {
    errorMessage.value = err.response?.data?.message || 'An error occurred while saving the card'
  } finally {
    loading.value = false
  }
}

const payWithCard = async () => {
  if (!selectedCard.value) return

  loading.value = true

  try {
    const selectedPack = {
      amount: quote.value.hours,
      price: Number(quote.value.rate),
      total: parseFloat(total.value),
      offer_id: quote.value.id,
    }

    const paymentResponse = await commonStore.cardPayment({
      project_id: props.projectId,
      selected_pack: selectedPack,
      selected_card_id: selectedCard.value.id,
    })

    if (paymentResponse?.data?.payment) {
      const res = await clientStore.updateOffer(props.projectId, paymentResponse.data.payment.offer_id)

      if (res?.data?.offer) {
        const updatedQuote = { ...res.data.offer }
        quote.value = updatedQuote

        // Emit the updated quote to parent components
        emit('quoteUpdated', updatedQuote)
      }

      step.value = 'succeeded'
    }
  } catch (err) {
    console.error('Payment failed:', err)
    step.value = 'failed'
  } finally {
    loading.value = false
  }
}

const handlePrimaryAction = async () => {
  switch (step.value) {
    case 'select':
      step.value = 'card'
      if (!userCards.value.length && stripe.value && elements.value) {
        await nextTick()
        await initializeCardElement()
      }
      break
    case 'card':
      if (userCards.value.length && selectedCard.value) {
        step.value = 'payment'
      } else if (!userCards.value.length) {
        await saveCardAndContinue()
      }
      break
    case 'payment':
      await payWithCard()
      break
  }
}

const goBack = () => {
  switch (step.value) {
    case 'card':
      step.value = 'select'
      break
    case 'payment':
      step.value = 'card'
      break
  }
}

const getButtonText = () => {
  switch (step.value) {
    case 'select':
      return 'Accept & Pay'
    case 'card':
      if (!userCards.value.length) {
        return 'Save Card & Continue'
      }
      return selectedCard.value ? 'Continue' : 'Select a Card'
    case 'payment':
      return 'Confirm & Pay'
    default:
      return 'Continue'
  }
}

const close = () => {
  step.value = 'select'
  errorMessage.value = ''
  emit('close')
}

// Lifecycle
onMounted(async () => {
  await getUserCards()

  try {
    stripe.value = await loadStripe(import.meta.env.VITE_STRIPE_PUBLISHABLE_KEY)
    if (stripe.value) {
      elements.value = stripe.value.elements()
    }
  } catch (error) {
    console.error('Error initializing Stripe:', error)
  }
})
</script>