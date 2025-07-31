<template>
  <StepPanel>
    <div class="flex flex-col gap-8">
      <div class="flex flex-col gap-4">
        <div class="flex justify-between items-center">
          <h4 class="font-normal font-archivo text-primary tracking-10p">
            Get Matched
          </h4>
          <h4 class="font-normal font-archivo text-primary">3/3</h4>
        </div>
        <h1 class="font-normal text-primary">
          We just need a few information so we can <i>confirm your identity.</i>
        </h1>
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
            v-model="formData.firstName"
            placeholder="First Name"
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
          <label for="password" class="text-h5 font-normal font-archivo text-primary">Password</label>
          <PasswordInput
            v-model="formData.password"
            :error="errors.password"
          />
        </div>
        <div class="flex flex-col gap-1">
          <label for="password" class="block text-h5 font-normal font-archivo text-primary">Confirm Password</label>
          <PasswordInput
            v-model="formData.confirmPassword"
            :error="errors.confirmPassword"
          />
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
import ShopifyTierDropdown from "../../../components/common/InputFields/ShopifyTierDropdown.vue"
import PasswordInput from "../../../components/common/InputFields/PasswordInput.vue"
import StepPanel from './StepPanel.vue'
import {validateEmail} from "@/utils/helpers.ts";
import {useAuthStore} from "@/store/auth.ts";
import {useClientStore} from "@/store/client.ts";

const clientStore = useClientStore()
const router = useRouter()
const authStore = useAuthStore()
const formData = ref({
  email: '',
  firstName: '',
  lastName: '',
  shopifyPlan: '',
  password: '',
  confirmPassword: ''
})

const errors = ref({
  email: '',
  firstName: '',
  lastName: '',
  shopifyPlan: '',
  password: '',
  confirmPassword: ''
})

onMounted(() => {
  const saved = localStorage.getItem('matchAccount')
  if (saved) formData.value = JSON.parse(saved)

  authStore.logout()
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

  const completeData = {
    ...JSON.parse(localStorage.getItem('matchDetails') || '{}'),
    ...JSON.parse(localStorage.getItem('matchBrief') || '{}'),
    ...formData.value
  }

  console.log('Submitting matched data:', completeData)

  const payload = {
    is_urgent: completeData.isUrgent,
    project_description: completeData.projectBrief,
    project_name: completeData.projectTitle,
    expert_slug: completeData.expertSlug,
    store_url: completeData.storeUrl,
    store_name: completeData.storeName,
    email: completeData.email,
    first_name: completeData.firstName,
    last_name: completeData.lastName,
    shopify_plan: completeData.shopifyPlan,
    password: completeData.password
  }

  try {
    await clientStore.getMatched(payload)

    if (authStore.token && authStore.user) {
      localStorage.removeItem('matchDetails')
      localStorage.removeItem('matchBrief')
      localStorage.removeItem('matchAccount')

      await router.push('/client/dashboard')
    }
  } catch (error: any) {
    const response = error?.response

    if (response?.status === 422 && response.data?.errors) {
      const serverErrors = response.data.errors

      const fieldsStepMap: Record<string, string> = {
        expert_slug: 'get-matched-details',
        store_name: 'get-matched-details',
        store_url: 'get-matched-details',
        project_name: 'get-matched-details',
        project_description: 'get-matched-brief',
      }

      const stepRedirect = Object.keys(serverErrors).find(key => fieldsStepMap[key])

      if (stepRedirect) {
        localStorage.setItem('matchServerErrors', JSON.stringify(serverErrors))

        const routeName = fieldsStepMap[stepRedirect]
        const query: Record<string, string> = {}

        if ('expert_slug' in serverErrors) {
          query.expert_slug = payload.expert_slug
        }

        await router.push({ name: routeName, query })
        return
      }

        errors.value.email = serverErrors.email?.[0] || ''
        errors.value.firstName = serverErrors.first_name?.[0] || ''
        errors.value.lastName = serverErrors.last_name?.[0] || ''
        errors.value.password = serverErrors.password?.[0] || ''
        errors.value.shopifyPlan = serverErrors.shopify_plan?.[0] || ''
    } else {
        console.error('Unexpected error:', error)
    }
  }
}

const goBack = () => {
  localStorage.setItem('matchAccount', JSON.stringify(formData.value))
  router.push('/client/get-matched/project-brief')
}

</script>