<script setup lang="ts">
import {ref} from "vue";
import ApiService from "@/services/api.service.ts";
import FormStatusMessage from "@/components/common/FormStatusMessage.vue";

const props = defineProps<{
  user: {
    new_messages: string,
    project_notifications: string,
  },
  userType?: string // Add userType to determine which API to call
}>()

const form = ref({
  new_messages: props.user.new_messages,
  project_notifications: props.user.project_notifications,
});

const errors = ref({
  new_messages: '',
  project_notifications: '',
});

const resetErrors = () => {
  errors.value = {
    new_messages: '',
    project_notifications: '',
  }
}

async function handleFieldChange(field: keyof typeof form.value, value: string) {
  try {
    const payload = { [field]: value };
    
    // ✅ Use correct API endpoint based on userType
    const endpoint = props.userType === 'expert' ? '/expert/settings' : '/client/settings';
    console.log('Making API call to:', endpoint); // Debug log
    
    const res = await ApiService.post(endpoint, payload);
    form.value[field] = res.data.user[field];
    errors.value[field] = 'success';
  } catch (error: any) {
    let message = `Failed to update ${field.replace('_', ' ')}.`;

    if (error.response) {
      const status = error.response.status;
      const serverMessage = error.response.data?.message || '';
      const validationErrors = error.response.data?.errors || {};

      if (status === 422 && validationErrors[field]) {
        message = Array.isArray(validationErrors[field])
            ? validationErrors[field][0]
            : validationErrors[field];
      } else if (status === 400) {
        message = 'Invalid input. Please review your entry.';
      } else if (status === 401) {
        message = 'You are not authenticated. Please log in again.';
      } else if (status === 403) {
        message = 'You do not have permission to perform this action.';
      } else if (status === 409) {
        message = 'This value already exists. Please use a different one.';
      } else if (serverMessage) {
        message = serverMessage;
      }
    } else if (error.request) {
      message = 'No response from the server. Please check your internet connection.';
    } else {
      message = 'An unexpected error occurred.';
    }

    console.error('API Error:', error); // Debug log
    errors.value[field] = message;
  }
}
</script>

<template>
  <div class="mx-auto p-6 bg-white rounded-md border border-gray-200 w-[45rem]">
    <!-- Project Notifications -->
    <div class="mb-8">
      <div class="flex items-center justify-between">
        <div>
          <p class="mb-1">Project Notifications</p>
          <p class="text-h4 text-coolGray mb-4">
            {{ form.project_notifications === 'instant' ? 'Instant Notifications' : 'Daily Summary' }}
          </p>
        </div>

        <div class="flex gap-2 font-semibold">
          <button
              :class="[
            'px-4 py-2 text-h4 rounded-sm',
            form.project_notifications === 'instant' ? 'bg-primary text-white' : 'bg-greyLight text-greyDark hover:bg-grey'
          ]"
              @click="handleFieldChange('project_notifications', 'instant')"
          >
            Instant
          </button>
          <button
              :class="[
            'px-4 py-2 text-h4 rounded-sm',
            form.project_notifications === 'daily' ? 'bg-primary text-white' : 'bg-greyLight text-greyDark hover:bg-grey'
          ]"
              @click="handleFieldChange('project_notifications', 'daily')"
          >
            Daily Summary
          </button>
        </div>
      </div>
      <FormStatusMessage
          :trigger="Date.now()"
          :status="errors.project_notifications"
          message="Project notifications updated successfully."
          @updateStatus="resetErrors"
      />
    </div>

    <!-- New Messages -->
    <div>
      <div class="flex items-center justify-between">
        <div>
          <p class="mb-1">New Messages</p>
          <p class="text-h4 text-coolGray mb-4">
            {{ form.new_messages === 'instant' ? 'Instant Notifications' : 'Daily Summary' }}
          </p>
        </div>

        <div class="flex gap-2 font-semibold">
          <button
              :class="[
            'px-4 py-2 text-h4 rounded-sm',
            form.new_messages === 'instant' ? 'bg-primary text-white' : 'bg-greyLight text-greyDark hover:bg-grey'
          ]"
              @click="handleFieldChange('new_messages', 'instant')"
          >
            Instant
          </button>
          <button
              :class="[
            'px-4 py-2 text-h4 rounded-sm',
            form.new_messages === 'daily' ? 'bg-primary text-white' : 'bg-greyLight text-greyDark hover:bg-grey'
          ]"
              @click="handleFieldChange('new_messages', 'daily')"
          >
            Daily Summary
          </button>
        </div>

      </div>
      <FormStatusMessage
          :trigger="Date.now()"
          :status="errors.new_messages"
          message="Message notifications updated successfully."
          @updateStatus="resetErrors"
      />
    </div>

  </div>
</template>