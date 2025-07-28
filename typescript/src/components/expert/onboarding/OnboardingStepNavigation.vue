<template>
  <div
      class="flex flex-row justify-between border border-gray-200 bg-white rounded-xl shadow p-6 w-[53.6875rem] h-[3.5rem]"
  >
    <RouterLink
        v-for="(step, index) in steps"
        :key="step.name"
        :to="step.path"
        class="flex items-center gap-2"
    >
      <div
          class="w-6 h-6 rounded-[4px] flex items-center justify-center"
          :class="[
          index === currentStepIndex
            ? 'bg-primary text-white'
            : index < currentStepIndex
              ? 'bg-accent text-black'
              : 'bg-lightGray text-graniteGray'
        ]"
      >
        <component
            :is="step.locked
            ? (index === currentStepIndex ? WhiteLock : Lock)
            : 'span'"
        >
          <template v-if="!step.locked">{{ index + 1 }}</template>
        </component>
      </div>

      <p
          :class="[
          'font-medium text-sm',
          index === currentStepIndex
            ? 'text-primary'
            : index < currentStepIndex
              ? 'text-primary'
              : 'text-tertiary-dark'
        ]"
      >
        {{ step.label }}
      </p>
    </RouterLink>
  </div>
</template>

<script setup lang="ts">
import { useRoute, RouterLink } from 'vue-router'
import { watchEffect, ref } from 'vue'
import Lock from '../../../assets/icons/lock.svg'
import WhiteLock from '../../../assets/icons/lock-white.svg'

const steps = [
  { name: 'personalDetails', label: 'Personal & Business Details', path: '/expert/onboarding-steps/personalDetails', locked: false },
  {
    name: 'serviceCategories',
    label: 'Service Categories',
    path: '/expert/onboarding-steps/serviceCategories',
    locked: false
  },
  {
    name: 'packagedServices',
    label: 'Packaged Services',
    path: '/expert/onboarding-steps/packagedServices',
    locked: false
  },
  {name: 'customerStories', label: 'Customer Stories', path: '/expert/onboarding-steps/customerStories', locked: true},
  {name: 'faq', label: 'FAQ', path: '/expert/onboarding-steps/faq', locked: true},
]

const route = useRoute()
const currentStepIndex = ref(0)

watchEffect(() => {
  currentStepIndex.value = steps.findIndex(step =>
      route.path.includes(step.path)
  )
})
</script>
