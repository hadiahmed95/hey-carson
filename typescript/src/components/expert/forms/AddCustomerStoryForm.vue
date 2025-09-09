<template>
  <form @submit.prevent="submitForm" class="space-y-6">
    <!-- Customer Story Images -->
    <div class="mb-card-padding-lg">
        <label class="block text-h5 font-normal mb-1">
            Customer Story Images
        </label>
        <div class="border border-dashed border-grey rounded-md p-8 text-center bg-white cursor-pointer transition-colors" @click="triggerFileSelect">
            <div class="flex flex-col items-center justify-center">
            <div class="w-48px h-48px mb-4 flex items-center justify-center">
                <svg width="49" height="49" viewBox="0 0 49 49" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M25.5 6.5H16.1C12.7397 6.5 11.0595 6.5 9.77606 7.15396C8.64708 7.7292 7.7292 8.64708 7.15396 9.77606C6.5 11.0595 6.5 12.7397 6.5 16.1V32.9C6.5 36.2603 6.5 37.9405 7.15396 39.2239C7.7292 40.3529 8.64708 41.2708 9.77606 41.846C11.0595 42.5 12.7397 42.5 16.1 42.5H34.5C36.3599 42.5 37.2899 42.5 38.0529 42.2956C40.1235 41.7408 41.7408 40.1235 42.2956 38.0529C42.5 37.2899 42.5 36.3599 42.5 34.5M38.5 16.5V4.5M32.5 10.5H44.5M21.5 17.5C21.5 19.7091 19.7091 21.5 17.5 21.5C15.2909 21.5 13.5 19.7091 13.5 17.5C13.5 15.2909 15.2909 13.5 17.5 13.5C19.7091 13.5 21.5 15.2909 21.5 17.5ZM30.4801 24.3363L13.5623 39.7161C12.6107 40.5812 12.1349 41.0137 12.0929 41.3884C12.0564 41.7132 12.1809 42.0353 12.4264 42.2511C12.7096 42.5 13.3526 42.5 14.6386 42.5H33.412C36.2903 42.5 37.7295 42.5 38.8598 42.0164C40.2789 41.4094 41.4094 40.2789 42.0164 38.8598C42.5 37.7295 42.5 36.2903 42.5 33.412C42.5 32.4435 42.5 31.9593 42.3941 31.5083C42.2611 30.9416 42.0059 30.4107 41.6465 29.9528C41.3605 29.5884 40.9824 29.2859 40.2261 28.6809L34.6317 24.2053C33.8748 23.5998 33.4963 23.2971 33.0796 23.1902C32.7123 23.096 32.3257 23.1082 31.9651 23.2254C31.5559 23.3583 31.1973 23.6843 30.4801 24.3363Z" stroke="#1F2125" stroke-opacity="0.65" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            
            <h4 class="font-medium text-gray-900 mb-1">Customer Story Images</h4>
            <p class="text-grey-black-combo text-sm">
                Add up to 3 images to showcase your work and final results. Recommended aspect ratio: 16:9.
            </p>
            </div>
            
            <!-- Hidden File Input -->
            <input
                ref="fileInput"
                type="file"
                accept="image/*"
                multiple
                class="hidden"
                @change="handleFileChange"
            />
            
            <!-- Preview Images -->
            <div v-if="imagesPreviews.length > 0" class="mt-4 flex gap-2 justify-center flex-wrap">
                <img 
                    v-for="(preview, index) in imagesPreviews" 
                    :key="index"
                    :src="preview"
                    alt="Image Preview"
                    class="w-32 h-18 object-cover rounded-md border"
                />
            </div>
        </div>
    </div>

    <!-- Title -->
    <div class="mb-card-padding-lg">
      <label class="block text-h5 font-normal mb-1" for="title">
        Customer Story Title
      </label>
      <p class="text-grey-black-combo text-h5 mb-2">
        Give your customer story a compelling title that highlights the main outcome.
      </p>
      <input
        id="title"
        v-model="form.title"
        type="text"
        maxlength="255"
        class="w-full border border-grey rounded-md px-4 py-3 text-paragraph bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
        placeholder="e.g., Increased Online Sales by 150% with SEO & Conversion Optimization"
        required
      />
    </div>

    <!-- Client's Problem -->
    <div class="mb-card-padding-lg">
      <label class="block text-h5 font-normal mb-1" for="problem">
        Client's Problem
      </label>
      <p class="text-grey-black-combo text-h5 mb-2">
        Explain briefly the client's problem and the project's main requirement.
      </p>
      <textarea
        id="problem"
        v-model="form.problem"
        rows="5"
        class="w-full border border-grey rounded-md px-4 py-3 text-paragraph bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
        required
      ></textarea>
    </div>

    <!-- Solution -->
    <div class="mb-card-padding-lg">
      <label class="block text-h5 font-normal mb-1" for="solution">
        Solution
      </label>
      <p class="text-grey-black-combo text-h5 mb-2">
        Provide details on how you'll approach the project and outline the process.
      </p>
      <textarea
        id="solution"
        v-model="form.solution"
        rows="5"
        maxlength="20000"
        class="w-full border border-grey rounded-md px-4 py-3 text-paragraph bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
        placeholder="Explain your approach and the steps you took..."
        required
      ></textarea>
    </div>

    <!-- Result -->
    <div class="mb-card-padding-lg">
      <label class="block text-h5 font-normal mb-1" for="result">
        Result
      </label>
      <p class="text-grey-black-combo text-h5 mb-2">
        Give insights into the results and explain what happened after the implementation.
      </p>
      <textarea
        id="result"
        v-model="form.result"
        rows="5"
        maxlength="10000"
        class="w-full border border-grey rounded-md px-4 py-3 text-paragraph bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
        placeholder="Share the measurable outcomes and impact..."
        required
      ></textarea>
    </div>

    <!-- Error Message -->
    <div v-if="errorMessage" class="bg-danger border border-danger rounded-md p-4">
      <p class="text-white text-sm">{{ errorMessage }}</p>
    </div>

    <!-- Submit Button -->
    <button
      type="submit"
      :disabled="isSubmitting"
      class="w-full bg-primary text-white text-paragraph font-medium rounded-md py-3 flex items-center justify-center gap-2 hover:bg-gray-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
    >
      <span>{{ submitButtonText }}</span>
    </button>
  </form>
