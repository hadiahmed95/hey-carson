<template>
  <div class="relative">
    <input
        :type="show ? 'text' : 'password'"
        :value="model"
        @input="e => model = (e.target as HTMLInputElement).value"
        placeholder="Enter your password"
        :class="['w-full px-3 py-2 border border-tertiary rounded-md text-paragraph font-sm focus:outline-none focus:border-tertiary',
          model ? 'text-primary' : 'text-tertiary-dark'
        ]"
    />
    <button
        type="button"
        @click="toggle"
        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-primary"
    >
      <eyeIcon />
    </button>
  </div>
  <h5 v-if="error" class="text-red-600 mt-2">
    {{ error }}
  </h5>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import eyeIcon from '../../../assets/icons/eye2.svg'

const props = defineProps<{
  modelValue: string
  error?: string
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void
}>()

const model = computed({
  get: () => props.modelValue,
  set: (val: string) => emit('update:modelValue', val),
})

const show = ref(false)
const toggle = () => {
  show.value = !show.value
}
</script>
