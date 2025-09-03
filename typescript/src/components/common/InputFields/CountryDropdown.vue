<template>
  <div class="flex flex-col gap-1">
    <label class="block text-h5 font-sm font-archivo text-primary">Country</label>
    <select v-model="model" class="w-full border px-4 py-2 rounded-md text-primary font-archivo text-paragraph">
      <option disabled value="">Select Country</option>
      <option v-for="country in countries" :key="country" :value="country">{{ country }}</option>
    </select>
    <h5 v-if="error" class="text-red-600 mt-2">
      {{ error }}
    </h5>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  error?: string
}>()

const model = defineModel<string>()
const countries = ['United States', 'Canada', 'United Kingdom', 'Australia', 'India', 'Germany', 'France', 'Netherlands', 'Japan', 'Brazil']
</script>
