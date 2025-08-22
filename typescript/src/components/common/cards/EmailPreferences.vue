<script setup lang="ts">
import {ref} from "vue";
import FormStatusMessage from "@/components/common/FormStatusMessage.vue";
import {useClientStore} from "@/store/client.ts";
import {useExpertStore} from "@/store/expert.ts";

const clientStore = useClientStore();
const expertStore = useExpertStore();
const triggerCounters = ref({
  new_messages: 0,
  project_notifications: 0,
});

const props = defineProps<{
  user: {
    new_messages: string,
    project_notifications: string,
  },
  userType?: string
}>()

const form = ref({
  new_messages: props.user.new_messages,
  project_notifications: props.user.project_notifications,
});

const errors = ref({
  new_messages: '',
  project_notifications: '',
});

const resetNewMessages = () => {
  errors.value.new_messages = '';
}

const resetProjectNotifications = () => {
  errors.value.project_notifications = '';
}

async function handleFieldChange(field: keyof typeof form.value, value: string) {
  try {
    const payload = { [field]: value };
    let res;
    if (props.userType === 'expert') {
      res = await expertStore.updateProfile(payload);
    } else {
      res = await clientStore.updateProfile(payload);
    }

    form.value[field] = res.user[field];
    errors.value[field] = 'success';

    triggerCounters.value[field]++;
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

    triggerCounters.value[field]++;
  }
}
</script>

<template>
  <div class="p-6 bg-white rounded-md border border-gray-200 max-w-[45rem] w-full">
    <!-- Project Notifications -->
    <div class="mb-8">
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
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
          :trigger="triggerCounters.project_notifications"
          :status="errors.project_notifications"
          message="Project notifications updated successfully."
          @updateStatus="resetProjectNotifications"
      />
    </div>


    <!-- New Messages -->
    <div>
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
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
          :trigger="triggerCounters.new_messages"
          :status="errors.new_messages"
          message="Message notifications updated successfully."
          @updateStatus="resetNewMessages"
      />
    </div>

  </div>
</template>
