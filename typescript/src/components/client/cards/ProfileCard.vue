<script setup lang="ts">
import {ref} from 'vue'
import UploadIcon from '@/assets/icons/upload.svg'
import DeleteIcon from '@/assets/icons/delete.svg'
import {getS3URL} from "@/utils/helpers.ts"
import ApiService from "@/services/api.service"
import { useAlertStore } from "@/store/alert.ts"

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
}>();

const form = ref({
  first_name: props.user.first_name,
  last_name: props.user.last_name,
  email: props.user.email,
  url: props.user.url,
  shopify_plan: props.user.shopify_plan,
});

const photoPreview = ref<string>(getS3URL(props.user?.photo))
const isUploading = ref(false)
const fileInput = ref<HTMLInputElement | null>(null)
const alertStore = useAlertStore()

function triggerFileSelect() {
  fileInput.value?.click()
}

async function handleFieldChange(field: keyof typeof form.value, value: string) {
  try {
    const payload = { [field]: value };
    const res = await ApiService.post(`/client/settings`, payload);
    form.value[field] = res.data.user[field];
    if (field === 'email') {
      emit('update-email', res.data.user.email);
    }
    alertStore.show(`${field.replace('_', ' ')} updated successfully.`, 'success');
  } catch (error) {
    alertStore.show(`Failed to update ${field}.`, 'error');
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
  try {
    const res = await ApiService.post(`/picture`, formData, true)
    photoPreview.value = getS3URL(res.data.user.photo)
    alertStore.show('Profile photo updated successfully.', 'success')
  } catch (error) {
    alertStore.show('Failed to upload photo.', 'error')
  } finally {
    isUploading.value = false
  }
}
</script>

<template>
  <div class="bg-white rounded-md p-card-padding w-[45rem] border border-gray-200">
    <div class="flex items-center justify-between gap-4 mb-6 h-24">
      <div class="flex items-center gap-2">
        <img v-if="photoPreview" src="https://framerusercontent.com/images/cc8Flm8m8pPmMC8SxphKC5r2zo.jpg" alt="Profile Picture" class="w-20 rounded-full object-cover" />
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
        <button class="px-4 py-2 rounded-sm border-2 flex items-center border-gray-200 text-h5 gap-1">
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

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
      <div>
        <label class="block text-h5 font-normal text-greyDark mb-1">First Name</label>
        <input type="text"
               @change="handleFieldChange('first_name', form.first_name)"
               v-model="form.first_name"
               class="w-full border border-grey rounded-md px-3 py-2.5 text-paragraph"
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
      <p class="text-h5 text-coolGray mt-1">Your email is not visible to the experts.</p>
    </div>

    <div class="mb-6">
      <label class="block text-h5 font-normal text-greyDark mb-1">Your Website</label>
      <input
          type="text"
          v-model="form.url"
          @change="handleFieldChange('url', form.url)"
          class="w-full border border-grey rounded-md px-3 py-2.5 text-paragraph"
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
    </div>
  </div>
</template>
