<template>
  <StepPanel>
    <div class="flex flex-col gap-8">
      <div class="flex flex-col gap-4">
        <div class="flex flex-row items-center justify-between">
          <h4 class="font-normal font-archivo text-primary tracking-10p">
            {{ listingType === 'freelancer' ? 'CREATE FREELANCER LISTING' : 'CREATE AGENCY LISTING' }}
          </h4>
          <h4 class="font-normal font-archivo text-primary tracking-10p">1/3</h4>
        </div>
        <h1 class="font-normal text-primary">Contact Information</h1>
        <p class="text-primary">
          Let’s start with the information leads can use to contact you. <br>
          The email you enter here will be connected to your expert account.
        </p>
      </div>

      <div class="flex flex-col gap-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <BaseInput
            label="First Name"
            placeholder="First Name"
            v-model="formData.firstName"
            :error="errors.firstName"
          />
          <BaseInput
            label="Last Name"
            placeholder="Last Name"
            v-model="formData.lastName"
            :error="errors.lastName"
          />
        </div>

        <BaseInput
          label="Email"
          placeholder="Enter your email"
          v-model="formData.email"
          type="email"
          :error="errors.email"
        />
        <CountryDropdown
          v-model="formData.country"
          :error="errors.country"
        />

        <BaseInput
          v-if="listingType === 'freelancer'"
          label="Portfolio / Website URL (Optional)"
          v-model="formData.website"
        />

        <BaseInput
          v-if="listingType === 'agency'"
          label="Agency Name"
          v-model="formData.agencyName"
          :error="errors.agencyName"
        />

        <BaseInput
          label="LinkedIn URL"
          v-model="formData.linkedIn"
          :error="errors.linkedIn"
        />

        <!-- GROUP 6: Navigation Buttons -->
        <div class="flex justify-between gap-8">
          <BaseButton
              :isPrimary="false"
              @click="back"
          >
            Back
          </BaseButton>
          <BaseButton :isPrimary="true" @click="nextStep">
            Next
          </BaseButton>
        </div>
      </div>
    </div>
  </StepPanel>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import BaseInput from '../../common/InputFields/BaseInput.vue'
import BaseButton from '../../common/InputFields/BaseButton.vue'
import CountryDropdown from "../../common/InputFields/CountryDropdown.vue";
import StepPanel from "./StepPanel.vue";
import {isValidUrl, validateEmail} from "@/utils/helpers.ts";

const router = useRouter()
const route = useRoute()
const listingType = ref<'freelancer' | 'agency' | null>(null)

onMounted(() => {
  const storedType = localStorage.getItem('listingType')
  if (storedType === 'freelancer' || storedType === 'agency') {
    listingType.value = storedType
  } else {
    router.push('/expert/signup')
  }

  const saved = localStorage.getItem('contactInfo')
  if (saved) Object.assign(formData, JSON.parse(saved))

  const emailError = route.query['email-error']
  if (typeof emailError === 'string') {
    errors.email = decodeURIComponent(emailError)
    router.replace({ query: { ...route.query, 'email-error': undefined } })
  }
})

const formData = reactive({
  firstName: '',
  lastName: '',
  email: '',
  country: '',
  website: '',
  agencyName: '',
  linkedIn: '',
})

const errors = reactive({
  firstName: '',
  lastName: '',
  email: '',
  country: '',
  website: '',
  agencyName: '',
  linkedIn: ''
})

const validate = () => {
  let isValid = true
  Object.keys(errors).forEach(key => (errors[key as keyof typeof errors] = ''))

  if (!formData.firstName.trim()) {
    errors.firstName = 'First name is required.'
    isValid = false
  }
  if (!formData.lastName.trim()) {
    errors.lastName = 'Last name is required.'
    isValid = false
  }
  if (!formData.email.trim()) {
    errors.email = 'Email is required.'
    isValid = false
  } else if (!validateEmail(formData.email)) {
    errors.email = 'Invalid email address.'
    isValid = false
  }
  if (!formData.country.trim()) {
    errors.country = 'Country is required.'
    isValid = false
  }
  if (listingType.value === 'agency' && !formData.agencyName.trim()) {
    errors.agencyName = 'Agency name is required.'
    isValid = false
  }
  if (!formData.linkedIn.trim()) {
    errors.linkedIn = 'LinkedIn Url is required.'
    isValid = false
  }
  if (formData.linkedIn.trim() && !isValidUrl(formData.linkedIn)) {
    errors.linkedIn = 'Invalid LinkedIn URL.'
    isValid = false
  }

  return isValid
}

const nextStep = () => {
  if (!validate()) return

  localStorage.setItem('contactInfo', JSON.stringify(formData))
  router.push('/expert/signup/professional-details')
}
const back = () => {
  router.push('/expert/signup')
}
</script>
