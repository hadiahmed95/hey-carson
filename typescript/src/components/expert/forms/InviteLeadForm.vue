<script setup lang="ts">
import { reactive } from 'vue'
import Arrow from '../../../assets/icons/arrow.svg'

const form = reactive({
  fullName: '',
  email: '',
  website: '',
  shopifyPlan: '',
  message: `Hi [Lead Full Name],

I hope you’re doing well. I’d love to continue our collaboration on shopexperts, where we can manage your project end-to-end—secure payments, real-time updates, and dedicated support, all in one place.

Simply create your free account here and we’ll take it from there.
Looking forward to working together!`
})

const sendInvitation = () => {
  console.log('Sending invitation...', form)
}
</script>

<template>
  <form @submit.prevent="sendInvitation" class="">
    <div class="mb-8">
      <label class="block text-h4 font-light mb-1" for="fullName">Lead Full Name</label>
      <input
          id="fullName"
          v-model="form.fullName"
          type="text"
          placeholder="Enter lead full name"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
          required
      />
    </div>

    <div class="mb-8">
      <label class="block text-h4 font-light mb-1" for="Email">
        Lead Email
      </label>
      <input
          id="Email"
          v-model="form.email"
          type="text"
          placeholder="Enter email"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
      />
    </div>

    <div class="mb-8">
      <label class="block text-h4 font-light mb-1" for="message">Invitation Message</label>
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

<style scoped>

</style>