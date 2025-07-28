<template>
  <div class="flex flex-col gap-1">
    <label class="block text-h5 font-sm font-archivo text-primary">What is your minimum project budget for your services?</label>
    <select v-model="model" :class="['w-full border px-4 py-2 rounded-md',
      model ? 'text-primary' : 'text-tertiary'
    ]">
      <option disabled value="">Select budget</option>
      <option value="<$500">< $500</option>
      <option value="$500-$1000">$500 - $1000</option>
      <option value="$1000-$5000">$1000 - $5000</option>
      <option value="$5000-$10000">$5000 - $10,000</option>
      <option value=">$10000">> $10,000</option>
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
</script>
