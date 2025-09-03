<script setup lang="ts">
import Trash from '@/assets/icons/trash.svg'
import Pencil from '@/assets/icons/pencil.svg'
import star from '@/assets/icons/star.svg'

defineProps<{
  project: {
    id: number
    title: string
    duration: string
    status: string
    images: Array<{
      url: string
      alt?: string
    }>
  }
}>()
</script>

<template>
  <div class="border rounded-md shadow-sm bg-white p-6 mb-4">
    <div class="flex justify-between items-start mb-4">
      <div class="flex items-center gap-2">
        <div class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-sm flex items-center gap-1">
          <star />
          {{ project.status }}
        </div>
      </div>
      <div class="flex gap-2">
        <button class="border border-gray-300 p-2 rounded-sm">
          <Pencil />
        </button>
        <button class="border border-gray-300 p-2 rounded-sm">
          <Trash />
        </button>
      </div>
    </div>

    <h3 class="font-semibold text-lg mb-2">{{ project.title }}</h3>

    <p class="text-gray-500 text-sm mb-4">{{ project.duration }}</p>

    <div class="flex gap-4">
      <div
          v-for="(image, index) in project.images"
          :key="index"
          class="flex-1"
      >
        <img
            :src="image.url"
            :alt="image.alt || `Project image ${index + 1}`"
            class="w-full h-42 object-cover rounded-md"
        />
      </div>
    </div>
  </div>
</template>