<template>
  <form @submit.prevent="submitForm">
    <!-- Service Category Selection -->
    <div class="mb-card-padding-lg">
      <label class="block text-h5 font-normal mb-1" for="serviceCategory">
        Select Service Category
      </label>
      <select
        id="serviceCategory"
        v-model="form.serviceCategory"
        class="w-full border border-grey rounded-md px-4 py-3 text-paragraph bg-white"
        @change="onServiceCategoryChange"
      >
        <option disabled value="">Choose a service category</option>
        <option value="shopify-development-troubleshooting">Shopify Development and Troubleshooting</option>
        <option value="shopify-marketing-sales">Shopify Marketing and Sales</option>
        <option value="technical-support-maintenance">Technical Support and Maintenance</option>
        <option value="shopify-design-customization">Shopify Design and Customization</option>
        <option value="ecommerce-strategy-consulting">E-commerce Strategy and Consulting</option>
      </select>
    </div>

    <!-- Subservices Section -->
    <div class="mb-6" v-if="form.serviceCategory">
      <div class="mb-card-padding-lg">
        <h4 class="text-h5 mb-1 font-semibold">Subservices</h4>
        <p class="text-h5 font-normal">Select up to 3 subservices for the selected service.</p>
      </div>

      <!-- Subservice #1 -->
      <div class="mb-card-padding-lg">
        <label class="block text-h5 font-normal mb-1" for="subservice1">
          Subservice #1
        </label>
        <select
          id="subservice1"
          v-model="form.subservices[0]"
          class="w-full border border-grey rounded-md px-4 py-3 text-paragraph bg-white"
        >
          <option value="shopify-development">Shopify Development</option>
          <option value="shopify-troubleshooting">Shopify Troubleshooting</option>
          <option value="shopify-ux-enhancement">Shopify UX Enhancement</option>
        </select>
      </div>

      <!-- Subservice #2 -->
      <div class="mb-card-padding-lg">
        <label class="block text-h5 font-normal mb-1" for="subservice2">
          Subservice #2
        </label>
        <select
          id="subservice2"
          v-model="form.subservices[1]"
          class="w-full border border-grey rounded-md px-4 py-3 text-paragraph bg-white"
        >
          <option value="shopify-troubleshooting">Shopify Troubleshooting</option>
          <option value="shopify-development">Shopify Development</option>
          <option value="shopify-ux-enhancement">Shopify UX Enhancement</option>
        </select>
      </div>

      <!-- Subservice #3 -->
      <div class="mb-card-padding-lg">
        <label class="block text-h5 font-normal mb-1" for="subservice3">
          Subservice #3
        </label>
        <select
          id="subservice3"
          v-model="form.subservices[2]"
          class="w-full border border-grey rounded-md px-4 py-3 text-paragraph bg-white"
        >
          <option value="shopify-ux-enhancement">Shopify UX Enhancement</option>
          <option value="shopify-development">Shopify Development</option>
          <option value="shopify-troubleshooting">Shopify Troubleshooting</option>
        </select>
      </div>
    </div>

    <!-- Submit Button -->
    <button
      type="submit"
      :disabled="isSubmitting"
      class="w-full bg-gray-800 text-white text-paragraph font-medium rounded-md py-3 flex items-center justify-center gap-2 hover:bg-gray-900 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
    >
      <span>{{ submitButtonText }}</span>
    </button>
  </form>
</template>

<script setup lang="ts">
import { reactive, ref, onMounted, computed } from "vue";
import type { ExpertServiceForm } from "@/types.ts";
import { useExpertStore } from "@/store/expert.ts";

// Props for edit mode
const props = withDefaults(defineProps<ExpertServiceForm>(), {
  serviceData: undefined
});

const emit = defineEmits<{
  (e: "close"): void;
}>();

const expertStore = useExpertStore();

// Form reactive state
const form = reactive({
  serviceCategory: "shopify-development-troubleshooting",
  subservices: ["shopify-development", "shopify-troubleshooting", "shopify-ux-enhancement"]
});

// Status management
const isSubmitting = ref(false);

// Computed for edit mode and button text
const isEditMode = computed(() => !!props.serviceData?.id);
const submitButtonText = computed(() => 
  isSubmitting.value 
    ? (isEditMode.value ? "Updating Service..." : "Adding Service...")
    : (isEditMode.value ? "Update Offered Service" : "Add Offered Service")
);

// Initialize form with data if in edit mode
onMounted(() => {
  if (props.serviceData) {
    form.serviceCategory = props.serviceData.serviceCategory || "";
    form.subservices = props.serviceData.subcategories || ["", "", ""];
  }
});

// Handle service category change
const onServiceCategoryChange = () => {
    if (!isEditMode.value) {
        form.subservices = ["", "", ""];
    }
};

// Submit function
const submitForm = async () => {
  isSubmitting.value = true;

  try {
    // Filter out empty subservices
    const filteredSubservices = form.subservices.filter(sub => sub.trim() !== "");

    // Map serviceCategory to title for backend
    const categoryTitleMap: Record<string, string> = {
      "shopify-development-troubleshooting": "Shopify Development and Troubleshooting",
      "shopify-marketing-sales": "Shopify Marketing and Sales",
      "technical-support-maintenance": "Technical Support and Maintenance",
      "shopify-design-customization": "Shopify Design and Customization",
      "ecommerce-strategy-consulting": "E-commerce Strategy and Consulting"
    };

    const payload = {
      title: categoryTitleMap[form.serviceCategory] || form.serviceCategory,
      serviceCategory: form.serviceCategory,
      subcategories: filteredSubservices
    };

    if (isEditMode.value && props.serviceData?.id) {
      await expertStore.updateOfferedService(props.serviceData.id, payload);
    } else {
      await expertStore.createOfferedService(payload);
    }
    emit("close");
  } catch (error: any) {
    console.error('Error adding service:', error);
  } finally {
    isSubmitting.value = false;
  }
};
</script>
