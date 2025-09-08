<script setup lang="ts">
import {ref} from 'vue'
import UploadIcon from '@/assets/icons/upload.svg'
import DeleteIcon from '@/assets/icons/delete.svg'
import {getS3URL, generateInitialsAvatar} from "@/utils/helpers.ts"
import ApiService from "@/services/api.service"
import { useAlertStore } from "@/store/alert.ts"
import FormStatusMessage from "@/components/common/FormStatusMessage.vue";

const props = defineProps<{
  user: {
    first_name: string,
    last_name: string,
    email: string,
    url: string,
    shopify_plan: string,
    photo: string,
  }
}>()

const emit = defineEmits<{
  (e: 'update-email', value: string): void;
  (e: 'photo-updated', photoPath: string): void;
}>();

const form = ref({
  first_name: props.user.first_name,
  last_name: props.user.last_name,
  email: props.user.email,
  url: props.user.url,
  shopify_plan: props.user.shopify_plan,
});

const resetStatus = () => {
  errors.value = {
    photo: '',
    first_name: '',
    last_name: '',
    email: '',
    url: '',
    shopify_plan: '',
  };
}

const errors = ref({
  photo: '',
  first_name: '',
  last_name: '',
  email: '',
  url: '',
  shopify_plan: '',
});

const triggerCounters = ref({
  photo: 0,
  first_name: 0,
  last_name: 0,
  email: 0,
  url: 0,
  shopify_plan: 0,
});

const isUploading = ref(false)
const fileInput = ref<HTMLInputElement | null>(null)
const alertStore = useAlertStore()

function triggerFileSelect() {
  fileInput.value?.click()
}

async function handleFieldChange(field: keyof typeof form.value, value: string) {
  errors.value[field] = '';
  try {
    const payload = { [field]: value };
    const res = await ApiService.post(`/client/settings`, payload);
    form.value[field] = res.data.user[field];
    if (field === 'email') {
      emit('update-email', res.data.user.email);
    }
    errors.value[field] = 'success';
  } catch (error: any) {
    let message = `Failed to update ${field}.`;

    if (error.response) {
      const status = error.response.status;
      const serverMessage = error.response.data?.message || '';
      const validationErrors = error.response.data?.errors || {};

      if (status === 422 && validationErrors[field]) {
        message = Array.isArray(validationErrors[field])
            ? validationErrors[field][0]
            : validationErrors[field];
      } else if (status === 400) {
        message = 'Invalid input. Please check your data.';
      } else if (status === 401) {
        message = 'Authentication required. Please login again.';
      } else if (status === 403) {
        message = 'You do not have permission to perform this action.';
      } else if (status === 409) {
        message = 'This value already exists. Please choose a different one.';
      } else if (serverMessage) {
        message = serverMessage;
      }
    } else if (error.request) {
      message = 'No response from server. Please check your internet connection.';
    } else {
      message = 'Unexpected error occurred.';
    }

    errors.value[field] = message;
  } finally {
    triggerCounters.value[field]++;
  }
}

async function handleFileChange(event: Event) {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]

  if (!file) return

  if (file.size > 15 * 1024 * 1024) {
    alertStore.show('File size exceeds 15MB.', 'error')
    return
  }

  const formData = new FormData()
  formData.append('file', file)

  isUploading.value = true
  errors.value.photo = ''
  
  try {
    const res = await ApiService.post(`/picture`, formData, true)
    errors.value.photo = 'success'
    
    // Update the local user photo immediately to reflect the change
    if (res.data.user && res.data.user.photo) {
      // Emit event to parent to update the user data
      emit('photo-updated', res.data.user.photo)
    }
    
    triggerCounters.value.photo++
    
    if (fileInput.value) {
      fileInput.value.value = ''
    }
    
  } catch (error: any) {
    let message = 'Failed to upload photo.'

    if (error.response) {
      const status = error.response.status
      const serverMessage = error.response.data?.message || ''

      if (status === 413) {
        message = 'Photo is too large. Please upload a smaller image.'
      } else if (status === 422) {
        message = 'Invalid photo format. Please try a different file.'
      } else if (serverMessage) {
        message = serverMessage
      }
    } else if (error.request) {
      message = 'No response from server. Please check your internet connection.'
    } else {
      message = 'Unexpected error occurred.'
    }

    errors.value.photo = message
    alertStore.show(message, 'error')
    
  } finally {
    isUploading.value = false
    triggerCounters.value.photo++;
  }
}
</script>

