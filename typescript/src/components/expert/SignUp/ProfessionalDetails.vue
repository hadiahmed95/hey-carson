<template>
  <StepPanel>
    <div class="flex flex-col gap-8">
      <div class="flex flex-col gap-4">
        <div class="flex flex-row items-center justify-between">
          <h4 class="font-normal font-archivo text-primary tracking-10p">
            {{ listingType === 'freelancer' ? 'CREATE FREELANCER LISTING' : 'CREATE AGENCY LISTING' }}
          </h4>
          <h4 class="font-normal font-archivo text-primary tracking-10p">2/3</h4>
        </div>
        <h1 class="font-normal text-primary">Professional Details</h1>
        <p class="text-primary">
          Select your main role and the primary services you want to offer. Also, select the minimum project budget you’d be happy with.
        </p>
      </div>

      <div class="flex flex-col gap-8">
        <RoleDropdown
          v-model="formData.role"
          :error="errors.role"
        />
        <ServicesDropdown
          v-model="formData.services"
          :error="errors.services"
        />
        <BudgetDropdown
          v-model="formData.budget"
          :error="errors.budget"
        />

        <!-- Only for agencies -->
        <BaseInput
          v-if="listingType === 'agency'"
          label="Partner link from the Shopify partner directory"
          v-model="formData.partnerLink"
          :error="errors.partnerLink"
        />
        <ShopifyTierDropdown
          v-if="listingType === 'agency'"
          v-model="formData.shopifyTier"
        />

        <!-- Navigation Buttons -->
        <div class="flex justify-between gap-8">
          <BaseButton
            :isPrimary="false"
            @click="goToPrevious"
          >
            Back
          </BaseButton>
          <BaseButton :isPrimary="true" @click="goToNext">
            Next
          </BaseButton>
        </div>
      </div>
    </div>
  </StepPanel>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import BaseInput from '../../common/InputFields/BaseInput.vue'
import BaseButton from '../../common/InputFields/BaseButton.vue'
import ServicesDropdown from "../../common/InputFields/ServicesDropdown.vue"
import BudgetDropdown from "../../common/InputFields/BudgetDropdown.vue"
import ShopifyTierDropdown from "../../common/InputFields/ShopifyTierDropdown.vue"
import RoleDropdown from "../../common/InputFields/RoleDropdown.vue"
import StepPanel from "./StepPanel.vue";
import {isValidUrl} from "@/utils/helpers.ts";

const router = useRouter()

// Listing type
const listingType = ref<'freelancer' | 'agency'>('freelancer')

// Form data
const formData = reactive({
  role: '',
  services: [] as string[],
  budget: '',
  partnerLink: '',
  shopifyTier: '',
})

const errors = reactive({
  role: '',
  services: '',
  budget: '',
  partnerLink: '',
})

const validate = () => {
  let isValid = true
  Object.keys(errors).forEach(key => (errors[key as keyof typeof errors] = ''))

  if (!formData.role.trim()) {
    errors.role = 'Please select a role.'
    isValid = false
  }

  if (!formData.services.length) {
    errors.services = 'Please select at least one service.'
    isValid = false
  }

  if (!formData.budget) {
    errors.budget = 'Please select a minimum project budget.'
    isValid = false
  }

  if (listingType.value === 'agency') {
    if (!formData.partnerLink.trim()) {
      errors.partnerLink = 'Partner link is required.'
      isValid = false
    } else if (!isValidUrl(formData.partnerLink)) {
      errors.partnerLink = 'Invalid URL.'
      isValid = false
    }
  }

  return isValid
}

// Load listing type from localStorage
onMounted(() => {
  const type = localStorage.getItem('listingType')
  if (type === 'freelancer' || type === 'agency') {
    listingType.value = type
  } else {
    // fallback to start
    router.push('/expert/signup')
  }

  const saved = localStorage.getItem('professionalDetails')
  if (saved) Object.assign(formData, JSON.parse(saved))
})

// Navigation
const goToNext = () => {
  if (!validate()) return

  localStorage.setItem('professionalDetails', JSON.stringify(formData))
  router.push('/expert/signup/experience')
}

const goToPrevious = () => {
  router.push('/expert/signup/contact-info')
}
</script>
