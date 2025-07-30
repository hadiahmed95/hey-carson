<script setup lang="ts">
import { reactive } from 'vue'
import Arrow from '../../../assets/icons/arrow.svg'

const form = reactive({
  fullName: '',
  companyEmail: '',
  companyName: '',
  website: '',
  projectName: '',
  hiredOnShopexperts: 'Yes',
  repeatedClient: 'Yes',
  projectValue: '',
  message: `Hey [Client Name],

I hope you’re pleased with how [Project Name] turned out. Could you take 2-3 minutes to leave a quick review? Your feedback helps me improve and build my shopexperts profile so future clients can find me more easily.

Thank you so much for your support!`
})

const sendInvitation = () => {
  console.log('Sending invitation...', form)
}
</script>

<template>
  <form @submit.prevent="sendInvitation" class="space-y-4">
    <div class="mb-5">
      <label class="block text-h4 font-light mb-1" for="fullName">Client Full Name</label>
      <input
          id="fullName"
          v-model="form.fullName"
          type="text"
          placeholder="Enter Client full name"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
          required
      />
    </div>

    <div class="mb-5">
      <label class="block text-h4 font-light mb-1" for="companyEmail">
        Client Email
      </label>
      <input
          id="companyEmail"
          v-model="form.companyEmail"
          type="text"
          placeholder="example@gmail.com"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
      />
    </div>

    <div class="mb-5">
      <label class="block text-h4 font-light mb-1" for="companyEmail">
        Client Company Name
      </label>
      <input
          id="companyName"
          v-model="form.companyName"
          type="text"
          placeholder="www.companywebsite.com"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
      />
    </div>

    <div class="mb-5">
      <label class="block text-h4 font-light mb-1" for="website">Client Company Website</label>
      <input
          id="website"
          v-model="form.website"
          type="url"
          placeholder="www.companywebsite.com"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
      />
    </div>

    <div class="mb-5">
      <label class="block text-h4 font-light mb-1" for="projectName">Project Name</label>
      <input
          id="projectName"
          v-model="form.projectName"
          type="url"
          placeholder="Enter project name"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
      />
    </div>

    <div class="mb-5">
      <label class="block text-h4 font-light mb-1" for="hiredOnShopexperts">
        Hired on Shopexperts
      </label>
      <select
          id="hiredOnShopexperts"
          v-model="form.hiredOnShopexperts"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
      >
        <option>Yes</option>
        <option>No</option>
      </select>
    </div>

    <div class="mb-5">
      <label class="block text-h4 font-light mb-1" for="repeatedClient">
        Repeated Client
      </label>
      <select
          id="repeatedClient"
          v-model="form.repeatedClient"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
      >
        <option>Yes</option>
        <option>No</option>
      </select>
    </div>

    <div class="mb-5">
      <label class="block text-h4 font-light mb-1" for="projectValue">
        Project Value
      </label>
      <select
          id="projectValue"
          v-model="form.projectValue"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
      >
        <option disabled value="" class="text-gray-100">Select project value range</option>
        <option>less than 100</option>
        <option>100 - 200</option>
        <option>200 - 300</option>
        <option>300 - 400</option>
        <option>greater than 400</option>
      </select>
    </div>

    <div class="mb-5">
      <label class="block text-h4 font-light mb-1" for="message">Request a Review Message</label>
      <textarea
          id="message"
          v-model="form.message"
          rows="8"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph text-gray-500"
      />
    </div>

    <button
        type="submit"
        class="w-full bg-primary text-white text-paragraph font-normal rounded-md py-3 flex items-center justify-center gap-2 hover:bg-gray-800 transition-colors"
    >
      Send Invitation
      <Arrow />
    </button>
  </form>
</template>
