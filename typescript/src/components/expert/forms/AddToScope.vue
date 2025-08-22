<script setup lang="ts">
import { reactive, ref, computed } from 'vue'
import {formatDate} from "@/utils/date.ts";
import {useExpertStore} from "@/store/expert.ts";
import {useLoaderStore} from "@/store/loader.ts";

const expertStore = useExpertStore()

const isFocused = ref(false)
const loader = useLoaderStore();
const isLoading = computed(() => loader.isLoadingState);
const errors = reactive({
  deadline: null,
  hours: null,
  rate: null
})

const form = reactive({
  hourlyRate: null as number | null,
  estimatedTime: null as number | null,
  deadlineDate: null as string | null,
})

const props = defineProps<{
  buttonText?: string
  projectId?: number
}>()

const emit = defineEmits<{
  close: []
  quotesUpdated: []
}>()

const createOffer = async () => {
  try {
    // Clear previous errors
    errors.deadline = null
    errors.hours = null
    errors.rate = null

    if (!props.projectId) {
      return
    }

    const res = await expertStore.createQuoteOrOffer('projects/' + props.projectId + '/offer', {
      type: props.buttonText === 'Send quote to client' ? 'offer' : 'scope',
      hours: form.estimatedTime,
      deadline: form.deadlineDate,
      rate: form.hourlyRate
    })

    if (res.data) {
      emit('quotesUpdated')
    }

    emit('close')
  } catch (err: any) {

    console.log('Full error response:', err.response?.data) // Debug log

    const errorData = err.response?.data

    if (errorData?.errors) {
      errors.deadline = errorData.errors.deadline?.[0] || null
      errors.hours = errorData.errors.hours?.[0] || null
      errors.rate = errorData.errors.rate?.[0] || null
    } else if (errorData?.message) {
      console.error('API Error:', errorData.message)
    }

    setTimeout(() => {
      errors.deadline = null
      errors.hours = null
      errors.rate = null
    }, 5000)
  }
}

const displayValue = computed({
  get() {
    return isFocused.value
      ? (form.hourlyRate ?? '').toString() || ''
      : `$${(form.hourlyRate ?? 0).toFixed(2) || '0.00'}`
  },
  set(value) {
    const numericValue = parseFloat(value.replace(/[^0-9.]/g, ''))
    form.hourlyRate = isNaN(numericValue) ? 0 : numericValue
  }
})

function handleInput(event: any) {
  displayValue.value = event.target.value
}

function handleFocus() {
  isFocused.value = true
}

function handleBlur() {
  isFocused.value = false
}

const sendInvitation = () => {
  createOffer()
}
</script>

<template>
  <form @submit.prevent="sendInvitation" class="">
    <div class="mb-8">
      <label class="block text-h4 font-light mb-1 font-archivo" for="hourlyRate">Enter your hourly rate (USD)</label>
      <input
        id="hourlyRate"
        v-model="displayValue"
        @input="handleInput"
        @focus="handleFocus"
        @blur="handleBlur"
        type="text"
        placeholder="$125.00"
        class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
        :class="{ 'border-red-500': errors.rate }"
        required
      />
      <p v-if="errors.rate" class="text-red-500 text-sm mt-1">{{ errors.rate }}</p>
      <h5 class="text-tertiary-dark mt-1">
        This is your default hourly rate from your profile. You can adjust it up or down for this specific project.
      </h5>
    </div>

    <div class="mb-8">
      <label class="block text-h4 font-light mb-1" for="estimatedTime">
        Estimated time needed to complete this project (hours)
      </label>
      <input
        id="estimatedTime"
        v-model="form.estimatedTime"
        type="number"
        placeholder="0"
        class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
        :class="{ 'border-red-500': errors.hours }"
      />
      <p v-if="errors.hours" class="text-red-500 text-sm mt-1">{{ errors.hours }}</p>
      <h5 class="text-tertiary-dark mt-1">
        These are additional hours for the current project.
      </h5>
    </div>

    <div class="mb-8">
      <label class="block text-h4 font-light mb-1" for="deadlineDate">Deadline date</label>
      <div class="relative">
        <input
          id="deadlineDate"
          v-model="form.deadlineDate"
          type="date"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
          :class="{ 'text-transparent': form.deadlineDate, 'border-red-500': errors.deadline }"
        />
        <p
          v-if="form.deadlineDate"
          class="absolute top-0 left-0 w-full h-full flex items-center px-4 pointer-events-none"
        >
          {{ formatDate(form.deadlineDate) }}
        </p>
      </div>
      <p v-if="errors.deadline" class="text-red-500 text-sm mt-1">{{ errors.deadline }}</p>
      <h5 class="text-tertiary-dark mt-1">
        Set a deadline that's achievable, considering the current deadline date and additional tasks that need to be completed on this project.
      </h5>
    </div>

    <div class="mb-8">
      <label class="block text-h4 font-light mb-1" for="deadlineDate">Project quote</label>
      <h2 class="font-semibold">
        ${{ ((form.hourlyRate ?? 0) * (form.estimatedTime ?? 0)).toFixed(2) }}
      </h2>
    </div>

    <button
      type="submit"
      :disabled="isLoading"
      class="w-full bg-primary text-white text-paragraph font-semibold rounded-md py-2 hover:bg-gray-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
    >
      <span v-if="isLoading">Processing...</span>
      <span v-else>{{ buttonText ? buttonText : 'Add Hours to Scope' }}</span>
    </button>
  </form>
</template>

<style scoped>

</style>