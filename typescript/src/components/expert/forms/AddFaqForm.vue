<template>
  <form @submit.prevent="submitForm" class="space-y-6">
    <!-- Question -->
    <div class="mb-card-padding-lg">
      <label class="block text-h5 font-normal mb-1 text-primary" for="question">
        Question
      </label>
      <p class="text-grey-black-combo text-h5 mb-1">
        Use clear language that your clients would understand and relate to.
      </p>
      <input
        id="question"
        v-model="form.question"
        type="text"
        placeholder="Write the question as a client might ask it."
        class="w-full border border-grey rounded-md px-4 py-3 text-paragraph bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
        required
      />
    </div>

    <!-- Answer -->
    <div class="mb-card-padding-lg">
      <label class="block text-h5 font-normal mb-1 text-primary" for="answer">
        Answer
      </label>
      <p class="text-grey-black-combo text-h5 mb-1">
        Provide helpful, detailed information that addresses the client's concern completely.
      </p>
      <textarea
        id="answer"
        v-model="form.answer"
        rows="5"
        placeholder="Keep your answer clear, straightforward, and concise."
        class="w-full border border-grey rounded-md px-4 py-3 text-paragraph bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
        required
      ></textarea>
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
import { reactive, ref, computed } from "vue";

const emit = defineEmits<{
  (e: "close"): void;
}>();

// Form reactive state
const form = reactive({
  question: "",
  answer: ""
});

// Status management
const isSubmitting = ref(false);

// Button text
const submitButtonText = computed(() => 
  isSubmitting.value ? "Adding FAQ..." : "Add FAQ"
);

// Submit function
const submitForm = async () => {
  isSubmitting.value = true;

  try {
    // Here we would typically make an API call
    
    // For now, just close the modal
    emit("close");
  } catch (error: any) {
    console.error('Error adding FAQ:', error);
  } finally {
    isSubmitting.value = false;
  }
};
</script>
