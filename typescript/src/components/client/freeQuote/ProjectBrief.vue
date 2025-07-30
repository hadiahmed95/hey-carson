<template>
  <StepPanel>
    <div class="flex flex-col gap-8">
      <div class="flex flex-col gap-4">
        <div class="flex justify-between items-center">
          <p class="text-h4 font-normal font-archivo text-primary tracking-10p">
            FREE PROJECT QUOTE
          </p>
          <p class="text-h4 font-normal font-archivo text-primary tracking-10p">2/3</p>
        </div>
        <h1 class="font-normal text-primary">
          Receive a <i>free project quote</i> for your Shopify project
        </h1>
        <p class="text-primary">
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
                placeholder="e.g. I need help customizing the product page layout to include color swatches and size guides. Also interested in optimizing mobile speed."
                :rows="6"
                noStyle
                hideLabel
            />

            <div class="border-t border-lightGray">
              <label class="w-full block px-4 py-2 text-tertiary-dark font-normal cursor-pointer flex items-center gap-2">
                <img :src="attachFileIcon" class="w-5 h-5" />
                Attach files
                <input
                    type="file"
                    multiple
                    class="hidden"
                    @change="handleFileChange"
                />
              </label>
            </div>
          </div>
          <h5 v-if="errors.projectBrief" class="text-red-600 mt-2">
            {{ errors.projectBrief }}
          </h5>

          <div v-if="attachedFiles.length" class="mt-2 space-y-1">
            <p class="text-sm text-primary font-medium">Attached Files:</p>
            <ul class="text-sm text-gray-700 list-disc list-inside">
              <li v-for="(file, index) in attachedFiles" :key="index">
                {{ file.name }}
              </li>
            </ul>
          </div>
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

const attachedFiles = ref<File[]>([])

const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files?.length) {
    attachedFiles.value = Array.from(target.files)
  }
}

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

const attachFileIcon = new URL('../../../assets/icons/attachFile.svg', import.meta.url).href

onMounted(() => {
  // Load saved data if available
  const saved = localStorage.getItem('projectBrief')
  if (saved) {
    formData.value = JSON.parse(saved)
  }
})

const proceed = () => {
  if (!validate()) return

  const payload = {
    ...formData.value,
    attachedFiles: attachedFiles.value
  }

  localStorage.setItem('projectBrief', JSON.stringify(payload))

  router.push('/client/free-quote/account-info')
}

const goBack = () => {
  router.push('/client/free-quote/agency-details')
}
</script>