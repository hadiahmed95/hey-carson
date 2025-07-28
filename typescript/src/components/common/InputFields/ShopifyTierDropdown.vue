<template>
  <div class="flex flex-col gap-1">
    <label class="block text-h5 font-sm font-archivo text-primary">What is the Shopify plan of the store you own or work for? <span class="text-tertiary-dark">(Optional)</span></label>
    <select v-model="model" :class="['w-full border px-4 py-2 rounded-md',
      model ? 'text-primary' : 'text-tertiary'
    ]">
      <option disabled value="">Select Tier</option>
      <option>Lite</option>
      <option>Basic</option>
      <option>Shopify</option>
      <option>Advanced</option>
      <option>Plus</option>
      <option>No Shopify Plan</option>
      <option>Not Sure</option>
    </select>
  </div>
</template>

<script setup lang="ts">
const model = defineModel<string>()
</script>