<template>
  <div class="bg-white rounded-md p-card-padding max-w-[45rem] w-full border border-gray-200">
    <div class="mb-6">
      <div class="flex flex-col lg:flex-row lg:items-center justify-between  gap-4 ">
        <div class="flex items-center gap-2">
          <div class="w-20 h-20 rounded-full overflow-hidden">
            <!-- Show actual image if photo exists -->
            <img
                v-if="user.photo && user.photo !== null && user.photo !== ''"
                :src="getS3URL(user.photo)"
                alt="Profile Picture"
                class="w-full h-full rounded-full object-cover"
            />
            <!-- Show initials avatar if no photo -->
            <div
                v-else
                :class="[
                  'w-full h-full rounded-full flex items-center justify-center text-white font-semibold text-h3',
                  generateInitialsAvatar(user.first_name + ' ' + user.last_name).bgColor
                ]"
            >
              {{ generateInitialsAvatar(user.first_name + ' ' + user.last_name).initials }}
            </div>
          </div>
          <div class="flex flex-col gap-1">
            <h4 class="font-semibold">Profile Picture</h4>
            <p class="text-h4 text-greyDark">PNG, JPG, GIF under 15MB.</p>
          </div>
        </div>

        <div class="flex gap-2">
          <button
              @click="triggerFileSelect"
              :disabled="isUploading"
              class="bg-primary text-white px-4 py-2 rounded-sm text-h5 flex items-center gap-1"
          >
            <UploadIcon class="w-4 h-4" />
            <span>{{ isUploading ? 'Uploading...' : 'Upload Image' }}</span>
          </button>
          <button class="px-4 py-2 rounded-sm border-2 flex items-center border-gray-200 text-h5 gap-1 font-bold">
            <DeleteIcon class="w-4 h-4" />
            <span>Delete</span>
          </button>
        </div>

        <input
            ref="fileInput"
            type="file"
            accept="image/*"
            class="hidden font-archivo"
            @change="handleFileChange"
        />
      </div>
      <FormStatusMessage
        :trigger="triggerCounters.photo"
        @updateStatus="resetStatus"
        :status="errors.photo"
        message="Profile photo updated successfully."
      />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
      <div>
        <label class="block text-h5 font-normal text-greyDark mb-1">First Name</label>
        <input type="text"
           @change="handleFieldChange('first_name', form.first_name)"
           v-model="form.first_name"
           class="w-full border border-grey rounded-md px-3 py-2.5 text-paragraph"
        />
        <FormStatusMessage
          :trigger="triggerCounters.first_name"
          @updateStatus="resetStatus"
          class="mt-2"
          :status="errors.first_name"
          message="First Name updated successfully."
        />
      </div>
      <div>
        <label class="block text-h5 font-normal text-greyDark mb-1">Last Name</label>
        <input
            type="text"
            v-model="form.last_name"
            class="w-full border border-grey rounded-md px-3 py-2.5 text-paragraph"
            @change="handleFieldChange('last_name', form.last_name)"
        />
        <FormStatusMessage
          :trigger="triggerCounters.last_name"
          @updateStatus="resetStatus"
          class="mt-2"
          :status="errors.last_name"
          message="Last Name updated successfully."
        />
      </div>
    </div>

    <div class="mb-6">
      <label class="block text-h5 font-normal text-greyDark mb-1">Email</label>
      <input
          type="email"
          v-model="form.email"
          class="w-full border border-grey rounded-md px-3 py-2.5 text-paragraph"
          @change="handleFieldChange('email', form.email)"
      />
      <h5 class="text-coolGray mt-1">Your email is not visible to the experts.</h5>
      <FormStatusMessage
        :trigger="triggerCounters.email"
        @updateStatus="resetStatus"
        class="mt-2"
        :status="errors.email"
        message="Email updated successfully."
      />
    </div>

    <div class="mb-6">
      <label class="block text-h5 font-normal text-greyDark mb-1">Your Website</label>
      <input
          type="text"
          v-model="form.url"
          @change="handleFieldChange('url', form.url)"
          class="w-full border border-grey rounded-md px-3 py-2.5 text-paragraph"
      />
      <FormStatusMessage
        :trigger="triggerCounters.url"
        @updateStatus="resetStatus"
        class="mt-2"
        :status="errors.url"
        message="Website updated successfully."
      />
    </div>

    <div>
      <label class="block text-h5 font-normal text-greyDark mb-1">Shopify Plan</label>
      <select
          v-model="form.shopify_plan"
          @change="handleFieldChange('shopify_plan', form.shopify_plan)"
          class="w-full border border-grey rounded-md px-3 py-2.5 text-paragraph"
      >
        <option value="Advanced">Advanced</option>
        <option value="Basic">Basic</option>
        <option value="Shopify">Shopify</option>
        <option value="Plus">Plus</option>
      </select>
      <FormStatusMessage
        :trigger="triggerCounters.shopify_plan"
        @updateStatus="resetStatus"
        class="mt-2"
        :status="errors.shopify_plan"
        message="Shopify plan updated successfully."
      />
    </div>
  </div>
</template>
