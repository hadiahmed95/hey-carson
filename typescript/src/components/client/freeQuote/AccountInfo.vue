<template>
  <StepPanel>
    <div class="flex flex-col gap-8">
      <div class="flex flex-col gap-4">
        <div class="flex justify-between items-center">
          <h4 class="font-normal font-archivo text-primary tracking-10p">
            FREE PROJECT QUOTE
          </h4>
          <h4 class="font-normal font-archivo text-primary tracking-10p">3/3</h4>
        </div>
        <h1 class="font-normal text-primary">You are almost done!</h1>
        <p class="text-primary">
          We just need a couple more details and you're all set!
        </p>
      </div>

      <div class="flex flex-col gap-8">
        <BaseInput
            label="Your Email Address"
            v-model="formData.email"
            placeholder="Enter your email"
            type="email"
            :error="errors.email"
        />

        <div class="flex gap-8">
          <BaseInput
              label="First Name"
              placeholder="First Name"
              v-model="formData.firstName"
              class="w-1/2"
              :error="errors.firstName"
          />
          <BaseInput
              label="Last Name"
              v-model="formData.lastName"
              placeholder="Last Name"
              class="w-1/2"
              :error="errors.lastName"
          />
        </div>

        <ShopifyTierDropdown v-model="formData.shopifyPlan" />

        <div class="flex flex-col gap-1">
          <label class="text-h5 font-normal font-archivo text-primary">Password</label>
          <PasswordInput v-model="formData.password" :error="errors.password" />
        </div>

        <div class="flex flex-col gap-1">
          <label class="block text-h5 font-normal font-archivo text-primary">Confirm Password</label>
          <PasswordInput v-model="formData.confirmPassword" :error="errors.confirmPassword" />
        </div>

        <div class="flex justify-between gap-8">
          <BaseButton :isPrimary="false" @click="goBack">Back</BaseButton>
          <BaseButton :loading-button="true" :isPrimary="true" @click="submitForm">Submit</BaseButton>
        </div>
      </div>
    </div>
  </StepPanel>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import BaseInput from '../../../components/common/InputFields/BaseInput.vue'
import BaseButton from '../../../components/common/InputFields/BaseButton.vue'
import PasswordInput from '../../../components/common/InputFields/PasswordInput.vue'
import ShopifyTierDropdown from '../../../components/common/InputFields/ShopifyTierDropdown.vue'
import { useAuthStore } from '@/store/auth.ts'
import StepPanel from './StepPanel.vue'
import {validateEmail} from "@/utils/helpers.ts";
import {useClientStore} from "@/store/client.ts";

const clientStore = useClientStore()
const router = useRouter()
const authStore = useAuthStore()
const formData = ref( {
  email: '',
  firstName: '',
  lastName: '',
  shopifyPlan: '',
  password: '',
  confirmPassword: ''
})

onMounted(() => {
  const saved = localStorage.getItem('accountInfo')
  if (saved) {
    formData.value = JSON.parse(saved)
  }

  authStore.logout()
})

const errors = ref({
  email: '',
  firstName: '',
  lastName: '',
  shopifyPlan: '',
  password: '',
  confirmPassword: ''
})

const validate = () => {
  let isValid = true

  errors.value = {
    email: '',
    firstName: '',
    lastName: '',
    shopifyPlan: '',
    password: '',
    confirmPassword: ''
  }

  if (!formData.value.email.trim()) {
    errors.value.email = 'Email is required.'
    isValid = false
  } else if (!validateEmail(formData.value.email)) {
    errors.value.email = 'Invalid email address.'
    isValid = false
  }

  if (!formData.value.firstName.trim()) {
    errors.value.firstName = 'First name is required.'
    isValid = false
  }

  if (!formData.value.lastName.trim()) {
    errors.value.lastName = 'Last name is required.'
    isValid = false
  }

  if (!formData.value.password) {
    errors.value.password = 'Password is required.'
    isValid = false
  } else if (formData.value.password.length < 8) {
    errors.value.password = 'Password must be at least 8 characters.'
    isValid = false
  }

  if (formData.value.confirmPassword !== formData.value.password) {
    errors.value.confirmPassword = 'Passwords do not match.'
    isValid = false
  }

  return isValid
}

const submitForm = async () => {
  if (!validate()) return

  try {
    const quotePreferences = JSON.parse(localStorage.getItem('quotePreferences') || '{}')
    const storeDetails = JSON.parse(localStorage.getItem('storeDetails') || '{}')
    const projectBrief = JSON.parse(localStorage.getItem('projectBrief') || '{}')

    const completeFormData = {
      ...quotePreferences,
      ...storeDetails,
      ...projectBrief,
      ...formData.value
    }

    const payload = {
      send_to_more_experts:      completeFormData.sendToMoreExperts,
      is_urgent:                 completeFormData.isUrgent,
      preferred_expert_id:       completeFormData.selectedExpert?.id,
      project_description:       completeFormData.projectBrief,
      project_name:              completeFormData.projectTitle,
      store_url:                 completeFormData.storeUrl,
      store_name:                completeFormData.storeName,
      email:                     completeFormData.email,
      first_name:                completeFormData.firstName,
      last_name:                 completeFormData.lastName,
      shopify_plan:              completeFormData.shopifyPlan,
      password:                  completeFormData.password
    }

    await clientStore.freeQuote(payload)

    if (authStore.token && authStore.user) {
      localStorage.removeItem('quotePreferences')
      localStorage.removeItem('storeDetails')
      localStorage.removeItem('projectBrief')
      localStorage.removeItem('accountInfo')

      await router.push('/client/dashboard')
    }
  } catch (error: any) {
    const response = error?.response

    if (response?.status === 422 && response.data?.errors) {
      const serverErrors = response.data.errors

      const fieldsStepMap: Record<string, string> = {
        preferred_expert_id: 'client-claim-expert',
        store_name: 'store-details',
        store_url: 'store-details',
        project_name: 'store-details',
        project_description: 'project-brief',
      }

      const stepRedirect = Object.keys(serverErrors).find(key => fieldsStepMap[key])
      if (stepRedirect) {
        localStorage.setItem('quoteServerErrors', JSON.stringify(serverErrors))
        await router.push({ name: fieldsStepMap[stepRedirect] })
        return
      }

      errors.value.email = serverErrors.email?.[0] || ''
      errors.value.firstName = serverErrors.first_name?.[0] || ''
      errors.value.lastName = serverErrors.last_name?.[0] || ''
      errors.value.password = serverErrors.password?.[0] || ''
      errors.value.shopifyPlan = serverErrors.shopify_plan?.[0] || ''
    } else {
      console.error('Unexpected error submitting free quote:', error)
    }
  }
}

const goBack = () => {
  localStorage.setItem('accountInfo', JSON.stringify(formData.value))
  router.push('/client/free-quote/project-brief')
}
</script>