</template>

<script setup lang="ts">
import { reactive, ref, onMounted, computed } from "vue";
import { useExpertStore } from "@/store/expert.ts";
import type { ExpertCustomerStoryForm } from "@/types";
import { getS3URL } from '@/utils/helpers.ts'

// Props for edit mode
const props = withDefaults(defineProps<ExpertCustomerStoryForm>(), {
  storyData: undefined
});

const emit = defineEmits<{
  (e: "close"): void;
}>();

const expertStore = useExpertStore();

// Form reactive state
const form = reactive({
  title: props.storyData?.title || "",
  problem: props.storyData?.problem || "",
  solution: props.storyData?.solution || "",
  result: props.storyData?.result || "",
  images: [] as File[]
});

// Status management
const isSubmitting = ref(false);
const imagesPreviews = ref<string[]>([]);
const fileInput = ref<HTMLInputElement>();
const errorMessage = ref("");

// Handle file selection
const triggerFileSelect = () => {
  fileInput.value?.click();
};

// Computed for edit mode and button text
const isEditMode = computed(() => !!props.storyData?.id);
const submitButtonText = computed(() => 
  isSubmitting.value 
    ? (isEditMode.value ? "Updating Story..." : "Adding Story...")
    : (isEditMode.value ? "Update Customer Story" : "Add Customer Story")
);

// Initialize form with data if in edit mode
onMounted(() => {
  if (props.storyData && props.storyData.images) {
    imagesPreviews.value = props.storyData.images.map(img => getS3URL(img.url));
  }
});

// Handle file change
const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const files = target.files;

  if (!files || files.length === 0) return;

  // Convert FileList to Array and limit to 3 images
  const selectedFiles = Array.from(files).slice(0, 3);
  
  errorMessage.value = "";
  
  const validFiles: File[] = [];
  let hasError = false;

  for (const file of selectedFiles) {
    if (file.size > 8 * 1024 * 1024) {
      errorMessage.value = `File ${file.name} exceeds 8MB limit. Please choose a smaller image.`;
      hasError = true;
      break;
    }

    if (!file.type.startsWith('image/')) {
      errorMessage.value = 'Please select image files only.';
      hasError = true;
      break;
    }

    validFiles.push(file);
  }

  if (hasError) return;

  form.images = validFiles;
  
  imagesPreviews.value = [];
  const newPreviews: string[] = [];
  let processedCount = 0;

  validFiles.forEach((file, index) => {
    const reader = new FileReader();
    reader.onload = (e) => {
      newPreviews[index] = e.target?.result as string;
      processedCount++;
      
      if (processedCount === validFiles.length) {
        imagesPreviews.value = newPreviews;
      }
    };
    reader.readAsDataURL(file);
  });
};

// Submit function
const submitForm = async () => {
  if (isSubmitting.value) return;

  isSubmitting.value = true;
  errorMessage.value = "";

  try {
    const formData = new FormData();
    formData.append('title', form.title);
    formData.append('problem', form.problem);
    formData.append('solution', form.solution);
    formData.append('result', form.result);

    // Only add images if new files were selected
    if (form.images.length > 0) {
      form.images.forEach((image, index) => {
        formData.append(`images[${index}]`, image);
      });
    }

    if (isEditMode.value && props.storyData) {
      await expertStore.updateCustomerStory(props.storyData.id, formData);
    } else {
      await expertStore.createCustomerStory(formData);
    }

    Object.assign(form, {
      title: "",
      problem: "",
      solution: "",
      result: "",
      images: []
    });
    imagesPreviews.value = [];
    emit("close");
    
  } catch (error: any) {
    console.error('Error submitting customer story:', error);
    
    if (error.response?.data?.message) {
      errorMessage.value = error.response.data.message;
    } else if (error.response?.data?.errors) {
      const errors = error.response.data.errors;
      const firstError = Object.values(errors)[0] as string[];
      errorMessage.value = firstError[0] || "Validation error occurred.";
    } else {
      errorMessage.value = "An error occurred while saving the customer story. Please try again.";
    }
  } finally {
    isSubmitting.value = false;
  }
};
</script>