<template>
  <div class="flex flex-col gap-2">
    <label class="block text-h5 font-sm font-archivo text-primary">Primary Services (up to 3)</label>
    <select @change="handleChange"
            :class="[
              'w-full border px-4 py-2 rounded-md',
              model?.length === 0 ? 'text-tertiary' : 'text-primary'
            ]"
    >
      <option disabled selected value="">Select from the dropdown ...</option>
      <option v-for="service in services" :key="service" :value="service">{{ service }}</option>
    </select>

    <!-- Pills -->
    <div class="flex flex-wrap gap-2">
      <span
          v-for="(service, index) in model"
          :key="index"
          class="bg-white text-primary text-h4 px-3 py-1 border border-tertiary-500 rounded-full flex items-center gap-2"
      >
        {{ service }}
        <button @click="removeService(index)" class="text-primary">×</button>
      </span>
    </div>
    <h5 v-if="error" class="text-red-600 mt-1">
      {{ error }}
    </h5>
  </div>
</template>

<script setup lang="ts">
const model = defineModel<string[]>()
defineProps<{
  error?: string
}>()

const services = [
  'Shopify Store Setup & Management',
  'Shopify Development & Troubleshooting',
  'Shopify Marketing & Sales',
  'Shopify Branding & Design',
  'Shopify Copywriting & Content',
  'Shopify Consulting & Strategy',
  'Shopify Accounting & Financial Services',
  'Shopify AI & Tech Solutions',
  'Shopify Operations & Management',
  'Shopify Technical Support & Maintenance',
  'Shopify Training & Support'
]

const handleChange = (event: Event) => {
  const value = (event.target as HTMLSelectElement).value

  if (value) {
    const current = model.value ?? []

    if (current.length < 3 && !current.includes(value)) {
      model.value = [...current, value]
    }
  }
}

const removeService = (index: number) => {
  model.value?.splice(index, 1)
}
</script>
