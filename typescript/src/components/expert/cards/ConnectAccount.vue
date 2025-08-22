<script setup lang="ts">
import {computed, ref} from 'vue'
import type { PayPalConnectionInterface } from "@/types.ts"
import FormStatusMessage from "@/components/common/FormStatusMessage.vue"
import {useExpertStore} from "@/store/expert.ts";
import {useLoaderStore} from "@/store/loader.ts";

const props = defineProps<{
  content: PayPalConnectionInterface
  isPaypal: boolean
  email: string
}>()

const loader = useLoaderStore();
const expertStore = useExpertStore()
const email = ref(props.email)
const updateStatus = ref('')
const isLoading = computed(() => loader.isLoadingState);
const triggerCounter = ref(0);

const resetStatus = () => {
  updateStatus.value = ''
}

async function handleEmailChange() {
  if (!email.value) {
    updateStatus.value = 'Please enter an email address.'
    return
  }

  const fieldName = props.isPaypal ? 'paypal_email' : 'wise_email'
  updateStatus.value = ''

  try {
    const payload = {
      profile:  {
        [fieldName]: email.value
      }
    }

    await expertStore.updateProfile(payload)

    updateStatus.value = 'success'
    triggerCounter.value++
  } catch (error: any) {
    let message = `Failed to update ${fieldName.replace('_', ' ')}.`

    if (error.response?.data) {
      const data = error.response.data
      const validationErrors = data.errors || {}

      // Check for validation errors
      if (validationErrors[fieldName]) {
        message = Array.isArray(validationErrors[fieldName])
            ? validationErrors[fieldName][0]
            : validationErrors[fieldName]
      } else if (data.message) {
        message = data.message
      }
    } else if (error.request) {
      message = 'No response from server. Please check your internet connection.'
    }

    updateStatus.value = message
    triggerCounter.value++
  }
}
</script>

<template>
  <div class="w-full bg-card border border-grey rounded-lg p-card-padding">
    <!-- Header Section -->
    <div class="flex items-center gap-4 mb-6">
      <div class="rounded-full overflow-hidden flex-shrink-0">
        <component :is="content.icon" class="text-sm" />
      </div>
      <div>
        <p class="text-primary font-semibold">{{ content.title }}</p>
        <h4 class="text-greyDark mt-1">{{ content.description }}</h4>
      </div>
    </div>

    <div class="space-y-4">
      <h5 class="block text-primary">{{ content.emailLabel }}</h5>

      <input
          type="email"
          v-model="email"
          :placeholder="content.emailPlaceholder"
          :disabled="isLoading"
          @change="handleEmailChange"
          class="w-full px-4 py-2 border border-grey rounded-md bg-white text-greyDark text-paragraph placeholder-greyDark focus:outline-none focus:ring-1 focus:ring-darkGray focus:border-darkGray disabled:opacity-50"
      />

      <FormStatusMessage
          :trigger="triggerCounter"
          @updateStatus="resetStatus"
          :status="updateStatus"
          :message="`Email connected successfully.`"
      />

      <p class="text-greyDark text-custom1 mt-3">{{ content.feeNote }}</p>
    </div>
  </div>
</template>