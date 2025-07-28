<template>
  <StepPanel>
    <div class="flex flex-col gap-8">
      <div class="flex flex-col gap-4">
        <div class="flex flex-row items-center justify-between">
          <h4 class="font-normal font-archivo text-primary tracking-10p">
            {{ listingType === 'freelancer' ? 'CREATE FREELANCER LISTING' : 'CREATE AGENCY LISTING' }}
          </h4>
          <h4 class="font-normal font-archivo text-primary tracking-10p">3/3</h4>
        </div>
        <h1 class="font-normal text-primary">You’re almost done!</h1>
        <p class="text-primary">
          We'd love to hear about your experience. Feel free to share your tech
          stack, apps you use daily, cool projects you've worked on – anything <br>
          you'd like us to know.
        </p>
      </div>

      <div class="flex flex-col gap-8">
        <BaseInput
            label="Share a short bio (Optional)"
            v-model="formData.bio"
            textarea
            :rows="6"
        />

        <!-- Navigation Buttons -->
        <div class="flex justify-between gap-8">
          <BaseButton :isPrimary="false" @click="goToPrevious">
            Back
          </BaseButton>
          <BaseButton :isPrimary="true" @click="submitForm">
            Submit Application
          </BaseButton>
        </div>
      </div>
    </div>
    <div v-if="isSuccess" class="bg-green-50 border border-green-300 p-6 rounded-md text-green-900">
      <p class="mb-2">
        Thanks for submitting your application to join our freelancer network.
      </p>
      <p>Shopexperts Team</p>
    </div>
  </StepPanel>
</template>

<script setup lang="ts">
import {onMounted, reactive, ref} from 'vue'
import { useRouter } from 'vue-router'
import BaseInput from '../../common/InputFields/BaseInput.vue'
import BaseButton from '../../common/InputFields/BaseButton.vue'
import StepPanel from "./StepPanel.vue";
import {useRegisterStore} from "@/store/register.ts";

const router = useRouter()
const registerStore = useRegisterStore()

const listingType = ref<'freelancer' | 'agency'>('freelancer')
const isSuccess = ref<boolean>(false);

onMounted(() => {
  const type = localStorage.getItem('listingType')
  if (type === 'freelancer' || type === 'agency') {
    listingType.value = type
  } else {
    // fallback to start
    router.push('/expert/signup')
  }

  const saved = localStorage.getItem('experience')
  if (saved) Object.assign(formData, JSON.parse(saved))
})

const formData = reactive({
  bio: ''
})

const validate = () => {
  const contactInfo = JSON.parse(localStorage.getItem('contactInfo') || '{}')
  const professionalDetails = JSON.parse(localStorage.getItem('professionalDetails') || '{}')
  const listingType = localStorage.getItem('listingType')

  if (
      !contactInfo.firstName ||
      !contactInfo.lastName ||
      !contactInfo.email ||
      !contactInfo.country ||
      (listingType === 'agency' && !contactInfo.agencyName)
  ) {
    console.warn('Missing contact info data')
    return false
  }

  if (
      !professionalDetails.role ||
      !professionalDetails.services?.length ||
      !professionalDetails.budget ||
      (listingType === 'agency' && !professionalDetails.partnerLink)
  ) {
    console.warn('Missing professional details data')
    return false
  }

  return true
}

const submitForm = async () => {
  localStorage.setItem('experience', JSON.stringify(formData))

  if (!validate()) return

  try {
    const storedType = { company_type: localStorage.getItem('listingType') }
    const contactInfo = JSON.parse(localStorage.getItem('contactInfo') || '{}')
    const professionalDetails = JSON.parse(localStorage.getItem('professionalDetails') || '{}')

    const completeFormData = {
      ...storedType,
      ...contactInfo,
      ...professionalDetails,
      ...formData
    }

    const payload = {
      first_name:                 completeFormData.firstName,
      last_name:                  completeFormData.lastName,
      email:                      completeFormData.email,
      about:                      completeFormData.bio,
      min_project_budget:         completeFormData.budget,
      company_type:               completeFormData.company_type,
      country:                    completeFormData.country,
      linkedIn_url:               completeFormData.linkedIn,
      partner_link_directory:     completeFormData.partnerLink,
      role:                       completeFormData.role,
      services:                   completeFormData.services,
      url:                        completeFormData.website,
      shopify_plan:               completeFormData.shopifyTier,
    }

    const response = await registerStore.signupExpert(payload)
    console.log(response)
    console.log(isSuccess.value)

    if (response.data.status) {
      isSuccess.value = true
      setTimeout(() => {
        localStorage.removeItem('listingType')
        localStorage.removeItem('contactInfo')
        localStorage.removeItem('professionalDetails')
        localStorage.removeItem('experience')
        router.push('/expert/login')
        isSuccess.value = false
      }, 8000)
    }
  } catch (error: any) {
    const emailErrors = error?.response?.data?.errors?.email

    if (Array.isArray(emailErrors) && emailErrors.length > 0) {
      const emailErrorMessage = encodeURIComponent(emailErrors[0])
      await router.push(`/expert/signup/contact-info?email-error=${emailErrorMessage}`)
    } else {
      console.log('Error submitting form:', error)
    }
  }
}

const goToPrevious = () => {
  router.push('/expert/signup/professional-details')
}
</script>
