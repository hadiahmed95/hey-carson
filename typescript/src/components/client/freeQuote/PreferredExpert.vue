<template>
  <StepPanel>
    <div class="flex flex-col gap-8">
      <div class="flex flex-col gap-4">
        <h4 class="font-normal font-archivo text-primary tracking-10p">
          FREE PROJECT QUOTE
        </h4>
        <h1 class="font-normal text-primary">
          Receive a <i>free project quote</i> for your Shopify project
        </h1>
        <p class="text-primary">
          Describe your project or the challenge you're facing to receive a free project quote from one or more experts.
        </p>
      </div>

      <div class="flex flex-col gap-8">
        <div class="flex flex-col gap-1">
          <h5 class="text-tertiary-dark font-normal">Preferred Expert</h5>
          <div class="relative w-full">
            <!-- Input -->
            <input
                type="text"
                v-model="search"
                @focus="handleInputFocus"
                @blur="handleInputBlur"
                @input="fetchExperts"
                placeholder="Search expert by name..."
                class="w-full border border-lightGray rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-primary"
            />

            <!-- Dropdown -->
            <ul
                v-if="open && experts.length"
                @mousedown="handleDropdownMouseDown"
                class="absolute z-10 mt-1 w-full bg-white border border-lightGray rounded-md shadow-lg max-h-60 overflow-auto"
            >
              <li
                  v-for="expert in experts"
                  :key="expert.id"
                  @click="selectExpert(expert)"
                  class="flex items-center gap-4 px-4 py-3 cursor-pointer hover:bg-gray-100"
              >
                <img
                    :src="getS3URL(expert.photo) || fallbackImage"
                    class="w-12 h-12 rounded-full object-cover"
                />
                <div>
                  <p class="font-archivo font-semibold">{{ expert.first_name }} {{ expert.last_name }}</p>
                  <h4 class="text-primary font-normal">{{ expert.profile.role }}</h4>
                </div>
              </li>
            </ul>

            <!-- Selected display -->
            <div
                v-if="formData.selectedExpert"
                class="mt-4 border border-lightGray rounded-sm px-4 py-2 flex items-center gap-4"
            >
              <img
                  :src="getS3URL(formData.selectedExpert?.photo) || fallbackImage"
                  class="w-12 h-12 rounded-full object-cover"
              />
              <div>
                <p class="font-archivo font-semibold">{{ formData.selectedExpert?.first_name }} {{ formData.selectedExpert?.last_name }}</p>
                <h4 class="text-primary font-normal">{{ formData.selectedExpert?.profile?.role }}</h4>
              </div>
            </div>
          </div>
          <h5 v-if="errors.expert" class="text-red-600 mt-2">
            {{ errors.expert }}
          </h5>
<!--          <div class="border border-lightGray rounded-md p-4 flex items-center gap-4">-->
<!--            <img src="../../../assets/icons/prefferedExpert.png" class="w-12 h-12 rounded-full object-cover" />-->
<!--            <div>-->
<!--              <p class="font-archivo font-semibold">Jessica Russwelt</p>-->
<!--              <p class="text-h4 text-primary font-normal">Shopify Developer</p>-->
<!--            </div>-->
<!--          </div>-->
        </div>

        <div class="flex items-start gap-4">
          <input
              type="checkbox"
              v-model="formData.sendToMoreExperts"
              class="translate-y-[2px] rounded-[4px] border border-lightGray accent-primary hover:accent-primary cursor-pointer"
          />
          <div>
            <h4 class="text-primary font-semibold">
              Send this quote request to 3 additional experts
            </h4>
            <h5 class="text-primary font-normal">
              We'll automatically forward your quote request to three additional experts who match your criteria, so you can compare multiple quotes.
            </h5>
          </div>
        </div>

        <div class="flex items-start gap-4">
          <input
              type="checkbox"
              v-model="formData.isUrgent"
              class="translate-y-[2px] rounded-[4px] border border-lightGray accent-primary hover:accent-primary cursor-pointer"
          />
          <div>
            <h4 class="text-primary font-semibold">This project is urgent</h4>
            <h5 class="text-primary font-normal">
              If you need a fast turnaround, let experts know your project is urgent.
            </h5>
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
import {onMounted, ref} from 'vue'
import {useRoute, useRouter} from 'vue-router'
import StepPanel from './StepPanel.vue'
import BaseButton from '../../../components/common/InputFields/BaseButton.vue'
import type { IExpertt } from '@/types.ts'
import {getS3URL} from "@/utils/helpers.ts";
import {useCommonStore} from "@/store/common.ts";

const commonStore = useCommonStore()
const search = ref( '')
const experts = ref<any[]>([])
const open = ref(false)

const route = useRoute()
const router = useRouter()

const fallbackImage = '../../../assets/icons/prefferedExpert.png'
const formData = ref<{
  sendToMoreExperts: boolean,
  isUrgent: boolean,
  selectedExpert: IExpertt | null
}>({
  sendToMoreExperts: false,
  isUrgent: false,
  selectedExpert: null
})

const dropdownTimeout = ref<NodeJS.Timeout | null>(null)

onMounted(async () => {
  try {
    const storedErrors = JSON.parse(localStorage.getItem('quoteServerErrors') || '{}')
    errors.value.expert = storedErrors.preferred_expert_id?.[0] || ''
    localStorage.removeItem('quoteServerErrors')

    if (!route.query.expert)
      return

    const queryExpert = route.query.expert as string
    experts.value = await commonStore.getExperts({ all: true })

    for (const expert of experts.value) {
      if (expert.first_name.toLowerCase() + '-' + expert.last_name.toLowerCase() === queryExpert.toLowerCase()) {
        formData.value.selectedExpert = expert
      }
    }
  } catch (err) {
    console.error('Error fetching experts:', err)
  }
})

const errors = ref({
  expert: ''
})

const proceed = () => {
  errors.value.expert = ''

  if (!formData.value.selectedExpert) {
    errors.value.expert = 'Please select a preferred expert before continuing.'
    return
  }

  localStorage.setItem('quotePreferences', JSON.stringify(formData.value))
  router.push('/client/free-quote/agency-details')
}

const goBackToWebsite = () => {
  window.location.href = '/'
}

const fetchExperts = async () => {
  if (!search.value.trim()) {
    experts.value = []
    return
  }

  try {
    experts.value = await commonStore.getExperts({search: search.value})
    console.log(experts.value)
  } catch (err) {
    console.error('Error fetching experts:', err)
  }
}

const selectExpert = (expert: any) => {
  formData.value.selectedExpert = expert
  open.value = false
  console.log(formData.value)
}

// New function to handle input focus
const handleInputFocus = () => {
  // Clear any existing timeout when input is focused
  if (dropdownTimeout.value) {
    clearTimeout(dropdownTimeout.value)
    dropdownTimeout.value = null
  }
  open.value = true
}

// New function to handle input blur with delay
const handleInputBlur = () => {
  // Use setTimeout to allow click events on dropdown items to fire first
  dropdownTimeout.value = setTimeout(() => {
    open.value = false
  }, 150) // 150ms delay allows click events to register
}

// New function to handle dropdown mousedown (prevents blur)
const handleDropdownMouseDown = (event: Event) => {
  event.preventDefault() // Prevents input from losing focus
}
</script>