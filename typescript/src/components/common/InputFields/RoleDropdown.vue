<template>
  <div class="flex flex-col gap-1">
    <label class="block text-h5 font-sm font-archivo text-primary">Role</label>
    <select v-model="model" :class="['w-full border px-4 py-2 rounded-md',
      model ? 'text-primary' : 'text-tertiary'
    ]">
      <option disabled value="">Select Role</option>
      <option value="Shopify Developer">Shopify Developer</option>
      <option value="Designer">Designer</option>
    </select>
    <h5 v-if="error" class="text-red-600 mt-2">
      {{ error }}
    </h5>
  </div>
</template>

<script setup lang="ts">
const model = defineModel<string>()
defineProps<{
  error?: string
}>()
</script>
