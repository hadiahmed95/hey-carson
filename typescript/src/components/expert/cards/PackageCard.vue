<template>
  <div class="flex border rounded-md shadow-sm bg-white mb-4 p-8">
    <div class="w-1/3">
      <img
          :src="service.image"
          :alt="service.title"
          class="w-full h-full object-cover rounded-md"
      />
    </div>

    <div class="flex-1 px-6">
      <div class="flex flex-col justify-between h-full">
        <div>
          <h5 class="text-gray-500 text-sm mb-2">Packaged Service Name #{{ service.id }}</h5>
          <h3 class="font-semibold text-lg mb-4">{{ service.title }}</h3>
        </div>

        <div>
          <h5 class="text-gray-500 text-sm">Starting Price</h5>
          <h2 class="font-bold text-xl">${{ service.price.toFixed(2) }}</h2>
        </div>

        <div class="flex gap-2 mt-4">
          <button class="border border-gray-300 p-2 rounded-sm">
            <Pencil/>
          </button>
          <button class="border border-gray-300 p-2 rounded-sm">
            <Trash/>
          </button>
        </div>
      </div>

    </div>
  </div>
</template>


<script setup lang="ts">
import Trash from '../../../assets/icons/trash.svg'
import Pencil from '../../../assets/icons/pencil.svg'

defineProps<{
  service: {
    id: number
    title: string
    price: number
    image: string
  }
}>()
</script>