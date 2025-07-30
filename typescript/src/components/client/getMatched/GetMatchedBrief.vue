<template>
  <StepPanel>
    <div class="flex flex-col gap-8">
      <div class="flex flex-col gap-4">
        <div class="flex justify-between items-center">
          <h4 class="font-normal font-archivo text-primary tracking-10p">
            Get Matched
          </h4>
          <h4 class="font-normal font-archivo text-primary">2/3</h4>
        </div>
        <h1 class="font-normal text-primary">
          No need to browse or search, <i class="font-besley">we’ll match you</i> with the perfect expert for your Shopify project.
        </h1>
        <p class="font-normal font-archivo text-primary">
          Describe your project or the challenge you're facing to receive a free project quote from one or more experts.
        </p>
      </div>

      <div class="flex flex-col gap-8">
        <div class="flex flex-col gap-1">
          <label class="block text-h5 font-normal font-archivo text-primary">Project Brief</label>
          <div class="rounded-md border border-lightGray overflow-hidden">
            <BaseInput
                v-model="formData.projectBrief"
                textarea
                placeholder="Please try to be as detailed as possible, as this helps us provide you with best possible solutions ..."
                :rows="10"
                noStyle
                hideLabel
            />
            <div class="border-t border-lightGray">
              <button class="w-full px-4 py-2 text-paragraph text-tertiary-dark font-normal flex items-center gap-2">
                <img :src="attachFileIcon" class="w-5 h-5" />
                Attach files
              </button>
            </div>
          </div>
          <h5 v-if="errors.projectBrief" class="text-red-600 mt-2">
            {{ errors.projectBrief }}
          </h5>
        </div>

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

const router = useRouter()
const formData = ref({
  projectBrief: ''
})

const errors = ref({
  projectBrief: ''
})
const attachFileIcon = new URL('../../../assets/icons/attachFile.svg', import.meta.url).href

onMounted(() => {
  const saved = localStorage.getItem('matchBrief')
  if (saved) formData.value = JSON.parse(saved)
})

const validate = () => {
  let isValid = true
  errors.value.projectBrief = ''

  if (!formData.value.projectBrief.trim()) {
    errors.value.projectBrief = 'Project brief is required.'
    isValid = false
  } else if (formData.value.projectBrief.trim().length < 20) {
    errors.value.projectBrief = 'Please provide more detail (at least 20 characters).'
    isValid = false
  }

  return isValid
}

const proceed = () => {
  if (!validate()) return

  localStorage.setItem('matchBrief', JSON.stringify(formData.value))
  router.push('/client/get-matched/account-info')
}

const goBack = () => {
  router.push('/client/get-matched')
}
</script>