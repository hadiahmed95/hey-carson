<template>
  <StepPanel>
    <div class="flex flex-col gap-8">
      <div class="flex flex-col gap-4">
        <div class="flex justify-between items-center">
          <h4 class="font-normal font-archivo text-primary tracking-10p">
            FREE PROJECT QUOTE
          </h4>
          <h4 class="font-normal font-archivo text-primary tracking-10p">1/3</h4>
        </div>
        <h1 class="font-normal text-primary">
          Receive a <i>free project quote</i> for your Shopify project
        </h1>
        <p class="text-primary">
          Describe your project or the challenge you're facing to receive a free project quote from one or more experts.
        </p>
      </div>

      <div class="flex flex-col gap-8">
        <BaseInput
            label="Shopify Store or Agency Name"
            v-model="formData.storeName"
            placeholder="TrendyThreads Co."
            :error="errors.storeName"
        />
        <BaseInput
            label="Shopify Store URL or Agency Website"
            v-model="formData.storeUrl"
            placeholder="Please enter your store or agency url"
            :error="errors.storeUrl"
        />
        <BaseInput
            label="Project Title"
            v-model="formData.projectTitle"
            placeholder="Give your project a short and descriptive title"
            :error="errors.projectTitle"
        />

        <div class="flex justify-between gap-8">
          <BaseButton :isPrimary="false" @click="goBack">Back</BaseButton>
          <BaseButton :isPrimary="true" @click="proceed">Next</BaseButton>
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
import {isValidUrl} from "@/utils/helpers.ts";

const router = useRouter()
const formData = ref({
  storeName: '',
  storeUrl: '',
  projectTitle: ''
})

const errors = ref({
  storeName: '',
  storeUrl: '',
  projectTitle: ''
})

const validate = () => {
  let isValid = true
  errors.value = {
    storeName: '',
    storeUrl: '',
    projectTitle: ''
  }

  if (!formData.value.storeName.trim()) {
    errors.value.storeName = 'Store or agency name is required.'
    isValid = false
  }

  if (!formData.value.storeUrl.trim()) {
    errors.value.storeUrl = 'Store URL or website is required.'
    isValid = false
  } else if (!isValidUrl(formData.value.storeUrl.trim())) {
    errors.value.storeUrl = 'Please enter a valid URL.'
    isValid = false
  }

  if (!formData.value.projectTitle.trim()) {
    errors.value.projectTitle = 'Project title is required.'
    isValid = false
  }

  return isValid
}

onMounted(() => {
  const saved = localStorage.getItem('storeDetails')
  if (saved) formData.value = JSON.parse(saved)

  const storedErrors = JSON.parse(localStorage.getItem('quoteServerErrors') || '{}')
  errors.value.storeName = storedErrors.store_name?.[0] || ''
  errors.value.storeUrl = storedErrors.store_url?.[0] || ''
  errors.value.projectTitle = storedErrors.project_name?.[0] || ''
  localStorage.removeItem('quoteServerErrors')
})

const proceed = () => {
  if (!validate()) return

  localStorage.setItem('storeDetails', JSON.stringify(formData.value))
  router.push('/client/free-quote/project-brief')
}

const goBack = () => {
  router.push('/client/free-quote')
}
</script>