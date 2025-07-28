<template>
  <StepPanel>
    <div class="flex flex-col gap-8">
      <div class="flex flex-col gap-4">
        <div class="flex justify-between items-center">
          <h4 class="font-normal font-archivo text-primary tracking-10p">CREATE YOUR LISTING</h4>
        </div>
        <h1 class="font-normal text-primary" v-html="subHeading" />
        <p class="text-primary" v-html="description" />
      </div>

      <div class="flex flex-col gap-4">
        <p>Select the type of listing you want to create:</p>
        <div
          :class="[
            'border rounded-md p-4 cursor-pointer gap-1',
            selected === 'freelancer' ? 'bg-gray-100 border-primary' : 'hover:bg-gray-100',
            errors.selected ? 'border-red-500' : ''
          ]"
          @click="select('freelancer')"
        >
          <h3 class="font-semibold text-primary">Freelancer</h3>
          <h4>Create your personal listing as an independent contractor.</h4>
        </div>

        <div
          :class="[
            'border rounded-md p-4 cursor-pointer gap-1',
            selected === 'agency' ? 'bg-gray-100 border-primary' : 'hover:bg-gray-100',
            errors.selected ? 'border-red-500' : ''
          ]"
          @click="select('agency')"
        >
          <h3 class="font-semibold text-primary">Shopify Agency</h3>
          <h4>Create a listing for the Shopify agency you own or work for.</h4>
        </div>
        <h5 v-if="errors.selected" class="text-red-600">
          {{ errors.selected }}
        </h5>
      </div>

      <div class="flex flex-col gap-2">
        <BaseButton :isPrimary="true" @click="proceed">Continue</BaseButton>
        <BaseButton :isPrimary="false" @click="goBack">Back to Website</BaseButton>
      </div>
    </div>
  </StepPanel>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import BaseButton from '../../common/InputFields/BaseButton.vue'
import StepPanel from "./StepPanel.vue";

const router = useRouter()
const selected = ref<'freelancer' | 'agency' | null>(null)
const errors = ref({ selected: '' })

const subHeading = `The next-gen Shopify services directory for <i>agencies, freelancers and consultants</i>`
const description = `Showcase your expertise and experience and let clients come to you. Join an exclusive directory built for top Shopify experts like you.`

const select = (type: 'freelancer' | 'agency') => {
  selected.value = type
  errors.value.selected = ''
}

const proceed = () => {
  if (!selected.value) {
    errors.value.selected = 'Please select a listing type before continuing.'
    return
  }

  localStorage.setItem('listingType', selected.value)
  router.push('/expert/signup/contact-info')
}

const goBack = () => {
  window.location.href = '/'
}
</script>
