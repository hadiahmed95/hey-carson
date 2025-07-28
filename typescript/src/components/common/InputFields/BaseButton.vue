<template>
  <button
      :class="[
      'px-4 py-2 text-sm font-semibold rounded-md border',
      isPrimary
        ? 'bg-black text-white hover:bg-accent hover:text-black'
        : 'bg-white text-black border-tertiary hover:bg-white hover:border-primary',
        (isLoading && loadingButton) && 'opacity-50 cursor-not-allowed'
    ]"
      :disabled="isLoading && loadingButton"
      @click="$emit('click')"
  >
    <Spinner v-if="isLoading && loadingButton" />
    <slot v-else />
  </button>
</template>

<script setup lang="ts">
import Spinner from "@/components/common/Spinner.vue";
import {useLoaderStore} from "@/store/loader.ts";
import {computed} from "vue";

const loader = useLoaderStore();
const isLoading = computed(() => loader.isLoadingState);

defineProps({
  isPrimary: {
    type: Boolean,
    default: false
  },

  loadingButton: {
    type: Boolean,
    default: false
  }
})
defineEmits(['click'])
</script>
