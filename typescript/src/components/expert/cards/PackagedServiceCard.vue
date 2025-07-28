<template>
  <div class="flex flex-row border rounded-md shadow-sm bg-white mb-4 p-6 justify-between">
    <div>
      <ServicePreview />
      <br>

      <h5 class="text-gray-500 font-medium pb-2">Service Name #{{ service.id }}</h5>
      <h3 class="font-semibold">{{ service.title }}</h3>

      <br>
      <h5 class="pb-2">Starting Price</h5>
      <h3 class="font-medium">{{ service.startingPrice }}</h3>
    </div>
    <div class="flex gap-5 items-start">
      <button class="border border-gray-300 p-2 rounded-sm">
        <Pencil/>
      </button>
      <button class="border border-gray-300 p-2 rounded-sm">
        <Trash/>
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import Trash from '../../../assets/icons/trash.svg'
import Pencil from '../../../assets/icons/pencil.svg'
import ServicePreview from '../../../assets/icons/servicePlaceholder.svg'

defineProps<{
  service: {
    id: number
    title: string
    startingPrice: string
    image: string
  }
}>()

</script>

<style scoped>

</style>
