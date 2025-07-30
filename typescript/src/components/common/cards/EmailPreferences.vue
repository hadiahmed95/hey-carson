<script setup lang="ts">
import {ref} from "vue";
import ApiService from "@/services/api.service.ts";
import { useAlertStore } from "@/store/alert.ts"

const alertStore = useAlertStore()
const props = defineProps<{
  user: {
    new_messages: string,
    project_notifications: string,
  }
}>()

const form = ref({
  new_messages: props.user.new_messages,
  project_notifications: props.user.project_notifications,
});

async function handleFieldChange(field: keyof typeof form.value, value: string) {
  try {
    const payload = { [field]: value };
    const res = await ApiService.post(`/client/settings`, payload);
    form.value[field] = res.data.user[field];
    alertStore.show(`${field.replace('_', ' ')} updated successfully.`, 'success');
  } catch (error) {
    alertStore.show(`Failed to update ${field}.`, 'error');
  }
}
</script>

<template>
  <div class="mx-auto p-6 bg-white rounded-md border border-gray-200 w-[45rem]">
    <!-- Project Notifications -->
    <div class="mb-8 flex items-center justify-between">
      <div>
        <h3 class="text-paragraph mb-1">Project Notifications</h3>
        <p class="text-h4 text-coolGray mb-4">Instant Notifications</p>
      </div>

      <div class="flex gap-2 font-semibold">
        <button
            :class="[
            'px-4 py-2 text-h4 rounded-sm',
            form.project_notifications === 'instant' ? 'bg-black text-white' : 'bg-greyLight text-greyDark hover:bg-grey'
          ]"
            @click="handleFieldChange('project_notifications', 'instant')"
        >
          Instant
        </button>
        <button
            :class="[
            'px-4 py-2 text-h4 rounded-sm',
            form.project_notifications === 'daily' ? 'bg-greyExtraDark text-white' : 'bg-greyLight text-black hover:bg-grey'
          ]"
            @click="handleFieldChange('project_notifications', 'daily')"
        >
          Daily Summary
        </button>
      </div>
    </div>

    <!-- New Messages -->
    <div class="flex items-center justify-between">
      <div>
        <h3 class="text-paragraph mb-1">New Messages</h3>
        <p class="text-h4 text-coolGray mb-4">Instant Notifications</p>
      </div>

      <div class="flex gap-2 font-semibold">
        <button
            :class="[
            'px-4 py-2 text-h4 rounded-sm',
            form.new_messages === 'instant' ? 'bg-greyExtraDark text-white' : 'bg-greyLight text-black hover:bg-grey'
          ]"
            @click="handleFieldChange('new_messages', 'instant')"
        >
          Instant
        </button>
        <button
            :class="[
            'px-4 py-2 text-h4 rounded-sm',
            form.new_messages === 'daily' ? 'bg-black text-white' : 'bg-greyLight text-greyDark hover:bg-grey'
          ]"
            @click="handleFieldChange('new_messages', 'daily')"
        >
          Daily Summary
        </button>
      </div>
    </div>
  </div>
</template>
