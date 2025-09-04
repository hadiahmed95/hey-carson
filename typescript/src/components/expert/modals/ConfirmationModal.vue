<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-md w-full max-w-md p-6 shadow-lg">
      <div class="mb-4">
        <h2 class="text-primary font-semibold text-h3 mb-2">{{ title }}</h2>
        <p class="text-tertiary text-paragraph">{{ message }}</p>
      </div>
      
      <div class="flex gap-3 justify-end">
        <button
          @click="emit('cancel')"
          class="px-4 py-2 border border-grey rounded-md text-primary font-medium hover:bg-secondary transition-colors"
        >
          Cancel
        </button>
        <button
          @click="handleConfirm"
          :disabled="loading"
          class="px-4 py-2 bg-danger text-white rounded-md font-medium transition-colors disabled:opacity-50"
        >
          <span v-if="loading">{{ loadingText }}...</span>
          <span v-else>{{ confirmText }}</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import type { ConfirmationModal } from '@/types.ts';

withDefaults(defineProps<ConfirmationModal>(), {
  confirmText: 'Confirm',
  loadingText: 'Processing'
});

const emit = defineEmits<{
  (e: 'confirm'): void;
  (e: 'cancel'): void;
}>();

const loading = ref(false);

const handleConfirm = async () => {
  loading.value = true;
  try {
    emit('confirm');
  } finally {
    loading.value = false;
  }
};
</script>
