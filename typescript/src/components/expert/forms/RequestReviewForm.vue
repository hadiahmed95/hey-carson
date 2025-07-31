<script setup lang="ts">
import {onMounted, reactive} from 'vue'
import {ref} from "vue";
import Arrow from '@/assets/icons/arrow.svg';
import {validateEmail} from "@/utils/helpers.ts";
import {useExpertStore} from "@/store/expert.ts";
import type {IProjectName} from "@/types.ts";
import { ModelListSelect } from 'vue-search-select'
import {useAuthStore} from "@/store/auth.ts";
import FormStatusMessage from "@/components/common/FormStatusMessage.vue";

const expertStore = useExpertStore();
const authStore = useAuthStore();

const emit = defineEmits<{
  (e: 'close'): void
}>()

const form = reactive({
  fullName: '',
  clientEmail: '',
  companyName: '',
  website: '',
  projectId: null as number | null,
  hiredOnShopexperts: 'Yes',
  repeatedClient: 'Yes',
  projectValue: '',
  message: `Hey [Client Name],

I hope you’re pleased with how [Project Name] turned out. Could you take 2-3 minutes to leave a quick review? Your feedback helps me improve and build my shopexperts profile so future clients can find me more easily.

Thank you so much for your support!`
})

const errors = ref<Record<string, string>>({})
const projectNames = ref<IProjectName[]>()
const generalErrorMessage = ref('')

const validateForm = () => {
  errors.value = {}

  if (!form.fullName.trim()) {
    errors.value.fullName = 'Client name is required.'
  }

  if (!form.clientEmail.trim()) {
    errors.value.clientEmail = 'Client\'s Email is required.'
  } else if (!validateEmail(form.clientEmail.trim())) {
    errors.value.clientEmail = 'Please enter a valid email.'
  }

  if (!form.projectId) {
    errors.value.projectName = 'Please select a Project name.'
  }

  if (!form.message.trim() || form.message.length < 10) {
    errors.value.message = 'Message must be at least 10 characters.'
  }

  return Object.keys(errors.value).length === 0
}

onMounted(async () => {
  projectNames.value = await expertStore.fetchProjectNames()
})

const resetStatus = () => {
  if (generalErrorMessage.value === 'success') {
    emit('close');
    generalErrorMessage.value = '';
  }
  generalErrorMessage.value = '';
}

const sendRequest = () => {
  if (!validateForm()) return

  try {
    const projectName = projectNames?.value?.find((proj) => proj.id === form.projectId)?.name ?? ''
    expertStore.createReviewRequest({
      expert_id: authStore.user.id,
      client_full_name: form.fullName,
      client_email: form.clientEmail,
      client_company_name: form.companyName,
      client_company_website: form.website,
      project_id: form.projectId,
      project_name: projectName,
      hired_on_shopexperts: form.hiredOnShopexperts === 'Yes',
      repeated_client: form.repeatedClient === 'Yes',
      is_client_reviewed: false,
      project_value_range: form.projectValue,
      message: form.message
    })

    generalErrorMessage.value = 'success';
  } catch (error: any) {
    if (error.response) {
      const status = error.response.status;
      const serverMessage = error.response.data?.message || '';
      const validationErrors = error.response.data?.errors || {};

      if (status === 422 && validationErrors) {
        Object.entries(validationErrors).forEach(([key, messages]) => {
          errors.value[key] = Array.isArray(messages) ? messages[0] : messages;
        });
      } else if (status === 400) {
        generalErrorMessage.value = 'Invalid input. Please check your data.';
      } else if (status === 401) {
        generalErrorMessage.value = 'Authentication required. Please login again.';
      } else if (status === 403) {
        generalErrorMessage.value = 'You do not have permission to perform this action.';
      } else if (status === 409) {
        generalErrorMessage.value = 'This value already exists. Please choose a different one.';
      } else {
        generalErrorMessage.value = serverMessage || 'An unexpected error occurred.';
      }
    } else if (error.request) {
      generalErrorMessage.value = 'No response from server. Please check your internet connection.';
    } else {
      generalErrorMessage.value = 'Unexpected error occurred.';
    }
  }
}
</script>

<template>
  <form @submit.prevent="sendRequest" class="space-y-4">
    <div class="mb-5">
      <label class="block text-h4 font-light mb-1" for="fullName">Client Full Name</label>
      <input
          id="fullName"
          v-model="form.fullName"
          type="text"
          placeholder="Enter Client full name"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
      />
      <h5 v-if="errors.fullName" class="text-red-600 mt-2">{{ errors.fullName }}</h5>
    </div>

    <div class="mb-5">
      <label class="block text-h4 font-light mb-1" for="clientEmail">
        Client Email
      </label>
      <input
          id="clientEmail"
          v-model="form.clientEmail"
          type="email"
          placeholder="example@gmail.com"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
      />
      <h5 v-if="errors.clientEmail" class="text-red-600 mt-2">{{ errors.clientEmail }}</h5>
    </div>

    <div class="mb-5">
      <label class="block text-h4 font-light mb-1" for="clientEmail">
        Client Company Name
      </label>
      <input
          id="companyName"
          v-model="form.companyName"
          type="text"
          placeholder="Company Name"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
      />
      <h5 v-if="errors.companyName" class="text-red-600 mt-2">{{ errors.companyName }}</h5>
    </div>

    <div class="mb-5">
      <label class="block text-h4 font-light mb-1" for="website">Client Company Website</label>
      <input
          id="website"
          v-model="form.website"
          type="url"
          placeholder="https://companywebsite.com"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
      />
      <h5 v-if="errors.website" class="text-red-600 mt-2">{{ errors.website }}</h5>
    </div>

    <div class="mb-5" v-if="projectNames">
      <label class="block text-h4 font-light mb-1" for="projectId">Project Name</label>
      <ModelListSelect
          v-model="form.projectId"
          :list="projectNames"
          option-value="id"
          option-text="name"
          placeholder="Search or select a project"
          class="w-full"
      />
      <h5 v-if="errors.projectName" class="text-red-600 mt-2">{{ errors.projectName }}</h5>
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
      <h5 v-if="errors.hiredOnShopexperts" class="text-red-600 mt-2">{{ errors.hiredOnShopexperts }}</h5>
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
      <h5 v-if="errors.repeatedClient" class="text-red-600 mt-2">{{ errors.repeatedClient }}</h5>
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
      <h5 v-if="errors.projectValue" class="text-red-600 mt-2">{{ errors.projectValue }}</h5>
    </div>

    <div class="mb-5">
      <label class="block text-h4 font-light mb-1" for="message">Request a Review Message</label>
      <textarea
          id="message"
          v-model="form.message"
          rows="8"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph text-gray-500"
      />
      <h5 v-if="errors.message" class="text-red-600 mt-2">{{ errors.message }}</h5>
    </div>
    <FormStatusMessage
        :trigger="Date.now()"
        @updateStatus="resetStatus"
        :status="generalErrorMessage"
        message="Review requested successfully."
        class="mt-2"
    />

    <button
        type="submit"
        class="w-full bg-primary text-white text-paragraph font-normal rounded-md py-3 flex items-center justify-center gap-2 hover:bg-gray-800 transition-colors"
    >
      Send Request
      <Arrow />
    </button>
  </form>
</template>
