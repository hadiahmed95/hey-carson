<template>
  <form @submit.prevent="submitForm" class="space-y-6">
    <!-- Packaged Service Thumbnail -->
    <div class="mb-card-padding-lg">
      <label class="block text-h5 font-normal mb-2" for="thumbnail">
        Packaged Service Thumbnail
      </label>
      
      <!-- Upload Area -->
      <div 
        @click="triggerFileSelect"
        class="border border-dashed border-grey rounded-md p-card-padding-lg text-center bg-white transition-colors cursor-pointer"
      >
        <div class="flex flex-col items-center justify-center p-card-padding-lg">
          <!-- Upload Icon -->
          <div class="flex items-center justify-center mb-card-padding-md">
            <svg width="49" height="48" viewBox="0 0 49 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M25.5 6H16.1C12.7397 6 11.0595 6 9.77606 6.65396C8.64708 7.2292 7.7292 8.14708 7.15396 9.27606C6.5 10.5595 6.5 12.2397 6.5 15.6V32.4C6.5 35.7603 6.5 37.4405 7.15396 38.7239C7.7292 39.8529 8.64708 40.7708 9.77606 41.346C11.0595 42 12.7397 42 16.1 42H34.5C36.3599 42 37.2899 42 38.0529 41.7956C40.1235 41.2408 41.7408 39.6235 42.2956 37.5529C42.5 36.7899 42.5 35.8599 42.5 34M38.5 16V4M32.5 10H44.5M21.5 17C21.5 19.2091 19.7091 21 17.5 21C15.2909 21 13.5 19.2091 13.5 17C13.5 14.7909 15.2909 13 17.5 13C19.7091 13 21.5 14.7909 21.5 17ZM30.4801 23.8363L13.5623 39.2161C12.6107 40.0812 12.1349 40.5137 12.0929 40.8884C12.0564 41.2132 12.1809 41.5353 12.4264 41.7511C12.7096 42 13.3526 42 14.6386 42H33.412C36.2903 42 37.7295 42 38.8598 41.5164C40.2789 40.9094 41.4094 39.7789 42.0164 38.3598C42.5 37.2295 42.5 35.7903 42.5 32.912C42.5 31.9435 42.5 31.4593 42.3941 31.0083C42.2611 30.4416 42.0059 29.9107 41.6465 29.4528C41.3605 29.0884 40.9824 28.7859 40.2261 28.1809L34.6317 23.7053C33.8748 23.0998 33.4963 22.7971 33.0796 22.6902C32.7123 22.596 32.3257 22.6082 31.9651 22.7254C31.5559 22.8583 31.1973 23.1843 30.4801 23.8363Z" stroke="#1F2125" stroke-opacity="0.65" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          
          <!-- Upload Text -->
          <div>
            <h4 class="text-primary font-semibold mb-1 text-h5">Packaged Service Thumbnail</h4>
            <p class="text-grey-black-combo text-h5">
              Upload a thumbnail image to make your service stand out.<br/>
              Recommended aspect ratio: 16:9.
            </p>
          </div>
          
          <!-- Hidden File Input -->
          <input
            ref="fileInput"
            type="file"
            accept="image/*"
            class="hidden"
            @change="handleFileChange"
          />
          
          <!-- Preview Image -->
          <div v-if="thumbnailPreview" class="mt-4">
            <img :src="thumbnailPreview" alt="Thumbnail Preview" class="w-32 h-18 object-cover rounded-md border" />
          </div>
        </div>
      </div>
    </div>

    <!-- Packaged Service Name -->
    <div class="mb-card-padding-lg">
      <label class="block text-h5 font-normal mb-1" for="serviceName">
        Packaged Service Name
      </label>
      <input
        id="serviceName"
        v-model="form.serviceName"
        type="text"
        placeholder="Shopify Development and Troubleshooting"
        class="w-full border border-grey rounded-md px-4 py-3 text-paragraph bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
      />
      <p class="text-grey-black-combo text-h5 mt-1">
        Use a clear, descriptive name that clients will recognize instantly.
      </p>
    </div>

    <!-- Starting Price (USD) -->
    <div class="mb-card-padding-lg">
      <label class="block text-h5 font-normal mb-1" for="price">
        Starting Price (USD)
      </label>
      <input
        id="price"
        v-model="form.price"
        type="text"
        placeholder="$950.00"
        class="w-full border border-grey rounded-md px-4 py-3 text-paragraph bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
      />
      <p class="text-grey-black-combo text-h5 mt-1">
        Set a starting price for this service. You can update it later.
      </p>
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
import { useAuthStore } from "@/store/auth.ts";

const authStore = useAuthStore();

// Props for edit mode
interface Props {
  serviceData?: {
    id?: number;
    title?: string;
    price?: number;
    image?: string;
  };
}

const props = withDefaults(defineProps<Props>(), {
  serviceData: undefined
});

const emit = defineEmits<{
  (e: "close"): void;
}>();

// Form reactive state
const form = reactive({
  serviceName: "",
  price: "",
  thumbnail: null as File | null
});

// Status management
const isSubmitting = ref(false);
const thumbnailPreview = ref<string | null>(null);
const fileInput = ref<HTMLInputElement>();

// Handle file selection
const triggerFileSelect = () => {
  fileInput.value?.click();
};

// Computed for edit mode and button text
const isEditMode = computed(() => !!props.serviceData?.id);
const submitButtonText = computed(() => 
  isSubmitting.value 
    ? (isEditMode.value ? "Updating Packaged Service..." : "Adding Packaged Service...")
    : (isEditMode.value ? "Update Packaged Service" : "Add Packaged Service")
);

// Initialize form with data if in edit mode
onMounted(() => {
  if (props.serviceData) {
    form.serviceName = props.serviceData.title || "";
    form.price = props.serviceData.price ? `$${props.serviceData.price.toFixed(2)}` : "";
    if (props.serviceData.image) {
      thumbnailPreview.value = props.serviceData.image;
    }
  }
});

// Handle file change
const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];

  if (!file) return;

  // Check file size (15MB limit)
  if (file.size > 15 * 1024 * 1024) {
    console.error('File size exceeds 15MB.');
    return;
  }

  // Check file type
  if (!file.type.startsWith('image/')) {
    console.error('Please select an image file.');
    return;
  }

  form.thumbnail = file;
  
  // Create preview
  const reader = new FileReader();
  reader.onload = (e) => {
    thumbnailPreview.value = e.target?.result as string;
  };
  reader.readAsDataURL(file);
};

// Submit function
const submitForm = async () => {
  isSubmitting.value = true;

  try {
    const payload = {
      expert_id: authStore.user.id,
      service_name: form.serviceName,
      price: form.price,
      thumbnail: form.thumbnail,
      ...(isEditMode.value && { id: props.serviceData!.id })
    };

    // Here we would typically make an API call
    
    // For now, just close the modal
    emit("close");
  } catch (error: any) {
    console.error('Error adding packaged service:', error);
  } finally {
    isSubmitting.value = false;
  }
};
</script>
