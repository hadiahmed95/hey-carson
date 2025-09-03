<template>
  <!-- Mobile Modal -->
  <div v-if="isMobile" class="fixed inset-0 z-50 bg-primary/30 flex items-end">
    <div class="w-full bg-card rounded-t-lg shadow-lg p-4 max-h-[90vh] overflow-y-auto">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-medium text-tertiary">Withdraw Funds</h2>
        <button @click="emit('close')" class="text-tertiary hover:text-tertiary-dark">
          ✕
        </button>
      </div>

      <!-- Zero Balance State -->
      <div v-if="availableBalance <= 0" class="text-center py-6">
        <div class="mb-4">
          <p class="text-tertiary text-h5">
            Your current available balance is ${{ availableBalance.toFixed(2) }}. <br /> 
            Please continue working on projects to earn funds, so that you can make your next withdraw.
          </p>
        </div>
        <button @click="emit('close')" class="w-full px-4 py-2 text-h5 text-card bg-primary rounded hover:bg-primary/90">
          Close
        </button>
      </div>

      <!-- Withdraw Form -->
      <div v-else>
        <div class="mb-4">
          <p class="text-h5 text-tertiary">Available Balance: <span class="font-semibold">${{ availableBalance.toFixed(2) }}</span></p>
        </div>

        <div class="mb-4">
          <label class="block text-h5 font-medium text-tertiary mb-2">Amount to Withdraw</label>
          <div class="relative">
            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-tertiary">$</span>
            <input
                v-model="amount"
                type="number"
                step="0.01"
                min="1"
                :max="availableBalance"
                class="w-full pl-8 pr-3 py-2 border border-grey rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                placeholder="0.00"
            />
          </div>
          <p v-if="errors?.amount" class="text-danger text-h5 mt-1">{{ errors.amount[0] }}</p>
        </div>

        <div class="mb-4">
          <label class="block text-h5 font-medium text-tertiary mb-2">Payment Method</label>
          <div class="space-y-2">
            <label class="flex items-center">
              <input type="radio" v-model="type" value="paypal" class="mr-2" />
              PayPal
            </label>
            <label class="flex items-center">
              <input type="radio" v-model="type" value="wise" class="mr-2" />
              Wise
            </label>
          </div>
          <p v-if="errors?.type" class="text-danger text-h5 mt-1">{{ errors.type[0] }}</p>
        </div>

        <div class="flex gap-2">
          <button @click="emit('close')"
                  class="flex-1 px-4 py-2 text-h5 border rounded text-tertiary hover:bg-greyLight">
            Cancel
          </button>

          <button @click="submitWithdraw"
                  :disabled="loading"
                  class="flex-1 px-4 py-2 text-h5 text-card bg-primary rounded hover:bg-primary/90 disabled:opacity-50">
            <span v-if="loading">Submitting...</span>
            <span v-else>Submit</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Desktop Modal -->
  <div v-else class="fixed inset-0 z-50 bg-primary/30 flex items-center justify-center">
    <div class="bg-card w-full max-w-md rounded-lg shadow-lg">
      <div class="flex items-center justify-between border-b p-4">
        <h2 class="text-lg font-medium">Withdraw Funds</h2>
        <button @click="emit('close')" class="text-tertiary hover:text-tertiary-dark">
          ✕
        </button>
      </div>

      <div class="p-4">
        <!-- Zero Balance State -->
        <div v-if="availableBalance <= 0" class="text-center py-6">
          <div class="mb-4">
            <p class="text-tertiary text-h5">
              Your current available balance is ${{ availableBalance.toFixed(2) }}. <br /> 
              Please continue working on projects to earn funds, so that you can make your next withdraw.
            </p>
          </div>
        </div>

        <!-- Withdraw Form -->
        <div v-else>
          <div class="mb-4">
            <p class="text-h5 text-tertiary">Available Balance: <span class="font-semibold">${{ availableBalance.toFixed(2) }}</span></p>
          </div>

          <div class="mb-4">
            <label class="block text-h5 font-medium text-tertiary mb-2">Amount to Withdraw</label>
            <div class="relative">
              <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-tertiary">$</span>
              <input
                  v-model="amount"
                  type="number"
                  step="0.01"
                  min="1"
                  :max="availableBalance"
                  class="w-full pl-8 pr-3 py-2 border border-grey rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                  placeholder="0.00"
              />
            </div>
            <p v-if="errors?.amount" class="text-danger text-h5 mt-1">{{ errors.amount[0] }}</p>
          </div>

          <div class="mb-4">
            <label class="block text-h5 font-medium text-tertiary mb-2">Payment Method</label>
            <div class="space-y-2">
              <label class="flex items-center">
                <input type="radio" v-model="type" value="paypal" class="mr-2" />
                PayPal
              </label>
              <label class="flex items-center">
                <input type="radio" v-model="type" value="wise" class="mr-2" />
                Wise
              </label>
            </div>
            <p v-if="errors?.type" class="text-danger text-h5 mt-1">{{ errors.type[0] }}</p>
          </div>

          <p v-if="resultContainer" class="text-danger text-h5 mt-2">{{ resultContainer }}</p>
        </div>
      </div>

      <div class="flex justify-end gap-2 border-t p-4">
        <button @click="emit('close')"
                class="px-4 py-2 text-h5 border rounded text-tertiary hover:bg-greyLight">
          Cancel
        </button>

        <button v-if="availableBalance > 0" @click="submitWithdraw"
                :disabled="loading"
                class="px-4 py-2 text-h5 text-card bg-primary rounded hover:bg-primary/90 disabled:opacity-50">
          <span v-if="loading">Submitting...</span>
          <span v-else>Submit</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useAuthStore } from '@/store/auth'
import { useExpertStore } from '@/store/expert'

const emit = defineEmits(['close', 'success'])
const isMobile = screen.width <= 760

const amount = ref<number | null>(null)
const type = ref('')
const loading = ref(false)
const resultContainer = ref('')
const errors = ref<any>({})
const authStore = useAuthStore();
const expertStore = useExpertStore();

const availableBalance = computed(() => {
    return authStore.user.available_balance;
})

const submitWithdraw = async () => {
  loading.value = true
  errors.value = {}
  resultContainer.value = ''

  try {
    await expertStore.setExpertPayout({
      amount: amount.value,
      type: type.value
    });

    emit('success')
    emit('close')
  } catch (err: any) {
    if (err.response?.data?.errors) {
      errors.value = err.response.data.errors
    } else {
      resultContainer.value = err.response?.data?.message || 'An error occurred'
    }
  } finally {
    loading.value = false
  }
}

</script>