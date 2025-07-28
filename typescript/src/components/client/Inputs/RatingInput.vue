<template>
  <div class="flex items-center justify-between">
    <span class="text-h4 text-primary w-32">{{ label }}</span>
    <div class="flex space-x-1">
      <template v-for="star in 5" :key="star">
        <svg
            @click="setValue(star)"
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 cursor-pointer"
            :class="star <= (modelValue ?? 0) ? 'text-success' : 'text-grey'"
            fill="currentColor"
            viewBox="0 0 20 20"
        >
          <path
              d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.196 3.674a1 1 0 00.95.69h3.862c.969 0 1.371 1.24.588 1.81l-3.124 2.27a1 1 0 00-.364 1.118l1.196 3.674c.3.921-.755 1.688-1.54 1.118l-3.124-2.27a1 1 0 00-1.175 0l-3.124 2.27c-.784.57-1.838-.197-1.54-1.118l1.196-3.674a1 1 0 00-.364-1.118L2.356 9.1c-.783-.57-.38-1.81.588-1.81h3.862a1 1 0 00.95-.69l1.196-3.674z"
          />
        </svg>
      </template>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  label: string;
  modelValue: number | null;
}>();

const emit = defineEmits(['update:modelValue']);

function setValue(value: number) {
  emit('update:modelValue', value);
}
</script>
