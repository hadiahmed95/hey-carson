<template>
  <StepPanel>
    <div class="flex flex-col gap-8">
      <div class="flex flex-col gap-4">
        <div class="flex justify-between items-center">
          <h4 class="font-normal font-archivo text-primary tracking-10p">
            Get Matched
          </h4>
          <h4 class="font-normal font-archivo text-primary">1/3</h4>
        </div>
        <h1 class="font-normal text-primary">
          No need to browse or search, <i class="font-besley">we’ll match you</i> with the perfect expert for your Shopify project.
        </h1>
        <p class="font-normal font-archivo text-primary">
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
          placeholder="https://yourstore.myshopify.com or https://youragency.com"
          :error="errors.storeUrl"
        />
        <BaseInput
          label="Project Title"
          v-model="formData.projectTitle"
          placeholder="Give your project a short and descriptive title"
          :error="errors.projectTitle"
        />

        <div class="flex items-start gap-4">
          <input
              type="checkbox"
              v-model="formData.isUrgent"
              class="translate-y-[2px] rounded-[4px] border border-lightGray accent-primary hover:accent-primary cursor-pointer"
          />
          <div>
            <p class="text-h4 text-primary font-semibold">This project is urgent</p>
            <p class="text-h5 text-primary font-normal">
              If you need a fast turnaround, let experts know your project is urgent.
            </p>
          </div>
        </div>

        <div class="flex flex-col gap-2">
          <BaseButton :isPrimary="true" @click="proceed">Continue</BaseButton>
          <BaseButton :isPrimary="false" @click="goBackToWebsite">Back to Website</BaseButton>
        </div>
      </div>
    </div>
  </StepPanel>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import BaseInput from '../../../components/common/InputFields/BaseInput.vue'
import BaseButton from '../../../components/common/InputFields/BaseButton.vue'
import StepPanel from './StepPanel.vue'
import {isValidUrl} from "@/utils/helpers.ts";

const route = useRoute()
const router = useRouter()
const formData = ref({
  storeName: '',
  storeUrl: '',
  projectTitle: '',
  isUrgent: false,
  expertSlug: ''
})

const errors = ref({
  storeName: '',
  storeUrl: '',
  projectTitle: ''
})

onMounted(() => {
  const saved = localStorage.getItem('matchDetails')
  if (saved) formData.value = JSON.parse(saved)

  formData.value.expertSlug = route.query.expert as string
  console.log('Expert slug:', formData.value.expertSlug)
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
    errors.value.storeUrl = 'Please enter a valid URL (e.g., https://yourstore.com).'
    isValid = false
  }

  if (!formData.value.projectTitle.trim()) {
    errors.value.projectTitle = 'Project title is required.'
    isValid = false
  }

  return isValid
}

const proceed = () => {
  if (!validate()) return

  localStorage.setItem('matchDetails', JSON.stringify(formData.value))
  router.push('/client/get-matched/project-brief')
}

const goBackToWebsite = () => {
  window.location.href = '/'
}
</script>