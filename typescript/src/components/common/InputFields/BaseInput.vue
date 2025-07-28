<template>
  <div :class="noStyle ? '' : ' flex flex-col gap-1'">
    <!-- Label rendered only if not hidden -->
    <label
        v-if="label && !hideLabel"
        class="block text-h5 font-normal font-archivo text-primary"
    >
      {{ label }}
    </label>

    <!-- Textarea -->
    <textarea
        v-if="textarea"
        :rows="rows"
        :placeholder="placeholder"
        :value="model"
        @input="e => model = (e.target as HTMLTextAreaElement).value"
        :class="[
        'w-full resize-none',
        noStyle
          ? 'px-3 py-2 text-paragraph'
          : 'px-3 py-2 text-paragraph font-normal border border-tertiary-300 rounded-md',
        'focus:outline-none focus:border-tertiary',
        model ? 'text-primary' : 'text-tertiary-dark'
      ]"
    ></textarea>

    <!-- Input -->
    <input
        v-else
        :type="type"
        :placeholder="placeholder"
        :value="model"
        @input="e => model = (e.target as HTMLInputElement).value"
        :disabled="disabled"
        :class="[
        'w-full',
        noStyle
          ? 'px-3 py-2 text-paragraph'
          : 'px-3 py-2 text-paragraph font-normal border border-tertiary-300 rounded-md',
        'focus:outline-none focus:border-tertiary',
        model ? 'text-primary' : 'text-tertiary-dark'
      ]"
    />

    <h5 v-if="error" class="text-red-600 mt-2">
      {{ error }}
    </h5>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = withDefaults(defineProps<{
  label?: string
  type?: string
  placeholder?: string
  modelValue: string
  textarea?: boolean
  rows?: number
  noStyle?: boolean
  hideLabel?: boolean
  error?: string
  disabled?: boolean
}>(), {
  rows: 4,
  noStyle: false,
  hideLabel: false,
})

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void
}>()

const model = computed({
  get: () => props.modelValue,
  set: (val: string) => emit('update:modelValue', val),
})
</script>
