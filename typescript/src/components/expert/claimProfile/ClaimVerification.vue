<template>
  <StepPanel>
    <div class="flex flex-col gap-8">
      <div class="flex flex-col gap-4">
        <div class="flex justify-between items-center">
          <h4 class="font-normal font-archivo text-primary tracking-10p">
            Claim Directory Listing
          </h4>
          <h4 class="font-normal font-archivo text-primary">2/2</h4>
        </div>
        <h1 class="font-normal text-primary">
          We just need a few information so we can <i>confirm your identity.</i>
        </h1>
      </div>

      <div class="flex flex-col gap-8">
        <BaseInput label="Company Name" v-model="formData.companyName" />
        <BaseInput label="Company Website" v-model="formData.companyWebsite" />
        <BaseInput label="Your Name" v-model="formData.yourName" />
        <BaseInput label="Company/Workspace Email" v-model="formData.companyEmail" />

        <div class="flex flex-col gap-2">
          <BaseButton :isPrimary="true" @click="submitClaim">Submit Claim Request</BaseButton>
          <BaseButton :isPrimary="false" @click="goBack">Back</BaseButton>
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
import StepPanel from './StepPanel.vue'

const router = useRouter()
const formData = ref({
  companyName: '',
  companyWebsite: '',
  yourName: '',
  companyEmail: ''
})

onMounted(() => {
  // Load any previously saved data
  const saved = localStorage.getItem('claimVerification')
  if (saved) {
    formData.value = JSON.parse(saved)
  }
})

const submitClaim = () => {
  // Save data and submit
  localStorage.setItem('claimVerification', JSON.stringify(formData.value))

  // Here you would typically send to API
  console.log('Claim Submitted:', formData.value)

  // Clear storage and redirect
  localStorage.removeItem('claimVerification')
  router.push('/claim-submitted')
}

const goBack = () => {
  // Save data before navigating back
  localStorage.setItem('claimVerification', JSON.stringify(formData.value))
  router.push('/expert/claim-profile')
}
</script>